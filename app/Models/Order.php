<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log; // ← tambahkan ini
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'seller_id',
        'address_id',

        'grand_total',

        'payment_method',
        'payment_status',
        'payment_proof',
        'payment_notes',

        'shipping_method',
        'shipping_cost',
        'shipping_address',

        'total_weight',
        'tracking_number',

        'status',
        'notes',

        // Midtrans fields
        'snap_token',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'paid_at',
    ];

    protected $casts = [
        'grand_total'   => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_at'       => 'datetime',
        'total_weight' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number =
                    'ORD-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(6));
            }
        });
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // =====================================================
    // PAYMENT STATUS METHODS
    // =====================================================

    public function isPaid(): bool
    {
        return !is_null($this->paid_at) || $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending' || $this->payment_status === 'pending';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isMidtransPaid(): bool
    {
        return $this->payment_method === 'midtrans' && $this->isPaid();
    }

    /**
     * Mark order as paid.
     * ⚠️ COD tidak boleh otomatis lunas — bayar saat barang sampai.
     */
    public function markAsPaid(): void
    {
        if ($this->payment_method === 'cod') {
            Log::warning('Attempted to markAsPaid() on a COD order', ['order_id' => $this->id]);
            return;
        }

        $this->update([
            'paid_at'        => now(),
            'payment_status' => 'paid',
            'status'         => 'processing',
        ]);
    }

    /**
     * Mark COD order sebagai selesai & lunas.
     * Dipanggil manual oleh admin/seller setelah barang diterima.
     */
    public function markCodDelivered(): void
    {
        if ($this->payment_method !== 'cod') return;

        $this->update([
            'paid_at'        => now(),
            'payment_status' => 'paid',
            'status'         => 'delivered',
        ]);
    }

    public function usesMidtrans(): bool
    {
        return $this->payment_method === 'midtrans';
    }

    public function canBePaid(): bool
    {
        return !$this->isPaid()
            && !$this->isCancelled()
            && in_array($this->status, ['pending']);
    }

    // =====================================================
    // ACCESSORS & MUTATORS
    // =====================================================

    public function getTotalAttribute()
    {
        return $this->grand_total ?? 0;
    }

    public function getFullShippingAddressAttribute(): string
    {
        if ($this->address) {
            return $this->address->formatted_address;
        }

        if ($this->shipping_address) {
            return $this->shipping_address;
        }

        return 'Address not available';
    }

    public function getRecipientInfoAttribute(): array
    {
        if ($this->address) {
            return [
                'name'  => $this->address->recipient_name,
                'phone' => $this->address->phone,
            ];
        }

        return [
            'name'  => $this->user->name ?? 'N/A',
            'phone' => 'N/A',
        ];
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        if ($this->payment_method === 'cod') {
            return $this->isPaid() ? 'Lunas (COD)' : 'COD - Bayar di Tempat';
        }

        return $this->isPaid() ? 'Dibayar' : 'Belum Dibayar';
    }

    public function getPaymentStatusColorAttribute(): string
    {
        if ($this->payment_method === 'cod' && !$this->isPaid()) {
            return 'blue';
        }

        return $this->isPaid() ? 'green' : 'yellow';
    }

    public function getStatusBadgeAttribute(): array
    {
        $badges = [
            'pending'    => ['text' => 'Menunggu', 'color' => 'yellow'],
            'processing' => ['text' => 'Diproses', 'color' => 'blue'],
            'shipped'    => ['text' => 'Dikirim', 'color' => 'purple'],
            'delivered'  => ['text' => 'Selesai', 'color' => 'green'],
            'cancelled'  => ['text' => 'Dibatalkan', 'color' => 'red'],
        ];

        return $badges[$this->status] ?? ['text' => ucfirst($this->status), 'color' => 'gray'];
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        $labels = [
            'midtrans' => 'Transfer Bank / E-Wallet (Midtrans)',
            'cod'      => 'Cash on Delivery (COD)',
            'transfer' => 'Transfer Bank Manual',
        ];

        return $labels[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    public function getShippingMethodLabelAttribute(): string
    {
        $labels = [
            'regular' => 'Reguler (3-5 hari)',
            'express' => 'Express (1-2 hari)',
        ];

        return $labels[$this->shipping_method] ?? ucfirst($this->shipping_method);
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopeUnpaid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('paid_at')
              ->orWhere('payment_status', '!=', 'paid');
        });
    }

    public function scopePaid($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('paid_at')
              ->orWhere('payment_status', 'paid');
        });
    }

    public function scopeMidtrans($query)
    {
        return $query->where('payment_method', 'midtrans');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
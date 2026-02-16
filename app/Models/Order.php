<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'seller_id',
        'address_id',

        'subtotal',
        'total_amount',
        'grand_total',

        'payment_method',
        'payment_status',
        'payment_proof',
        'payment_notes',
        'transfer_date',

        'shipping_method',
        'shipping_cost',
        'shipping_amount',
        'shipping_address',

        'tracking_number',

        'status',
        'currency',
        'notes',

        'snap_token',
    ];


    protected $casts = [
        'grand_total' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
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

    /**
     * Relationship: Order belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Seller 
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * ✅ Relationship: Order belongs to Address (UserAddress)
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    /**
     * Relationship: Order has many OrderItems
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * ✅ Accessor: Get shipping address with fallback
     * 
     * Priority:
     * 1. Relasi address (jika masih ada)
     * 2. shipping_address string (snapshot)
     */
    public function getFullShippingAddressAttribute(): string
    {
        // Priority 1: Dari relasi address
        if ($this->address) {
            return $this->address->formatted_address;
        }

        // Priority 2: Dari shipping_address string (snapshot)
        if ($this->shipping_address) {
            return $this->shipping_address;
        }

        return 'Address not available';
    }

    /**
     * ✅ Accessor: Get recipient info dengan fallback
     */
    public function getRecipientInfoAttribute(): array
    {
        if ($this->address) {
            return [
                'name' => $this->address->recipient_name,
                'phone' => $this->address->phone,
            ];
        }

        // Fallback ke user info
        return [
            'name' => $this->user->name ?? 'N/A',
            'phone' => 'N/A',
        ];
    }
    // App\Models\Order.php
    public function getTotalAttribute()
    {
        return $this->grand_total ?? $this->total_amount ?? 0;
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by payment status
     */
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }
}

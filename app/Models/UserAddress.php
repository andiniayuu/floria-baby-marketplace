<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'addresses'; // ⚠️ Assuming tabel bernama 'addresses'

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'province',
        'city',
        'district',
        'subdistrict',
        'postal_code',
        'full_address',
        'notes',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relationship: Address belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Get formatted address string
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->full_address,
            $this->subdistrict,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Accessor: Get full recipient info
     */
    public function getFullRecipientAttribute(): string
    {
        return "{$this->recipient_name} ({$this->phone})";
    }

    /**
     * Boot method: Ensure only one primary address per user
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($address) {
            if ($address->is_primary) {
                // Remove primary flag from other addresses
                static::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_primary' => false]);
            }
        });
    }
}
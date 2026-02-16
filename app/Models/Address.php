<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'label',
        'recipient_name',
        'phone',
        'province',
        'city',
        'district',
        'subdistrict',
        'postal_code',
        'street_address',
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
     * 🔧 ACCESSOR: Get formatted address string
     * Format: Street, City, State Zipcode
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = [];

        if ($this->street_address) {
            $parts[] = $this->street_address;
        }

        if ($this->street_address) $parts[] = $this->street_address;
        if ($this->village) $parts[] = $this->village;
        if ($this->subdistrict) $parts[] = $this->subdistrict;
        if ($this->district) $parts[] = $this->district;
        if ($this->province) $parts[] = $this->province;

        // City, State Zipcode
        $location = [];
        if ($this->city) $location[] = $this->city;
        if ($this->state) $location[] = $this->state;
        if ($this->zip_code) $location[] = $this->zip_code;

        if (!empty($location)) {
            $parts[] = implode(' ', $location);
        }

        return implode(', ', $parts);
    }

    /**
     * 🔧 ACCESSOR: Get full recipient info with phone
     */
    public function getFullRecipientAttribute(): string
    {
        return "{$this->recipient_name} ({$this->phone})";
    }

    /**
     * 🔧 BOOT: Auto-manage primary address
     * Hanya 1 alamat yang bisa primary per user
     */
    protected static function boot()
    {
        parent::boot();

        // Before saving
        static::saving(function ($address) {
            // Jika ini dijadikan primary
            if ($address->is_primary) {
                // Remove primary dari alamat lain milik user yang sama
                static::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_primary' => false]);
            }
        });

        // After creating (jika alamat pertama, auto set primary)
        static::created(function ($address) {
            $count = static::where('user_id', $address->user_id)->count();

            // Jika ini alamat pertama user, set as primary
            if ($count === 1 && !$address->is_primary) {
                $address->update(['is_primary' => true]);
            }
        });
    }
}

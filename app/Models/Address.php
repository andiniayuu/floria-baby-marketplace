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
        'label',
        'first_name',
        'last_name',
        'phone',
        'street_address',
        'city',
        'state',
        'zip_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Get full name
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // Helper: Get alamat lengkap untuk tampilan
    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->street_address,
            $this->city,
            $this->state,
            $this->zip_code,
        ]));
    }

    // Helper: Set sebagai alamat default
    public function setAsDefault(): void
    {
        // Set semua alamat user lain jadi false
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);
        
        // Set alamat ini jadi default
        $this->update(['is_default' => true]);
    }

    // Scope: Get default address for user
    public function scopeDefault($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->where('is_default', true)
                     ->first();
    }
}
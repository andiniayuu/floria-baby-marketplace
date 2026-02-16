<?php

namespace App\Models;

use App\Models\UserAddress;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Kolom yang bisa diisi mass-assignment
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'phone',
        'role',
        'is_banned',
        'seller_status',
        'shop_name',
        'shop_description',
        'password',

    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // User → Alamat baru (UserAddress)
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    // User → Primary address
    public function primaryAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_primary', true);
    }

    // Helper attribute untuk primary address
    public function getPrimaryAddressAttribute()
    {
        return $this->primaryAddress;
    }

    // User → Orders (pembeli)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Seller → Products
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    // Seller → Orders
    public function sellerOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller' && $this->seller_status === 'approved';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /*
    |--------------------------------------------------------------------------
    | FILAMENT ACCESS (kontrol akses ke panel no middleware)
    |--------------------------------------------------------------------------
    */

    public function canAccessPanel(Panel $panel): bool
    {
        // Cek banned user
        if ($this->is_banned) {
            return false;
        }

        //Cek akses berdasarkan panel
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'seller') {
            return $this->role === 'seller'
                && $this->seller_status === 'approved'
                && SellerRequest::where('user_id', $this->id)
                ->where('status', 'approved')
                ->exists();
        }

        return false;
    }
}

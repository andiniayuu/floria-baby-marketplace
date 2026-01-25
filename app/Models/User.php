<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'seller_status',
        'shop_name',
        'shop_description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
        return $this->role === 'seller'
            && $this->seller_status === 'approved';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /*
    |--------------------------------------------------------------------------
    | FILAMENT ACCESS
    |--------------------------------------------------------------------------
    */

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->is_banned) {
            return false;
        }

        return match ($panel->getId()) {
            'admin'  => $this->isAdmin(),
            'seller' => $this->isSeller(),
            default  => false,
        };
    }
}

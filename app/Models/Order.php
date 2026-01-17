<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id',
        'order_number',
        'grand_total',
        'payment_method',
        'payment_status',
        'payment_proof',
        'status',
        'shipping_address',
        'shipping_method',
        'shipping_amount',
        'currency',
        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Pembeli
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Seller / Owner
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Item di dalam order
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'price',
        'compare_price',
        'stock',
        'weight',
        'sku',
        'images',
        'is_active',
        'is_featured',
        'on_sale',
    ];

    protected $casts = [
        'images'        => 'array',
        'price'         => 'integer',
        'compare_price' => 'integer',
        'stock'         => 'integer',
        'weight'        => 'integer',
        'is_active'     => 'boolean',
        'is_featured'   => 'boolean',
        'on_sale'       => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (Filament::auth()->check()) {
                $product->seller_id = Filament::auth()->id();
            }
        });
    }

    // RELATIONS

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

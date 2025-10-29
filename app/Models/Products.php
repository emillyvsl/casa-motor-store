<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'sku',
        'category_id',
        'shipping_profile_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'allow_out_of_stock_sales',
        'max_backorder',
        'stock_alert_threshold',
        'is_featured',
        'attributes',
        'discount_price',
        'is_active',
    ];

    protected $casts = [
        'attributes' => 'array',
        'allow_out_of_stock_sales' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function shippingProfile()
    {
        return $this->belongsTo(ShippingProfile::class);
    }

    public function images()
    {
        return $this->hasMany(ProductsImages::class);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrdersItems::class);
    }
}

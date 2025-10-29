<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingProfile extends Model
{
    protected $fillable = [
        'name',
        'delivery_time_in_stock',
        'delivery_time_backorder',
        'shipping_cost',
        'description',
        'type',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(Products::class);
    }
}

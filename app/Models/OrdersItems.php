<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersItems extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'subtotal',
        'fulfillment_mode',
        'stock_quantity',
        'backordered_quantity',
        'lead_time_days',
        'estimated_delivery_days',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'stock_quantity' => 'integer',
        'backordered_quantity' => 'integer',
        'lead_time_days' => 'integer',
        'estimated_delivery_days' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}

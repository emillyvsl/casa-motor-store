<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'total',
        'payment_method',
        'tracking_code',
        'shipped_at',
        'delivered_at',
        'shipping_cost',
        'shipping_service',
        'shipping_delivery_days',
        'estimated_delivery_days',
        'discount_total',
        'address_id',
        'coupon_id',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'shipping_cost' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_delivery_days' => 'integer',
        'estimated_delivery_days' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(Addresses::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupons::class);
    }

    public function items()
    {
        return $this->hasMany(OrdersItems::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payments::class, 'order_id');
    }
}

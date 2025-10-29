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
        'discount_total',
        'address_id',
        'coupon_id',
    ];

    protected $dates = ['shipped_at', 'delivered_at'];

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
        return $this->hasMany(OrdersItems::class);
    }

    public function payment()
    {
        return $this->hasOne(Payments::class);
    }
}

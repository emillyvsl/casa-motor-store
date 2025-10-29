<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'status',
        'amount',
        'transaction_data',
        'installments',
        'gateway',
        'paid_at',
    ];

    protected $casts = [
        'transaction_data' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}

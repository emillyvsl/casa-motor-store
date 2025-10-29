<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $fillable = [
        'product_id',
        'customer_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

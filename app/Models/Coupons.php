<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected $fillable = [
        'code',
        'discount_value',
        'discount_percent',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $fillable = [
        'customer_id',
        'country',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'cep',
        'is_default',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }
}

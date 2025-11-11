<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOrigin extends Model
{
    protected $fillable = ['name', 'cep', 'address', 'city', 'state'];
}

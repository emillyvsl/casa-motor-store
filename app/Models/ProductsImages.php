<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsImages extends Model
{
    protected $fillable = ['product_id', 'path', 'is_main'];

    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
}

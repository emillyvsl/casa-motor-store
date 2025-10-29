<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    protected $fillable = [
        'customer_id',
        'session_id',
        'total',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function addProduct(Products $product, int $quantity = 1)
    {
        $item = $this->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->subtotal = $item->quantity * $item->unit_price;
            $item->save();
        } else {
            $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'subtotal' => $quantity * $product->price,
            ]);
        }

        $this->updateTotal();
    }

    public function removeProduct(Products $product)
    {
        $this->items()->where('product_id', $product->id)->delete();
        $this->updateTotal();
    }

    public function updateTotal()
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }
}

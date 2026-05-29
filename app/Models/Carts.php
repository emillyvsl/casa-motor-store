<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    protected $fillable = [
        'customer_id',
        'session_id',
        'total',
        'shipping_service',
        'shipping_cost',
        'shipping_cep_destino',
        'shipping_delivery_days',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function addProduct(Products $product, int $quantity = 1)
    {
        $item = $this->items()->where('product_id', $product->id)->first();
        $unitPrice = $product->effective_price;

        if ($item) {
            $item->quantity += $quantity;
            $item->unit_price = $unitPrice;
            $item->subtotal = $item->quantity * $item->unit_price;
            $item->save();
        } else {
            $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $quantity * $unitPrice,
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

    public function clearShippingSelection(): void
    {
        $this->update([
            'shipping_service' => null,
            'shipping_cost' => 0,
            'shipping_cep_destino' => null,
            'shipping_delivery_days' => null,
        ]);
    }
}

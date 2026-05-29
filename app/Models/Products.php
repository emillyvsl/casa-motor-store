<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'sku',
        'category_id',
        'shipping_profile_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'allow_out_of_stock_sales',
        'max_backorder',
        'stock_alert_threshold',
        'weight',
        'width',
        'height',
        'length',
        'is_featured',
        'attributes',
        'discount_price',
        'is_active',
        'backorder_delivery_days',
        'out_of_stock_message',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock' => 'integer',
        'max_backorder' => 'integer',
        'stock_alert_threshold' => 'integer',
        'backorder_delivery_days' => 'integer',
        'weight' => 'decimal:3',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'length' => 'decimal:2',
        'attributes' => 'array',
        'allow_out_of_stock_sales' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getEffectivePriceAttribute(): float
    {
        if ($this->discount_price !== null && (float) $this->discount_price >= 0 && (float) $this->discount_price < (float) $this->price) {
            return (float) $this->discount_price;
        }

        return (float) $this->price;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock > 0) {
            return 'in_stock';
        }

        if ($this->allow_out_of_stock_sales) {
            return 'backorder';
        }

        return 'out_of_stock';
    }

    public function hasStockFor(int $quantity = 1): bool
    {
        return $this->stock >= max($quantity, 1);
    }

    public function availableBackorderQuantity(): ?int
    {
        if (! $this->allow_out_of_stock_sales) {
            return 0;
        }

        return $this->max_backorder === null ? null : max((int) $this->max_backorder, 0);
    }

    public function availableQuantity(): ?int
    {
        $backorderQuantity = $this->availableBackorderQuantity();

        if ($backorderQuantity === null) {
            return null;
        }

        return max((int) $this->stock, 0) + $backorderQuantity;
    }

    public function canFulfillQuantity(int $quantity): bool
    {
        $quantity = max($quantity, 1);
        $availableQuantity = $this->availableQuantity();

        if ($availableQuantity === null) {
            return true;
        }

        return $availableQuantity >= $quantity;
    }

    public function backorderedQuantityFor(int $quantity): int
    {
        return max(0, max($quantity, 1) - max((int) $this->stock, 0));
    }

    public function stockQuantityFor(int $quantity): int
    {
        return min(max((int) $this->stock, 0), max($quantity, 1));
    }

    public function leadTimeDaysFor(int $quantity): int
    {
        return $this->backorderedQuantityFor($quantity) > 0
            ? max((int) $this->backorder_delivery_days, 0)
            : 0;
    }

    public function fulfillmentModeFor(int $quantity): string
    {
        $stockQuantity = $this->stockQuantityFor($quantity);
        $backorderedQuantity = $this->backorderedQuantityFor($quantity);

        if ($backorderedQuantity === 0) {
            return 'in_stock';
        }

        if ($stockQuantity === 0) {
            return 'backorder';
        }

        return 'mixed';
    }

    public function availabilityMessageFor(int $quantity = 1): string
    {
        $quantity = max($quantity, 1);

        if ($this->canFulfillQuantity($quantity)) {
            if ($this->backorderedQuantityFor($quantity) > 0) {
                $leadTime = $this->leadTimeDaysFor($quantity);

                return $leadTime > 0
                    ? "Parte do pedido será atendida sob encomenda em até {$leadTime} dias úteis."
                    : 'Parte do pedido será atendida sob encomenda.';
            }

            return 'Produto disponível para compra.';
        }

        $limit = $this->availableQuantity();

        if ($limit === 0) {
            return $this->out_of_stock_message ?: 'Produto indisponível no momento.';
        }

        if ($limit === null) {
            return 'Produto disponível sob encomenda.';
        }

        return "Quantidade indisponível. Limite disponível para venda: {$limit} unidade(s).";
    }

    public function lowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= max((int) $this->stock_alert_threshold, 0);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function shippingProfiles()
    {
        return $this->belongsToMany(
            ShippingProfile::class,
            'product_shipping_profile',
            'product_id',      // chave local correta
            'shipping_profile_id' // chave relacionada correta
        );
    }

    public function images()
    {
        return $this->hasMany(ProductsImages::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrdersItems::class, 'product_id');
    }
}

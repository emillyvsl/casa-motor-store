<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'shipping_service')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('shipping_service')->nullable();
            });
        }

        if (! Schema::hasColumn('orders', 'shipping_delivery_days')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('shipping_delivery_days')->nullable();
            });
        }

        if (! Schema::hasColumn('orders', 'estimated_delivery_days')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('estimated_delivery_days')->nullable();
            });
        }

        if (! Schema::hasColumn('orders_items', 'product_name')) {
            Schema::table('orders_items', function (Blueprint $table) {
                $table->string('product_name')->nullable();
            });
        }

        if (! Schema::hasColumn('orders_items', 'product_sku')) {
            Schema::table('orders_items', function (Blueprint $table) {
                $table->string('product_sku')->nullable();
            });
        }

        if (! Schema::hasColumn('orders_items', 'fulfillment_mode')) {
            Schema::table('orders_items', function (Blueprint $table) {
                $table->string('fulfillment_mode')->nullable();
            });
        }

        if (! Schema::hasColumn('orders_items', 'stock_quantity')) {
            Schema::table('orders_items', function (Blueprint $table) {
                $table->integer('stock_quantity')->default(0);
            });
        }

        if (! Schema::hasColumn('orders_items', 'backordered_quantity')) {
            Schema::table('orders_items', function (Blueprint $table) {
                $table->integer('backordered_quantity')->default(0);
            });
        }

        if (! Schema::hasColumn('orders_items', 'lead_time_days')) {
            Schema::table('orders_items', function (Blueprint $table) {
                $table->integer('lead_time_days')->default(0);
            });
        }

        if (! Schema::hasColumn('orders_items', 'estimated_delivery_days')) {
            Schema::table('orders_items', function (Blueprint $table) {
                $table->integer('estimated_delivery_days')->default(0);
            });
        }
    }

    public function down(): void
    {
        foreach (['estimated_delivery_days', 'shipping_delivery_days', 'shipping_service'] as $column) {
            if (Schema::hasColumn('orders', $column)) {
                Schema::table('orders', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        foreach ([
            'estimated_delivery_days',
            'lead_time_days',
            'backordered_quantity',
            'stock_quantity',
            'fulfillment_mode',
            'product_sku',
            'product_name',
        ] as $column) {
            if (Schema::hasColumn('orders_items', $column)) {
                Schema::table('orders_items', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable()->unique(); // código interno / referência
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('shipping_profile_id')->nullable()->constrained('shipping_profiles')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->boolean('allow_out_of_stock_sales')->default(false);
            $table->integer('max_backorder')->nullable();
            $table->integer('stock_alert_threshold')->default(5);
            $table->boolean('is_featured')->default(false);
            $table->json('attributes')->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('backorder_delivery_days')->nullable()->default(0);
            $table->string('out_of_stock_message')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

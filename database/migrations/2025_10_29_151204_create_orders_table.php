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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, paid, shipped, delivered, canceled
            $table->decimal('total', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // Pix, cartão, boleto
            $table->string('tracking_code')->nullable(); // código de rastreio
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

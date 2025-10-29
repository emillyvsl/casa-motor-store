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
        Schema::create('shipping_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: "Padrão Nacional", "Sob Encomenda", "Entrega Expressa"
            $table->integer('delivery_time_in_stock')->default(3); // Dias úteis se houver estoque
            $table->integer('delivery_time_backorder')->default(10); // Dias úteis sob encomenda
            $table->decimal('shipping_cost', 10, 2)->default(0); // Valor fixo ou base do frete
            $table->text('description')->nullable();
            $table->string('type')->default('default');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Frete selecionado — persistido para usar no checkout
            $table->string('shipping_service')->nullable()->after('total');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_service');
            $table->string('shipping_cep_destino')->nullable()->after('shipping_cost');
            $table->integer('shipping_delivery_days')->nullable()->after('shipping_cep_destino');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_service',
                'shipping_cost',
                'shipping_cep_destino',
                'shipping_delivery_days',
            ]);
        });
    }
};

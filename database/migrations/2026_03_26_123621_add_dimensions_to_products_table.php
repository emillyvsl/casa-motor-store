<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Dimensoes fisicas usadas no calculo de frete (Melhor Envio)
            $table->decimal('weight', 8, 3)->default(0.3)->after('stock');  // kg
            $table->decimal('width', 8, 2)->default(16)->after('weight');   // cm
            $table->decimal('height', 8, 2)->default(16)->after('width');   // cm
            $table->decimal('length', 8, 2)->default(20)->after('height');  // cm
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight', 'width', 'height', 'length']);
        });
    }
};

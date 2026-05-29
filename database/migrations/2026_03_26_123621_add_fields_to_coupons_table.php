<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('type')->default('fixed')->after('code');       // 'fixed' | 'percent'
            $table->integer('max_uses')->nullable()->after('is_active');   // null = uso ilimitado
            $table->integer('used_count')->default(0)->after('max_uses'); // contador de usos
            $table->decimal('min_order_value', 10, 2)->default(0)->after('used_count');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['type', 'max_uses', 'used_count', 'min_order_value']);
        });
    }
};

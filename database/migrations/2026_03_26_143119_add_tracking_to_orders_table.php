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
        if (! Schema::hasColumn('orders', 'tracking_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('tracking_code')->nullable()->after('status');
            });
        }

        if (! Schema::hasColumn('orders', 'shipped_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('shipped_at')->nullable()->after('tracking_code');
            });
        }

        if (! Schema::hasColumn('orders', 'delivered_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['tracking_code', 'shipped_at', 'delivered_at'] as $column) {
            if (Schema::hasColumn('orders', $column)) {
                Schema::table('orders', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};

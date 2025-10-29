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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('method'); // Pix, cartão, boleto
            $table->string('status')->default('pending');
            $table->decimal('amount', 10, 2);
            $table->json('transaction_data')->nullable(); // Dados da API de pagamento
            $table->integer('installments')->default(1);
            $table->string('gateway')->nullable(); // Ex: MercadoPago, Stripe etc.
            $table->timestamp('paid_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

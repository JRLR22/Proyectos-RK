<?php



// ============================================
// MIGRACIÓN 8: create_payments_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')
                ->constrained('orders', 'order_id')
                ->onDelete('cascade');
            $table->enum('method', ['tarjeta', 'transferencia', 'efectivo', 'paypal']);
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id', 100)->nullable();
            $table->enum('status', ['aprobado', 'pendiente', 'rechazado'])
                ->default('pendiente');
            $table->timestamp('payment_date')->useCurrent();
            $table->timestamps();
            $table->index('order_id');
            $table->index('status');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

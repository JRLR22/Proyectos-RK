<?php

// ============================================
// MIGRACIÓN 6: create_orders_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');
            $table->timestamp('date')->useCurrent();
            $table->decimal('total', 10, 2)->default(0);
            $table->text('shipping_address')->nullable();
            $table->enum('status', ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'])
                ->default('pendiente');
            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


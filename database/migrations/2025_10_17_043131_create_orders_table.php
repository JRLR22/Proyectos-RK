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
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->timestamp('date')->useCurrent();
            $table->enum('status', ['pendiente', 'pagado', 'enviado', 'cancelado'])->default('pendiente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


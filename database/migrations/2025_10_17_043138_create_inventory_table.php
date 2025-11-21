<?php


// ============================================
// MIGRACIÓN 12: create_inventory_table.php
// ============================================


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->foreignId('book_id')
                ->constrained('books', 'book_id')
                ->onDelete('cascade');
            $table->foreignId('store_id')
                ->constrained('stores', 'store_id')
                ->onDelete('cascade');
            $table->enum('movement_type', ['entrada', 'venta', 'ajuste', 'devolución']);
            $table->integer('quantity');
            $table->integer('stock_after')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
            $table->index('book_id');
            $table->index('store_id');
            $table->index(['book_id', 'store_id']);
            $table->index('movement_type');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};


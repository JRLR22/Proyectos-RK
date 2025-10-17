<?php


// ============================================
// MIGRACIÓN 7: create_order_items_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->integer('quantity')->check('quantity > 0');
            $table->decimal('price', 8, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};


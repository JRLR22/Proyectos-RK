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
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->enum('movement_type', ['entrada', 'venta', 'ajuste']);
            $table->integer('quantity');
            $table->timestamp('date')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};


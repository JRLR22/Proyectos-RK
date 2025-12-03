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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->foreignId('cart_id')->constrained('carts', 'cart_id')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_addition', 10, 2); // Guardar precio al momento de agregar
            $table->timestamps();

            // Evitar duplicados de libro en el mismo carrito
            $table->unique(['cart_id', 'book_id']);
            
            // Índices
            $table->index('cart_id');
            $table->index('book_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
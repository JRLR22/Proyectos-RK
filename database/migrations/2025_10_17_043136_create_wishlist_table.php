<?php


// ============================================
// MIGRACIÓN 11: create_wishlist_table.php
// ============================================



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlist', function (Blueprint $table) {
            $table->id('wishlist_id');
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');
            $table->foreignId('book_id')
                ->constrained('books', 'book_id')
                ->onDelete('cascade');
            $table->timestamp('added_date')->useCurrent();
            $table->timestamps();
            $table->unique(['user_id', 'book_id'], 'unique_user_book_wishlist');
            $table->index('user_id');
            $table->index('book_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist');
    }
};

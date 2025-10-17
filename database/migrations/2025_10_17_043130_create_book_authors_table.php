<?php


// ============================================
// MIGRACIÓN 5: create_book_authors_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_authors', function (Blueprint $table) {
            $table->id('book_author_id');
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('authors', 'author_id')->onDelete('cascade');
            $table->integer('author_order')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_authors');
    }
};






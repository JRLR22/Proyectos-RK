<?php



// ============================================
// MIGRACIÓN 4: create_books_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id('book_id');
            $table->string('isbn', 13)->unique();
            $table->string('gonvill_code', 100)->unique();
            $table->string('title', 200);
            $table->string('subtitle', 200)->nullable();
            $table->string('publisher', 100)->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('edition', 50)->nullable();
            $table->string('language', 30)->default('Español');
            $table->decimal('price', 8, 2);
            $table->integer('pages')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->text('description')->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->foreignId('category_id')
                ->constrained('categories', 'category_id')
                ->onDelete('cascade');
            $table->enum('status', ['En stock', 'No en stock'])->default('En stock');
            $table->enum('type', ['Papel', 'Impresión bajo demanda', 'eBook'])->default('Papel');
            $table->timestamps();
            $table->index('title');
            $table->index('publisher');
            $table->index('category_id');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};




<?php


// ============================================
// MIGRACIÓN 3: create_categories_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_category_id')
                ->nullable()
                ->constrained('categories', 'category_id')
                ->onDelete('cascade');
            $table->timestamps();
            $table->index('parent_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};


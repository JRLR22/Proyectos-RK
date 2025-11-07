<?php


// ============================================
// MIGRACIÓN 2: create_authors_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id('author_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->text('biography')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('website', 200)->nullable();
            $table->timestamps();
            $table->index(['first_name', 'last_name']);
            $table->index('nationality');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};



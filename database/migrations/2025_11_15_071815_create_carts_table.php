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
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Null para guests
            $table->string('session_id', 100)->nullable(); // Para usuarios no autenticados
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamps();
            $table->timestamp('expires_at')->nullable(); // Para limpiar carritos abandonados

            // Índices
            $table->index('user_id');
            $table->index('session_id');
            $table->unique(['user_id'], 'unique_user_cart')->where('user_id', '!=', null);
            
            // Foreign key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
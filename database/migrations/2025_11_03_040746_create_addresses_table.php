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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('address_id'); // Usamos address_id para consistencia
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Información del destinatario
            $table->string('recipient_name');
            $table->string('phone', 20);
            
            // Dirección completa
            $table->string('street_address'); // Calle y número
            $table->string('apartment')->nullable(); // Depto, interior, etc.
            $table->string('neighborhood')->nullable(); // Colonia
            $table->string('city');
            $table->string('state');
            $table->string('postal_code', 10);
            $table->string('country')->default('México');
            
            // Referencias adicionales
            $table->text('references')->nullable(); // "Entre calle X y Y", "Casa azul", etc.
            
            // Control
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
            $table->index(['user_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
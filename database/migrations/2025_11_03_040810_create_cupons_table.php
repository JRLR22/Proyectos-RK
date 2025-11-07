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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id('coupon_id'); // Usamos coupon_id para consistencia
            
            // Código del cupón (ej: "BIENVENIDA10", "VERANO2024")
            $table->string('code', 50)->unique();
            
            // Descripción para el admin/usuario
            $table->string('description')->nullable();
            
            // Tipo de descuento: 'percentage' o 'fixed'
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            
            // Valor del descuento
            $table->decimal('discount_value', 10, 2);
            
            // Condiciones
            $table->decimal('min_purchase', 10, 2)->default(0)->comment('Compra mínima requerida');
            $table->integer('max_uses')->nullable()->comment('Máximo de usos totales (null = ilimitado)');
            $table->integer('max_uses_per_user')->default(1)->comment('Máximo de usos por usuario');
            
            // Contador de usos
            $table->integer('used_count')->default(0);
            
            // Validez
            $table->timestamp('starts_at')->nullable()->comment('Fecha de inicio');
            $table->timestamp('expires_at')->nullable()->comment('Fecha de expiración');
            
            // Estado
            $table->boolean('active')->default(true);
            
            $table->timestamps();
            
            // Índices
            $table->index('code');
            $table->index('active');
            $table->index('expires_at');
        });

        // Tabla para rastrear quién ha usado qué cupón
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            
            // Relación con coupons (usando coupon_id)
            $table->unsignedBigInteger('coupon_id');
            $table->foreign('coupon_id')
                  ->references('coupon_id')
                  ->on('coupons')
                  ->onDelete('cascade');
            
            // Relación con users (usando user_id)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Relación con orders (usando order_id)
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade');
            
            $table->decimal('discount_applied', 10, 2);
            $table->timestamp('used_at');
            
            $table->index(['coupon_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('coupons');
    }
};
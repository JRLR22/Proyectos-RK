<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id('shipping_method_id');
            
            // Información del método
            $table->string('name');
            $table->text('description')->nullable();
            
            // Costos
            $table->decimal('base_cost', 10, 2)->comment('Costo base del envío');
            $table->decimal('cost_per_kg', 10, 2)->default(0)->comment('Costo adicional por kilogramo');
            $table->decimal('free_shipping_threshold', 10, 2)->nullable()->comment('Compra mínima para envío gratis');
            
            // Tiempos de entrega
            $table->integer('estimated_days_min')->comment('Días mínimos de entrega');
            $table->integer('estimated_days_max')->comment('Días máximos de entrega');
            
            // Restricciones geográficas
            $table->json('available_states')->nullable()->comment('Estados donde está disponible');
            
            // Estado
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0)->comment('Orden de visualización');
            
            $table->timestamps();
            
            // Índices
            $table->index('active');
            $table->index('sort_order');
        });

        // Insertar métodos de envío por defecto
        DB::table('shipping_methods')->insert([
            [
                'name' => 'Estándar',
                'description' => 'Entrega en 5-7 días hábiles',
                'base_cost' => 80.00,
                'cost_per_kg' => 15.00,
                'free_shipping_threshold' => 500.00,
                'estimated_days_min' => 5,
                'estimated_days_max' => 7,
                'active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Express',
                'description' => 'Entrega en 2-3 días hábiles',
                'base_cost' => 150.00,
                'cost_per_kg' => 25.00,
                'free_shipping_threshold' => 1000.00,
                'estimated_days_min' => 2,
                'estimated_days_max' => 3,
                'active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recoger en Tienda',
                'description' => 'Recoge tu pedido en cualquiera de nuestras tiendas',
                'base_cost' => 0.00,
                'cost_per_kg' => 0.00,
                'free_shipping_threshold' => null,
                'estimated_days_min' => 1,
                'estimated_days_max' => 2,
                'active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
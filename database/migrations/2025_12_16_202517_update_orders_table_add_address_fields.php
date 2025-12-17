<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Solo eliminar columnas viejas y agregar foreign keys si no existen
            
            // Eliminar shipping_address si existe
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
            
            // Eliminar date si existe
            if (Schema::hasColumn('orders', 'date')) {
                $table->dropColumn('date');
            }
        });
        
        // Agregar foreign keys en una transacción separada
        Schema::table('orders', function (Blueprint $table) {
            // Verificar si la foreign key no existe antes de agregarla
            $foreignKeys = DB::select("
                SELECT constraint_name 
                FROM information_schema.table_constraints 
                WHERE table_name = 'orders' 
                AND constraint_type = 'FOREIGN KEY'
            ");
            
            $existingKeys = array_column($foreignKeys, 'constraint_name');
            
            if (!in_array('orders_address_id_foreign', $existingKeys)) {
                $table->foreign('address_id')
                    ->references('address_id')
                    ->on('addresses')
                    ->onDelete('set null');
            }
            
            // Solo agregar si las tablas existen
            if (Schema::hasTable('shipping_methods') && !in_array('orders_shipping_method_id_foreign', $existingKeys)) {
                $table->foreign('shipping_method_id')
                    ->references('shipping_method_id')
                    ->on('shipping_methods')
                    ->onDelete('set null');
            }
            
            if (Schema::hasTable('coupons') && !in_array('orders_coupon_id_foreign', $existingKeys)) {
                $table->foreign('coupon_id')
                    ->references('coupon_id')
                    ->on('coupons')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            
            if (Schema::hasTable('shipping_methods')) {
                $table->dropForeign(['shipping_method_id']);
            }
            
            if (Schema::hasTable('coupons')) {
                $table->dropForeign(['coupon_id']);
            }
            
            $table->text('shipping_address')->nullable();
            $table->timestamp('date')->useCurrent();
        });
    }
};
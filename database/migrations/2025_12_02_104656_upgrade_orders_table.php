<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('orders', function (Blueprint $table) {

            // Identificador único de orden
            $table->string('order_number', 20)->unique()->after('order_id');

            // Totales
            $table->decimal('subtotal', 10, 2)->default(0)->after('user_id');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);

            // Información de pago
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');

            // Relaciones
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('shipping_method_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();

            // Opcionales
            $table->text('notes')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
        });
    }

    public function down() {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'subtotal',
                'discount_amount',
                'shipping_cost',
                'tax_amount',
                'payment_method',
                'payment_status',
                'address_id',
                'shipping_method_id',
                'coupon_id',
                'notes',
                'tracking_number',
                'shipped_at',
                'delivered_at',
                'cancelled_at',
                'cancellation_reason',
            ]);
        });
    }
};

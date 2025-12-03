<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {


            $table->renameColumn('method', 'payment_method');

     
            $table->string('payment_method')->default('pending')->change();
            $table->string('status')->default('pending')->change();

       
            $table->decimal('amount', 10, 2)->change();
            $table->string('currency', 3)->default('MXN')->after('amount');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('paypal_order_id')->nullable();
            $table->text('payment_details')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            // Índices
            $table->index('stripe_payment_intent_id');
            $table->index('paypal_order_id');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->string('payment_method')->change();
            $table->string('status')->change();


            $table->dropColumn([
                'currency',
                'stripe_payment_intent_id',
                'paypal_order_id',
                'payment_details',
                'error_message',
                'paid_at',
                'refunded_at'
            ]);

            $table->dropIndex(['stripe_payment_intent_id']);
            $table->dropIndex(['paypal_order_id']);


            $table->renameColumn('payment_method', 'method');
        });
    }
};

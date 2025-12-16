<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar CHECKs heredados de enums
        DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_method_check');
        DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_status_check');
    }

    public function down(): void
    {
        // Re-crear checks originales 
        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT payments_method_check
            CHECK (payment_method IN ('tarjeta','transferencia','efectivo','paypal'))
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT payments_status_check
            CHECK (status IN ('aprobado','pendiente','rechazado'))
        ");
    }
};

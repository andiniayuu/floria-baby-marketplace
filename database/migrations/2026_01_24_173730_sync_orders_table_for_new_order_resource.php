<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // 1. total_amount (baru)
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable()->after('grand_total');
            }

            // 2. shipping_cost (baru)
            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->nullable()->after('shipping_amount');
            }

            // 3. ubah status jadi string
            $table->string('status')->change();
        });

        // 4. Copy data lama → kolom baru
        DB::statement('UPDATE orders SET total_amount = grand_total WHERE total_amount IS NULL');
        DB::statement('UPDATE orders SET shipping_cost = shipping_amount WHERE shipping_cost IS NULL');

        // 5. Mapping status lama → status baru
        DB::statement("
            UPDATE orders SET status = 
            CASE status
                WHEN 'new' THEN 'pending'
                WHEN 'processing' THEN 'processing'
                WHEN 'shipped' THEN 'shipped'
                WHEN 'delivered' THEN 'delivered'
                WHEN 'cancelled' THEN 'cancelled'
                ELSE status
            END
        ");
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->dropColumn('total_amount');
            }

            if (Schema::hasColumn('orders', 'shipping_cost')) {
                $table->dropColumn('shipping_cost');
            }
        });
    }
};

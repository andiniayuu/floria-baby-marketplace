<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('midtrans_snap_token')->nullable()->after('payment_method');
            $table->string('midtrans_order_id')->nullable()->after('midtrans_snap_token');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_payment_type')->nullable()->after('midtrans_transaction_id');
            $table->timestamp('paid_at')->nullable()->after('midtrans_payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_snap_token',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'paid_at'
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->unique()->after('id');
            }

            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->cascadeOnDelete()
                    ->after('order_number');
            }

            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('shipping_method');
            }

            if (!Schema::hasColumn('orders', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (Schema::hasColumn('orders', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }

            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }

            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('orders', 'order_number')) {
                $table->dropColumn('order_number');
            }
        });
    }
};

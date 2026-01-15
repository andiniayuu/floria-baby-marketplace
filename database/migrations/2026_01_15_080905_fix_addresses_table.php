<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Drop FK lama
        Schema::table('addresses', function (Blueprint $table) {
            try {
                $table->dropForeign(['order_id']);
            } catch (\Throwable $e) {
                // FK sudah tidak ada → aman
            }
        });

        // 2️⃣ Rename kolom
        Schema::table('addresses', function (Blueprint $table) {
            if (
                Schema::hasColumn('addresses', 'order_id') &&
                !Schema::hasColumn('addresses', 'user_id')
            ) {
                $table->renameColumn('order_id', 'user_id');
            }
        });

        // 3️⃣ Tambah kolom baru
        Schema::table('addresses', function (Blueprint $table) {

            if (!Schema::hasColumn('addresses', 'label')) {
                $table->string('label')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('addresses', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('zip_kode');
            }
        });

        // 4️⃣ Tambah FK baru ke users
        Schema::table('addresses', function (Blueprint $table) {
            try {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            } catch (\Throwable $e) {
                // FK sudah ada → skip
            }
        });
    }

    public function down(): void
    {
        // Rollback jarang dipakai di production, tapi tetap aman
        Schema::table('addresses', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {}
        });

        Schema::table('addresses', function (Blueprint $table) {
            if (
                Schema::hasColumn('addresses', 'user_id') &&
                !Schema::hasColumn('addresses', 'order_id')
            ) {
                $table->renameColumn('user_id', 'order_id');
            }

            if (Schema::hasColumn('addresses', 'label')) {
                $table->dropColumn('label');
            }

            if (Schema::hasColumn('addresses', 'is_default')) {
                $table->dropColumn('is_default');
            }
        });

        Schema::table('addresses', function (Blueprint $table) {
            try {
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->cascadeOnDelete();
            } catch (\Throwable $e) {}
        });
    }
};

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
        Schema::table('orders', function (Blueprint $table) {

            // Jika kolom SUDAH ADA, jangan tambah lagi
            if (Schema::hasColumn('orders', 'seller_id')) {

                // Drop FK lama (kalau ada)
                try {
                    $table->dropForeign(['seller_id']);
                } catch (\Throwable $e) {
                    // amanin kalau FK belum ada
                }

                // Tambah FK baru yang BENAR
                $table->foreign('seller_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['seller_id']);
            } catch (\Throwable $e) {
                //
            }
        });
    }
};

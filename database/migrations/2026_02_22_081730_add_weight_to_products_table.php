<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambah kolom weight jika belum ada
            if (!Schema::hasColumn('products', 'weight')) {
                $table->integer('weight')->nullable()->after('stock')->comment('Berat dalam gram');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }
};
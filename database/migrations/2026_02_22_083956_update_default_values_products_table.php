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
        // Set default untuk data yang sudah ada
        DB::statement('UPDATE products SET compare_price = 0 WHERE compare_price IS NULL');
        DB::statement('UPDATE products SET weight = 0 WHERE weight IS NULL');
        DB::statement('UPDATE products SET is_featured = 0 WHERE is_featured IS NULL');
        DB::statement('UPDATE products SET on_sale = 0 WHERE on_sale IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

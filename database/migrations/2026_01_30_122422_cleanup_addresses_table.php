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
         Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasColumn('addresses', 'street_address')) {
                $table->dropColumn('street_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->text('street_address')->nullable()->after('phone');
        });
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            // Role: admin, seller, user
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'seller', 'user'])->default('user')->after('email');
            }

            // Status seller: pending, approved, rejected
            if (!Schema::hasColumn('users', 'seller_status')) {
                $table->enum('seller_status', ['pending', 'approved', 'rejected'])->nullable()->after('role');
            }

            // Informasi toko untuk seller
            if (!Schema::hasColumn('users', 'shop_name')) {
                $table->string('shop_name')->nullable()->after('seller_status');
            }

            if (!Schema::hasColumn('users', 'shop_description')) {
                $table->text('shop_description')->nullable()->after('shop_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->after('user_id');
            $table->string('province')->nullable()->after('label');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('subdistrict')->nullable()->after('district');
            $table->string('postal_code')->nullable()->after('subdistrict');
            $table->text('full_address')->nullable()->after('postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name', 'province', 'city', 'district', 'subdistrict', 'postal_code', 'full_address'
            ]);
        });
    }
};

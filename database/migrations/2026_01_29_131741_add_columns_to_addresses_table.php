<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'label')) {
                $table->string('label')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('addresses', 'notes')) {
                $table->text('notes')->nullable()->after('zip_code');
            }
            if (!Schema::hasColumn('addresses', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasColumn('addresses', 'label')) {
                $table->dropColumn('label');
            }
            if (Schema::hasColumn('addresses', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('addresses', 'is_primary')) {
                $table->dropColumn('is_primary');
            }
        });
    }
};

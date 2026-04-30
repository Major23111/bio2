<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('shipments') && ! Schema::hasColumn('shipments', 'tracking_url')) {
            Schema::table('shipments', function (Blueprint $table) {
                $table->string('tracking_url')->nullable()->after('tracking_number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('shipments') && Schema::hasColumn('shipments', 'tracking_url')) {
            Schema::table('shipments', function (Blueprint $table) {
                $table->dropColumn('tracking_url');
            });
        }
    }
};

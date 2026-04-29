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
        if (! Schema::hasTable('product_variants') || Schema::hasColumn('product_variants', 'pack_size')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('pack_size')->nullable()->after('variant_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('product_variants') || ! Schema::hasColumn('product_variants', 'pack_size')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('pack_size');
        });
    }
};

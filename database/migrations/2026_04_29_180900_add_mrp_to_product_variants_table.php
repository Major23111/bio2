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
        if (! Schema::hasTable('product_variants') || Schema::hasColumn('product_variants', 'mrp')) {
            return;
        }

        $afterColumn = Schema::hasColumn('product_variants', 'pack_size') ? 'pack_size' : 'variant_name';

        Schema::table('product_variants', function (Blueprint $table) use ($afterColumn): void {
            $table->decimal('mrp', 10, 2)->default(0)->after($afterColumn);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('product_variants') || ! Schema::hasColumn('product_variants', 'mrp')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table): void {
            $table->dropColumn('mrp');
        });
    }
};

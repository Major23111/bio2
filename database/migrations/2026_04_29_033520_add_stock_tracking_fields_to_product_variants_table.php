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
        if (! Schema::hasTable('product_variants')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'coa_no')) {
                $table->string('coa_no')->nullable()->after('stock_quantity');
            }

            if (! Schema::hasColumn('product_variants', 'batch_no')) {
                $table->string('batch_no')->nullable()->after('coa_no');
            }

            if (! Schema::hasColumn('product_variants', 'mfg_date')) {
                $table->date('mfg_date')->nullable()->after('batch_no');
            }

            if (! Schema::hasColumn('product_variants', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('mfg_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('product_variants')) {
            return;
        }

        $columnsToDrop = array_values(array_filter(
            ['coa_no', 'batch_no', 'mfg_date', 'expiry_date'],
            fn (string $column): bool => Schema::hasColumn('product_variants', $column),
        ));

        if ($columnsToDrop === []) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn($columnsToDrop);
        });
    }
};

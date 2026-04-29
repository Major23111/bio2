<?php

namespace Tests\Feature;

use App\Models\Product\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_variant_model_columns_exist_after_migrations(): void
    {
        foreach ((new ProductVariant())->getFillable() as $column) {
            $this->assertTrue(
                Schema::hasColumn('product_variants', $column),
                "Missing product_variants.{$column}; add a migration before deploying code that writes it."
            );
        }
    }

    public function test_deploy_critical_tables_exist_after_migrations(): void
    {
        foreach (['product_variants', 'product_prices', 'notifications', 'categories'] as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Missing {$table} table after migrations."
            );
        }
    }
}

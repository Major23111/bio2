<?php

namespace App\Services\AdminPanel;

use App\Models\Pricing\ProductBulkPrice;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PricingCrudService
{
    // Load all products that have at least one base price row (these are mapped).
    public function getMappedProducts(): array
    {
        // Load variants that have a base price, with product and all prices eager loaded.
        $mappedVariantList = ProductVariant::query()
            ->with(['product:id,name,sku,category_id', 'prices'])
            ->whereHas('prices', function ($query) {
                $query->where('price_type', 'base')->where('is_active', true);
            })
            ->where('is_active', true)
            ->get();

        // Build the final display rows.
        $mappedProductList = [];

        foreach ($mappedVariantList as $variant) {
            // Read the base, b2b, and b2c price amounts from the loaded prices.
            $basePrice  = $variant->prices->where('price_type', 'base')->first();
            $b2bPrice   = $variant->prices->where('price_type', 'b2b')->first();
            $b2cPrice   = $variant->prices->where('price_type', 'b2c')->first();

            $mappedProductList[] = [
                'variant_id'   => (int) $variant->id,
                'product_name' => $variant->product?->name ?? 'Unknown Product',
                'sku'          => $variant->product?->sku ?? $variant->sku,
                'base_price'   => $basePrice ? (float) $basePrice->amount : null,
                'b2c_price'    => $b2cPrice  ? (float) $b2cPrice->amount  : null,
                'b2b_price'    => $b2bPrice  ? (float) $b2bPrice->amount  : null,
            ];
        }

        return $mappedProductList;
    }

    // Load all products that have NO base price row (these need pricing mapped).
    public function getUnmappedProducts(): array
    {
        // Load variants with no base price row at all.
        $unmappedVariantList = ProductVariant::query()
            ->with(['product:id,name,sku'])
            ->whereDoesntHave('prices', function ($query) {
                $query->where('price_type', 'base');
            })
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        // Build the final display rows.
        $unmappedProductList = [];

        foreach ($unmappedVariantList as $variant) {
            $unmappedProductList[] = [
                'variant_id'     => (int) $variant->id,
                'product_name'   => $variant->product?->name ?? 'Unknown Product',
                'catalog_number' => $variant->catalog_number ?? $variant->sku,
                'date_added'     => $variant->created_at?->format('Y-m-d') ?? '—',
            ];
        }

        return $unmappedProductList;
    }

    // Load all products for the bulk pricing modal dropdown.
    public function getAllProductsForDropdown(): array
    {
        // Load all active products with their default variant.
        // Note: do not restrict columns on defaultVariant — oldestOfMany uses a self-join
        // and column restriction causes an ambiguous 'product_id' error in MySQL.
        $productList = Product::query()
            ->with(['defaultVariant'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);

        $dropdownList = [];

        foreach ($productList as $product) {
            // Only include products that have a default variant linked.
            if (! $product->defaultVariant) {
                continue;
            }

            $dropdownList[] = [
                'variant_id'   => (int) $product->defaultVariant->id,
                'product_name' => $product->name,
                'sku'          => $product->sku,
            ];
        }

        return $dropdownList;
    }

    // Build the bulk pricing table with dynamic slab columns.
    public function getBulkPricingTableData(): array
    {
        // Load all active bulk price rows with their variant and product.
        $allBulkPriceRows = ProductBulkPrice::query()
            ->with(['variant.product:id,name,sku'])
            ->where('is_active', true)
            ->orderBy('product_variant_id')
            ->orderBy('min_quantity')
            ->get();

        // Collect all distinct min_quantity values — these become the table column headers.
        $slabColumnList = $allBulkPriceRows
            ->pluck('min_quantity')
            ->unique()
            ->sort()
            ->values()
            ->all();

        // Group bulk price rows by variant id.
        $pricesByVariant = $allBulkPriceRows->groupBy('product_variant_id');

        // Build one row per variant with a price for each slab column.
        $tableRowList = [];

        foreach ($pricesByVariant as $variantId => $variantPriceRows) {
            // Read the product name from the first row in this group.
            $firstRow    = $variantPriceRows->first();
            $productName = $firstRow->variant?->product?->name ?? 'Unknown Product';
            $productSku  = $firstRow->variant?->product?->sku ?? '—';

            // Build a map of min_quantity → amount for quick lookup.
            $slabPriceMap = [];

            foreach ($variantPriceRows as $priceRow) {
                $slabPriceMap[(int) $priceRow->min_quantity] = (float) $priceRow->amount;
            }

            // Build the price values for each slab column.
            $pricePerSlab = [];

            foreach ($slabColumnList as $slabQty) {
                $pricePerSlab[$slabQty] = $slabPriceMap[$slabQty] ?? null;
            }

            $tableRowList[] = [
                'variant_id'   => (int) $variantId,
                'product_name' => $productName,
                'sku'          => $productSku,
                'prices'       => $pricePerSlab,
            ];
        }

        return [
            'slab_columns' => $slabColumnList,
            'rows'         => $tableRowList,
        ];
    }

    // Save the bulk pricing slabs for a given product variant.
    public function saveBulkPricingSlabs(int $variantId, array $slabList): void
    {
        // Check that the variant actually exists.
        $variantExists = ProductVariant::query()->where('id', $variantId)->exists();

        if (! $variantExists) {
            throw ValidationException::withMessages([
                'variant_id' => 'Selected product variant was not found.',
            ]);
        }

        // Remove all existing bulk price rows for this variant before saving new ones.
        ProductBulkPrice::query()->where('product_variant_id', $variantId)->delete();

        // Insert each slab row one at a time.
        foreach ($slabList as $slab) {
            $minQty = (int) ($slab['min_quantity'] ?? 0);
            $amount = (float) ($slab['amount'] ?? 0);

            // Skip rows where either quantity or amount is missing.
            if ($minQty <= 0 || $amount <= 0) {
                continue;
            }

            ProductBulkPrice::create([
                'product_variant_id' => $variantId,
                'min_quantity'       => $minQty,
                'amount'             => $amount,
                'currency'           => 'INR',
                'is_active'          => true,
            ]);
        }
    }
}

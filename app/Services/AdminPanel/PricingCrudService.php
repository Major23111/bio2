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
    public function getMappedProducts(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Business logic: Get variants with mapped public price
        $paginator = ProductVariant::query()
            ->with(['product:id,name,sku,category_id', 'prices'])
            ->whereHas('prices', function ($query) {
                $query->where('price_type', 'public')->where('is_active', true);
            })
            ->where('is_active', true)
            ->paginate(10, ['*'], 'mapped_page');

        // Business logic: Map the data for the UI
        $paginator->getCollection()->transform(function ($variant) {
            $publicPrice = $variant->prices->where('price_type', 'public')->first();
            $b2bPrice   = $variant->prices->where('price_type', 'b2b')->first();
            $b2cPrice   = $variant->prices->where('price_type', 'b2c')->first();

            return [
                'variant_id'   => (int) $variant->id,
                'product_name' => $variant->product?->name ?? 'Unknown Product',
                'sku'          => $variant->product?->sku ?? $variant->sku,
                'pack_size'    => $variant->pack_size ?? $variant->variant_name,
                'mrp'          => $variant->mrp ?? ($publicPrice ? (float) $publicPrice->amount : null),
                'b2c_price'    => $b2cPrice  ? (float) $b2cPrice->amount  : null,
                'b2b_price'    => $b2bPrice  ? (float) $b2bPrice->amount  : null,
            ];
        });

        return $paginator;
    }

    // Load all products that have NO base price row (these need pricing mapped).
    public function getUnmappedProducts(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Business logic: Load variants without a public price
        $paginator = ProductVariant::query()
            ->with(['product:id,name,sku'])
            ->whereDoesntHave('prices', function ($query) {
                $query->where('price_type', 'public');
            })
            ->where('is_active', true)
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'unmapped_page');

        // Business logic: Transform data for UI
        $paginator->getCollection()->transform(function ($variant) {
            return [
                'variant_id'     => (int) $variant->id,
                'product_name'   => $variant->product?->name ?? 'Unknown Product',
                'catalog_number' => $variant->catalog_number ?? $variant->sku,
                'pack_size'      => $variant->pack_size ?? $variant->variant_name,
                'date_added'     => $variant->created_at?->format('Y-m-d') ?? '—',
            ];
        });

        return $paginator;
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

        // Remove all existing general bulk price rows (those not linked to a company) before saving new ones.
        ProductBulkPrice::query()
            ->where('product_variant_id', $variantId)
            ->whereNull('applies_to_user_type')
            ->delete();

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
                'max_quantity'       => $slab['max_quantity'] ?? null,
                'amount'             => $amount,
                'currency'           => 'INR',
                'is_active'          => true,
            ]);
        }
    }

    // Load company specific pricing list for the index view.
    public function getCompanyPricingList(): array
    {
        $companyPrices = ProductPrice::query()
            ->with(['company:id,name,company_type'])
            ->whereNotNull('company_id')
            ->where('is_active', true)
            ->get();

        $groupedByCompany = $companyPrices->groupBy('company_id');

        $companyList = [];

        foreach ($groupedByCompany as $companyId => $prices) {
            $company = $prices->first()->company;
            if (!$company) {
                continue;
            }

            // Determine if there are private bulk slabs for this company
            $hasPrivateSlabs = ProductBulkPrice::query()
                ->where('applies_to_user_type', 'company_' . $companyId)
                ->exists();

            // Find the most generous discount or specific base price override to summarize
            $preferredRateText = 'Custom Rates Applied';
            $firstOverride = $prices->first();
            if ($firstOverride->Discount > 0) {
                if ($firstOverride->DiscountType === 'percent') {
                    $preferredRateText = "-{$firstOverride->Discount}% Base";
                } else {
                    $preferredRateText = "-₹" . number_format($firstOverride->Discount, 2) . " Base";
                }
            } else {
                $preferredRateText = "₹" . number_format($firstOverride->amount, 2) . " Fixed Rate";
            }

            $companyList[] = [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'short_name' => strtoupper(substr($company->name, 0, 2)),
                'type' => strtoupper($company->company_type ?? 'PARTNER'),
                'preferred_rate' => $preferredRateText,
                'has_private_slabs' => $hasPrivateSlabs,
                'last_sync' => $prices->first()->updated_at?->diffForHumans() ?? 'Unknown',
            ];
        }

        return $companyList;
    }

    public function saveMappedPricing(int $variantId, float $mrp, float $b2cPercent, float $b2bPrice, float $discountPercent, string $applyDiscountTo): void
    {
        // Business logic: Check if variant exists
        $variant = ProductVariant::find($variantId);
        if (!$variant) {
            throw ValidationException::withMessages(['variant_id' => 'Variant not found.']);
        }

        // Business logic: Save MRP to variant
        $variant->mrp = $mrp;
        $variant->save();

        // Business logic: Calculate B2C price based on B2B price and margin
        $b2cPrice = $b2bPrice + ($b2bPrice * ($b2cPercent / 100));

        // Business logic: Assign discounts
        $b2cDiscount = ($applyDiscountTo === 'B2C' || $applyDiscountTo === 'Both B2C and B2B') ? $discountPercent : 0;
        $b2bDiscount = ($applyDiscountTo === 'B2B' || $applyDiscountTo === 'Both B2C and B2B') ? $discountPercent : 0;

        // Business logic: Save public price
        ProductPrice::updateOrCreate(
            ['product_variant_id' => $variantId, 'price_type' => 'public', 'company_id' => null],
            ['amount' => $mrp, 'is_active' => true, 'currency' => 'INR']
        );

        // Business logic: Save B2C Price
        ProductPrice::updateOrCreate(
            ['product_variant_id' => $variantId, 'price_type' => 'b2c', 'company_id' => null],
            ['amount' => $b2cPrice, 'DiscountType' => 'percent', 'Discount' => $b2cDiscount, 'is_active' => true, 'currency' => 'INR']
        );

        // Business logic: Save B2B Price
        ProductPrice::updateOrCreate(
            ['product_variant_id' => $variantId, 'price_type' => 'b2b', 'company_id' => null],
            ['amount' => $b2bPrice, 'DiscountType' => 'percent', 'Discount' => $b2bDiscount, 'is_active' => true, 'currency' => 'INR']
        );
    }

    public function updatePricing(int $variantId, float $mrp, float $b2cPercent, float $b2bPrice, float $discountPercent, string $applyDiscountTo): void
    {
        // Business logic: Editing uses same logic as creating
        $this->saveMappedPricing($variantId, $mrp, $b2cPercent, $b2bPrice, $discountPercent, $applyDiscountTo);
    }

    public function saveCompanyPricing(int $companyId, string $productSelection, ?int $variantId, float $specificB2bPrice, float $exclusiveDiscount, array $bulkSlabs): void
    {
        // For simplicity, we assume single product selection if variantId is provided, else apply to all mapped variants
        $variantsToApply = collect();
        if ($productSelection === 'Single Product' && $variantId) {
            $variantsToApply->push(ProductVariant::find($variantId));
        } elseif ($productSelection === 'Apply to All') {
            // Business logic: Load ALL variants that have a B2B price mapped, not just the first page.
            $mappedVariantIds = ProductPrice::query()
                ->where('price_type', 'b2b')
                ->where('is_active', true)
                ->pluck('product_variant_id');
            
            $variantsToApply = ProductVariant::whereIn('id', $mappedVariantIds)->get();
        }

        foreach ($variantsToApply->filter() as $variant) {
            // Business logic: Set company specific price (defaults to company_price instead of b2b)
            ProductPrice::updateOrCreate(
                ['product_variant_id' => $variant->id, 'price_type' => 'company_price', 'company_id' => $companyId],
                ['amount' => $specificB2bPrice, 'DiscountType' => 'percent', 'Discount' => $exclusiveDiscount, 'is_active' => true, 'currency' => 'INR']
            );

            // Optional: Save custom bulk pricing for this company
            if (!empty($bulkSlabs)) {
                // Remove old bulk prices for this company/variant
                ProductBulkPrice::where('product_variant_id', $variant->id)
                    ->where('applies_to_user_type', 'company_' . $companyId)
                    ->delete();

                foreach ($bulkSlabs as $slab) {
                    if (empty($slab['min_quantity']) || empty($slab['amount'])) continue;
                    ProductBulkPrice::create([
                        'product_variant_id' => $variant->id,
                        'applies_to_user_type' => 'company_' . $companyId,
                        'min_quantity' => $slab['min_quantity'],
                        'max_quantity' => $slab['max_quantity'] ?? null,
                        'amount' => $slab['amount'],
                        'currency' => 'INR',
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}

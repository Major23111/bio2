<?php
namespace App\Services\Product;

use App\Models\Authorization\User;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Pricing\PriceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductCatalogService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected PriceService $priceService,
    ) {
    }

    // Get all catalog products with filters, search, sorting and pagination applied.
    public function getProductListToBeDisplayed(?User $user, array $filters = []): array
    {
        // Prepare filter values into normalized structure.
        $catalogFilters = $this->prepareProductListFilters($filters);

        // Load all visible products for the logged in user or guest.
        $visibleProducts = $this->dataVisibilityService->visibleProductQuery($user)
            ->orderBy('products.name')
            ->get();

        // Batch-load all product prices at once for performance.
        $productIds = $visibleProducts->pluck('id')->all();
        $allPrices = $this->batchLoadProductPrices($productIds, $user);

        // Attach prices to each product from the cached batch.
        $visibleProducts = $visibleProducts
            ->map(fn ($product) => $this->attachPriceFromCache($product, $allPrices))
            ->values();

        // Filter products by free-text search.
        $searchScopedProducts = $this->getProductsToBeDisplayedBySearch($visibleProducts, $catalogFilters['search']);

        // Build available filter options (categories, brands, price range) from search results.
        $catalogOptions = $this->getAvailableProductFilters($searchScopedProducts);

        // Apply all selected sidebar filters.
        $filteredProducts = $searchScopedProducts
            ->filter(fn ($product): bool => $this->getProductsAfterApplyingFilters($product, $catalogFilters))
            ->values();

        // Sort products by user selected option (name, price, relevance).
        $sortedProducts = $this->sortProducts($filteredProducts, $catalogFilters['sort'])->values();

        // Paginate the sorted products (15 items per page).
        $paginatedProducts = $this->paginateProducts($sortedProducts, 15);

        // Load all variants for products on current page (single query, no N+1).
        $productsOnPage = $paginatedProducts->getCollection();
        $productIdsOnPage = $productsOnPage->pluck('id')->all();
        $allVariantsByProduct = $this->loadAllVariantsForProducts($productIdsOnPage);

        // Attach variants to each product.
        $productsOnPage = $productsOnPage
            ->map(fn ($product) => $this->attachVariantsToProduct($product, $allVariantsByProduct))
            ->values();

        // Update paginator collection with products that now have variants.
        $paginatedProducts->setCollection($productsOnPage);

        // Attach bulk pricing summary to each paginated product card.
        $paginatedProducts->setCollection(
            $paginatedProducts->getCollection()
                ->map(fn ($product) => $this->attachCatalogCardCommercialData($product, $user))
                ->values()
        );

        return [
            'products' => $paginatedProducts,
            'catalogOptions' => $catalogOptions,
        ];
    }

    // Load all product prices in one batch instead of individual queries.
    private function batchLoadProductPrices(array $productIds, ?User $user): array
    {
        if (empty($productIds)) {
            return [];
        }

        $visiblePriceTypes = $this->priceService->visiblePriceTypes($user);
        $genericPriceTypes = array_values(array_filter($visiblePriceTypes, fn (string $type): bool => $type !== 'company_price'));
        $priceTypePriority = array_flip($visiblePriceTypes);

        $variantRows = ProductVariant::query()
            ->with(['prices' => function ($builder) use ($genericPriceTypes, $user): void {
                $builder->where('product_prices.is_active', true)
                    ->where(function ($priceQuery) use ($genericPriceTypes, $user): void {
                        if ($genericPriceTypes !== []) {
                            $priceQuery->where(function ($genericQuery) use ($genericPriceTypes): void {
                                $genericQuery
                                    ->whereNull('product_prices.company_id')
                                    ->whereIn('product_prices.price_type', $genericPriceTypes);
                            });
                        }

                        if ($user && $user->isB2b() && $user->company_id) {
                            $priceQuery->orWhere(function ($companyQuery) use ($user): void {
                                $companyQuery
                                    ->where('product_prices.price_type', 'company_price')
                                    ->where('product_prices.company_id', $user->company_id);
                            });
                        }
                    });
            }])
            ->whereIn('product_id', $productIds)
            ->where('is_active', true)
            ->orderBy('product_id')
            ->orderBy('id')
            ->get();

        $resolvedPricesByProductId = [];

        foreach ($variantRows as $variant) {
            $productId = (int) $variant->product_id;

            if (isset($resolvedPricesByProductId[$productId])) {
                continue;
            }

            $selectedPrice = $variant->prices
                ->sortBy(fn ($price): int => $priceTypePriority[$price->price_type] ?? 999)
                ->first();

            if (! $selectedPrice) {
                continue;
            }

            $resolvedPricesByProductId[$productId] = $this->buildCatalogResolvedPrice($variant, $selectedPrice, $user);
        }

        return $resolvedPricesByProductId;
    }

    // Build the same lightweight price payload the catalog cards need, without issuing one query per product.
    private function buildCatalogResolvedPrice(ProductVariant $variant, object $selectedPrice, ?User $user): array
    {
        $baseAmount = round((float) $selectedPrice->amount, 2);
        $discountType = strtolower(trim((string) ($selectedPrice->DiscountType ?? 'cash')));
        $discountValue = round(max(0, (float) ($selectedPrice->Discount ?? 0)), 2);

        if (! in_array($discountType, ['cash', 'percent'], true)) {
            $discountType = 'cash';
        }

        $discountAmount = $discountType === 'percent'
            ? round(($baseAmount * min($discountValue, 100)) / 100, 2)
            : min($discountValue, $baseAmount);

        $amount = round($baseAmount - $discountAmount, 2);
        $gstRate = round((float) ($selectedPrice->gst_rate ?? 0), 2);
        $taxAmount = round(($amount * $gstRate) / 100, 2);

        $minimumQuantity = $user && $user->isB2b()
            ? ($variant->b2b_min_order_quantity ?? $variant->min_order_quantity ?? 1)
            : ($variant->b2c_min_order_quantity ?? $variant->min_order_quantity ?? 1);

        $maximumQuantity = $user && $user->isB2b()
            ? ($variant->b2b_max_order_quantity ?? $variant->max_order_quantity)
            : ($variant->b2c_max_order_quantity ?? $variant->max_order_quantity);

        return [
            'base_amount' => $baseAmount,
            'amount' => $amount,
            'gst_rate' => $gstRate,
            'tax_amount' => $taxAmount,
            'price_after_gst' => round($amount + $taxAmount, 2),
            'currency' => (string) ($selectedPrice->currency ?? 'INR'),
            'discount_amount' => $discountAmount,
            'price_type' => (string) $selectedPrice->price_type,
            'product_variant_id' => (int) $variant->id,
            'variant_sku' => (string) $variant->sku,
            'variant_name' => (string) $variant->variant_name,
            'min_order_quantity' => max(1, (int) $minimumQuantity),
            'max_order_quantity' => $maximumQuantity === null ? null : (int) $maximumQuantity,
            'lot_size' => max(1, (int) ($variant->lot_size ?? 1)),
        ];
    }

    // Attach price details from pre-loaded cache to product.
    private function attachPriceFromCache(object $product, array $cachedPrices): object
    {
        $productId = (int) $product->id;
        $price = $cachedPrices[$productId] ?? [];

        // Attach base and final prices.
        $product->visible_base_price = $price['base_amount'] ?? null;
        $product->visible_price = $price['amount'] ?? null;
        $product->visible_discount_amount = $price['discount_amount'] ?? 0;

        // Attach tax details.
        $product->gst_rate = $price['gst_rate'] ?? 0;
        $product->tax_amount = $price['tax_amount'] ?? null;
        $product->price_with_gst = $price['price_after_gst'] ?? null;
        $product->visible_currency = $price['currency'] ?? null;

        // Attach variant information.
        $product->visible_price_type = $price['price_type'] ?? null;
        $product->visible_variant_id = $price['product_variant_id'] ?? null;
        $product->visible_variant_sku = $price['variant_sku'] ?? null;
        $product->visible_variant_name = $price['variant_name'] ?? null;

        // Attach order quantity limits.
        $product->visible_min_order_quantity = $price['min_order_quantity'] ?? 1;
        $product->visible_max_order_quantity = $price['max_order_quantity'] ?? null;
        $product->visible_lot_size = $price['lot_size'] ?? 1;

        return $product;
    }

    // Normalize all filter values into consistent structure.
    private function prepareProductListFilters(array $filters): array
    {
        // Get search text and trim whitespace.
        $search = trim((string) ($filters['search'] ?? $filters['search_text'] ?? $filters['search_value'] ?? ''));

        // Normalize category filter values (remove empty, duplicates).
        $categoryRawValues = is_array($filters['category_name'] ?? $filters['category'] ?? $filters['category_id'] ?? null)
            ? ($filters['category_name'] ?? $filters['category'] ?? $filters['category_id'] ?? [])
            : [$filters['category_name'] ?? $filters['category'] ?? $filters['category_id'] ?? null];
        $selectedCategories = collect($categoryRawValues)
            ->map(fn ($item): string => trim((string) $item))
            ->filter()
            ->unique(fn (string $item): string => Str::lower($item))
            ->values()
            ->all();

        // Normalize subcategory filter values.
        $appRawValues = is_array($filters['application_name'] ?? $filters['subcategory_name'] ?? $filters['subcategory'] ?? $filters['subcategory_id'] ?? null)
            ? ($filters['application_name'] ?? $filters['subcategory_name'] ?? $filters['subcategory'] ?? $filters['subcategory_id'] ?? [])
            : [$filters['application_name'] ?? $filters['subcategory_name'] ?? $filters['subcategory'] ?? $filters['subcategory_id'] ?? null];
        $selectedApplications = collect($appRawValues)
            ->map(fn ($item): string => trim((string) $item))
            ->filter()
            ->unique(fn (string $item): string => Str::lower($item))
            ->values()
            ->all();

        // Normalize brand filter values.
        $brandRawValues = $filters['brand_name'] ?? [];
        $brandRawValues = is_array($brandRawValues) ? $brandRawValues : [$brandRawValues];
        $selectedBrands = collect($brandRawValues)
            ->map(fn ($item): string => trim((string) $item))
            ->filter()
            ->unique(fn (string $item): string => Str::lower($item))
            ->values()
            ->all();

        // Keep only valid max-price values.
        $selectedMaxPrice = is_numeric($filters['max_price'] ?? null) ? (float) $filters['max_price'] : null;
        $sort = trim((string) ($filters['sort'] ?? 'relevant'));

        // Create lookup maps for fast filter matching.
        $categoryLookup = collect($selectedCategories)
            ->mapWithKeys(fn (string $value): array => [Str::lower(trim($value)) => true])
            ->all();

        $applicationLookup = collect($selectedApplications)
            ->mapWithKeys(fn (string $value): array => [Str::lower(trim($value)) => true])
            ->all();

        $brandLookup = collect($selectedBrands)
            ->mapWithKeys(fn (string $value): array => [Str::lower(trim($value)) => true])
            ->all();

        return [
            'search' => $search,
            'sort' => $sort,
            'maxPrice' => $selectedMaxPrice,
            'categoryLookup' => $categoryLookup,
            'applicationLookup' => $applicationLookup,
            'brandLookup' => $brandLookup,
        ];
    }

    // Filter products by search text.
    private function getProductsToBeDisplayedBySearch(Collection $products, string $search): Collection
    {
        $searchText = Str::lower(trim($search));

        if ($searchText === '') {
            return $products->values();
        }

        $matchedProducts = $products
            ->filter(fn ($product): bool => $this->isProductMatchingSearchText($product, $searchText))
            ->values();

        return $matchedProducts;
    }

    // Check if product contains search text in searchable fields.
    private function isProductMatchingSearchText(object $product, string $searchText): bool
    {
        // List of fields to search in.
        $searchableFields = [
            $product->name ?? null,
            $product->sku ?? null,
            $product->description ?? null,
            $product->brand ?? null,
            $product->category_name ?? null,
            $product->subcategory_name ?? null,
            $product->visible_variant_sku ?? null,
        ];

        // Check each field for the search text.
        foreach ($searchableFields as $field) {
            $normalizedField = Str::lower(trim((string) $field));

            if ($normalizedField !== '' && Str::contains($normalizedField, $searchText)) {
                return true;
            }
        }

        return false;
    }

    // Get all available filter options (categories, brands, price range).
    private function getAvailableProductFilters(Collection $products): array
    {
        // Get min and max product prices.
        [$minPrice, $maxPrice] = $this->getProductPriceRange($products);

        return [
            'categoryOptions' => $this->getFilterWiseProductCount($products, 'category_name'),
            'applicationOptions' => $this->getFilterWiseProductCount($products, 'subcategory_name'),
            'brandOptions' => $this->getFilterWiseProductCount($products, 'brand'),
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ];
    }

    // Count products for each filter value (for sidebar).
    private function getFilterWiseProductCount(Collection $products, string $field): Collection
    {
        $counts = $products
            ->map(fn ($product): string => trim((string) data_get($product, $field)))
            ->filter()
            ->countBy()
            ->sortKeys();

        return $counts;
    }

    // Calculate min and max prices from product collection.
    private function getProductPriceRange(Collection $products): array
    {
        // Get all prices and filter out empty values.
        $prices = $products
            ->pluck('visible_price')
            ->filter(fn ($price) => $price !== null)
            ->map(fn ($price): float => (float) $price)
            ->values();

        // Return default range if no prices exist.
        if ($prices->isEmpty()) {
            return [0, 1000];
        }

        // Return min and max prices.
        $minPrice = (int) floor($prices->min());
        $maxPrice = (int) ceil($prices->max());

        return [$minPrice, $maxPrice];
    }

    // Check if product passes all selected filters.
    private function getProductsAfterApplyingFilters(object $product, array $catalogFilters): bool
    {
        // Check if product matches filter criteria.
        $matchesFilter = function (array $candidates, array $selectedLookup): bool {
            if ($selectedLookup === []) {
                return true;
            }

            // Check each candidate against filter lookup.
            foreach ($candidates as $candidate) {
                $normalized = Str::lower(trim((string) $candidate));

                if ($normalized !== '' && isset($selectedLookup[$normalized])) {
                    return true;
                }
            }

            return false;
        };

        // Check category filter.
        $categoryMatches = $matchesFilter([
            $product->category_name ?? null,
            $product->category_slug ?? null,
            $product->category_id ?? null,
        ], $catalogFilters['categoryLookup']);

        if (!$categoryMatches) {
            return false;
        }

        // Check subcategory filter.
        $subcategoryMatches = $matchesFilter([
            $product->subcategory_name ?? null,
            $product->subcategory_slug ?? null,
            $product->subcategory_id ?? null,
        ], $catalogFilters['applicationLookup']);

        if (!$subcategoryMatches) {
            return false;
        }

        // Check brand filter.
        $brandMatches = $matchesFilter([
            $product->brand ?? null,
        ], $catalogFilters['brandLookup']);

        if (!$brandMatches) {
            return false;
        }

        // Check price filter.
        if ($catalogFilters['maxPrice'] !== null) {
            $price = $product->visible_price;

            if ($price === null || (float) $price > $catalogFilters['maxPrice']) {
                return false;
            }
        }

        return true;
    }

    // Sort products by selected sorting option.
    private function sortProducts(Collection $products, string $sort): Collection
    {
        if ($sort === 'name_az') {
            return $products->sortBy(fn ($product) => Str::lower((string) ($product->name ?? '')), SORT_NATURAL);
        }

        if ($sort === 'price_low') {
            return $products->sortBy(fn ($product) => $product->visible_price ?? PHP_FLOAT_MAX);
        }

        if ($sort === 'price_high') {
            return $products->sortByDesc(fn ($product) => $product->visible_price ?? -1);
        }

        // Default: sort by name ascending.
        return $products->sortBy(fn ($product) => Str::lower((string) ($product->name ?? '')), SORT_NATURAL);
    }

    // Split collection into pages with given items per page.
    private function paginateProducts(Collection $products, int $perPage): LengthAwarePaginator
    {
        // Get current page from request.
        $currentPage = max(1, (int) LengthAwarePaginator::resolveCurrentPage('page'));

        // Get items for current page.
        $items = $products->forPage($currentPage, $perPage)->values();

        // Create paginator.
        $paginator = new LengthAwarePaginator(
            $items,
            $products->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ],
        );

        // Append query parameters to pagination links.
        $paginator->appends(request()->query());

        return $paginator;
    }

    // Attach bulk pricing summary for catalog product card.
    private function attachCatalogCardCommercialData(object $product, ?User $user): object
    {
        // Keep listing fast and resilient; detailed tier pricing is loaded on the product detail page.
        $product->catalog_bulk_summary = null;

        return $product;
    }

    // Load all active variants for given products in a single efficient query.
    private function loadAllVariantsForProducts(array $productIds): array
    {
        // Return empty if no product IDs provided.
        if (empty($productIds)) {
            return [];
        }

        // Fetch all active variants for the products (single query, no N+1).
        $variants = ProductVariant::query()
            ->whereIn('product_id', $productIds)
            ->where('is_active', true)
            ->orderBy('product_id')
            ->orderBy('pack_size')
            ->get();

        // Group variants by product ID for easy lookup.
        $variantsByProduct = [];
        foreach ($variants as $variant) {
            $productId = (int) $variant->product_id;
            if (!isset($variantsByProduct[$productId])) {
                $variantsByProduct[$productId] = [];
            }
            $variantsByProduct[$productId][] = $variant;
        }

        return $variantsByProduct;
    }

    // Attach all available variants to a product.
    private function attachVariantsToProduct(object $product, array $allVariants): object
    {
        // Get variants for this product or empty collection.
        $productId = (int) $product->id;
        $productVariants = $allVariants[$productId] ?? [];

        // Attach variants collection to product.
        $product->all_variants = collect($productVariants);

        return $product;
    }
}

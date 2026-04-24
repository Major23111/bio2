<?php
namespace App\Services\Product;

use App\Models\Authorization\User;
use App\Models\Order\OrderItem;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\UserActivityLog;
use App\Services\Pricing\PriceService;
use Illuminate\Support\Collection;

class ProductUtilityService
{
    public function __construct(
        protected PriceService $priceService,
    ) {
    }

    // Get all product categories sorted by order and name.
    public function categories(): Collection
    {
        $allCategories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->values();

        return $allCategories;
    }

    // Get home page categories in configured order.
    public function getConfiguredCategories(): Collection
    {
        // Get configured category slugs from application config.
        $configuredSlugs = collect(config('common.home_page_category_slugs', []))
            ->filter(fn (mixed $slug): bool => is_string($slug) && trim($slug) !== '')
            ->map(fn (string $slug): string => trim($slug))
            ->unique()
            ->values();

        // If no configured slugs, get all categories marked for home page display.
        if ($configuredSlugs->isEmpty()) {
            $categories = Category::query()
                ->where('IsDisplayedOnHomePage', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->values();

            return $categories;
        }

        // Load categories for configured slugs.
        $categoriesForSlugs = Category::query()
            ->where('IsDisplayedOnHomePage', true)
            ->whereIn('slug', $configuredSlugs->all())
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->keyBy('slug');

        // Return categories in configured order.
        $orderedCategories = $configuredSlugs
            ->map(fn (string $slug) => $categoriesForSlugs->get($slug))
            ->filter()
            ->values();

        return $orderedCategories;
    }

    // Get top products frequently bought together with a given product.
    public function frequentlyBoughtTogetherProducts(int $productId, ?User $user): Collection
    {
        // Get limit from config (minimum 1).
        $limit = max(1, (int) config('common.frequently_bought_together_limit', 4));

        // Load current product.
        $currentProduct = Product::query()
            ->select(['id', 'category_id', 'subcategory_id'])
            ->find($productId);

        // Return empty if current product doesn't exist.
        if (!$currentProduct) {
            return collect();
        }

        // Start collecting related product ids.
        $selectedProductIds = collect();

        // Get products frequently bought with current product.
        $topProductFrequencyMap = OrderItem::query()
            ->selectRaw('order_items.product_id, COUNT(*) as frequency_count')
            ->whereIn('order_items.order_id', function ($builder) use ($productId): void {
                // Find all orders containing current product.
                $builder->select('order_items.order_id')
                    ->from('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->where('order_items.product_id', $productId)
                    ->whereIn('orders.status', ['submitted', 'approved']);
            })
            ->whereNotNull('order_items.product_id')
            ->where('order_items.product_id', '!=', $productId)
            ->groupBy('order_items.product_id')
            ->orderByDesc('frequency_count')
            ->limit($limit)
            ->pluck('frequency_count', 'product_id');

        // Add top frequency products to selected list.
        $frequencyProductIds = $topProductFrequencyMap
            ->keys()
            ->map(fn ($relatedProductId) => (int) $relatedProductId);

        $selectedProductIds = $selectedProductIds->concat($frequencyProductIds);

        // Fill remaining slots from same subcategory if needed.
        if ($selectedProductIds->count() < $limit && $currentProduct->subcategory_id) {
            $remainingCount = $limit - $selectedProductIds->count();

            $sameSubcategoryProductIds = Product::query()
                ->where('subcategory_id', $currentProduct->subcategory_id)
                ->where('id', '!=', $productId)
                ->whereNotIn('id', $selectedProductIds->all())
                ->where('is_active', true)
                ->orderBy('name')
                ->limit($remainingCount)
                ->pluck('id')
                ->map(fn ($relatedProductId) => (int) $relatedProductId);

            $selectedProductIds = $selectedProductIds->concat($sameSubcategoryProductIds);
        }

        // Fill remaining slots from same category if needed.
        if ($selectedProductIds->count() < $limit && $currentProduct->category_id) {
            $remainingCount = $limit - $selectedProductIds->count();

            $sameCategoryProductIds = Product::query()
                ->where('category_id', $currentProduct->category_id)
                ->where('id', '!=', $productId)
                ->whereNotIn('id', $selectedProductIds->all())
                ->where('is_active', true)
                ->orderBy('name')
                ->limit($remainingCount)
                ->pluck('id')
                ->map(fn ($relatedProductId) => (int) $relatedProductId);

            $selectedProductIds = $selectedProductIds->concat($sameCategoryProductIds);
        }

        // Get unique product ids up to limit.
        $topProductIds = $selectedProductIds
            ->unique()
            ->take($limit)
            ->values();

        // Return empty if no related products.
        if ($topProductIds->isEmpty()) {
            return collect();
        }

        // Load selected products with relationships.
        $products = Product::query()
            ->with([
                'category:id,name',
                'subcategory:id,name',
                'primaryImage:id,file_path',
            ])
            ->whereIn('id', $topProductIds->all())
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        // Build final list in ranked order with pricing.
        $relatedProducts = $topProductIds
            ->map(function (int $relatedProductId) use ($products, $topProductFrequencyMap, $user) {
                // Get product by id.
                $product = $products->get($relatedProductId);

                if (!$product) {
                    return null;
                }

                // Get product price.
                $price = $this->priceService->resolveProductPrice($relatedProductId, $user);

                if (!$price) {
                    return null;
                }

                // Attach frequency and price information.
                $product->frequency_count = (int) ($topProductFrequencyMap[$relatedProductId] ?? 0);
                $product->visible_price = $price['amount'] ?? null;
                $product->gst_rate = $price['gst_rate'] ?? 0;
                $product->tax_amount = $price['tax_amount'] ?? null;
                $product->price_with_gst = $price['price_after_gst'] ?? null;
                $product->visible_currency = $price['currency'] ?? null;
                $product->visible_price_type = $price['price_type'] ?? null;
                $product->visible_variant_id = $price['product_variant_id'] ?? null;
                $product->visible_variant_sku = $price['variant_sku'] ?? null;
                $product->visible_variant_name = $price['variant_name'] ?? null;
                $product->visible_min_order_quantity = $price['min_order_quantity'] ?? 1;
                $product->visible_max_order_quantity = $price['max_order_quantity'] ?? null;
                $product->visible_lot_size = $price['lot_size'] ?? 1;

                return $product;
            })
            ->filter()
            ->values();

        return $relatedProducts;
    }

    // Log user activity (view, search, etc) for both guests and logged-in users.
    public function logUserActivity(?User $user, string $sessionId, string $path, string $activityType, array $payload = []): void
    {
        // Create activity log record.
        UserActivityLog::query()->create([
            'session_id' => $sessionId,
            'user_id' => $user?->id,
            'user_type' => $user?->user_type ?: 'guest',
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'activity_type' => $activityType,
            'path' => $path,
            'payload' => $payload,
            'created_at' => now(),
        ]);
    }
}

<?php
namespace App\Services\Product;

use App\Models\Authorization\User;
use App\Models\Product\ProductTechnicalResource;
use App\Models\Product\ProductVariant;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Pricing\PriceService;
use App\Services\Utility\FileHandlingService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductDetailService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected PriceService $priceService,
        protected FileHandlingService $fileHandlingService,
    ) {
    }

    // Get single product detail with all related information for detail page.
    public function getAccessibleProductByProductId(?User $user, int $productId): ?object
    {
        // Load the product if it's visible to the current user.
        $product = $this->dataVisibilityService->visibleProductQuery($user)
            ->where('products.id', $productId)
            ->first();

        // Return nothing if product is not visible.
        if (!$product) {
            return null;
        }

        // Attach resolved price information.
        $product = $this->attachResolvedPriceData($product, $user);

        // Attach variant technical specifications.
        $product = $this->attachVisibleVariantTechnicalSpecifications($product);

        // Attach downloadable technical files.
        $product = $this->attachActiveTechnicalResources($product);

        // Attach bulk pricing tiers.
        $product = $this->attachVisibleVariantBulkPriceTiers($product, $user);

        return $product;
    }

    // Download one technical file after checking product visibility.
    public function downloadTechnicalResourceForViewer(?User $user, int $productId, int $resourceId): BinaryFileResponse
    {
        // Load the product with visibility check.
        $product = $this->getAccessibleProductByProductId($user, $productId);

        if (!$product) {
            throw new NotFoundHttpException('Technical resource not found.');
        }

        // Find the requested file in the product's resource list.
        $technicalResource = collect($product->technical_resources ?? [])
            ->first(fn ($resource): bool => (int) ($resource->id ?? 0) === $resourceId);

        if (!$technicalResource) {
            throw new NotFoundHttpException('Technical resource not found.');
        }

        // Get the stored file path.
        $storedFilePath = trim((string) ($technicalResource->stored_file_path ?? ''));

        // Check if file exists on disk.
        if ($storedFilePath === '' || !$this->fileHandlingService->fileExists($storedFilePath)) {
            throw new NotFoundHttpException('Technical resource file is not available.');
        }

        // Return the file for download.
        $fileName = (string) ($technicalResource->original_file_name ?? basename($storedFilePath));
        $downloadFile = $this->fileHandlingService->downloadPublicFile($storedFilePath, $fileName);

        return $downloadFile;
    }

    // Attach all pricing information for the product detail page.
    private function attachResolvedPriceData(object $product, ?User $user): object
    {
        // Get resolved price from pricing service.
        $price = $this->priceService->resolveProductPrice((int) $product->id, $user);

        // Attach base and final prices.
        $product->visible_base_price = $price['base_amount'] ?? null;
        $product->visible_price = $price['amount'] ?? null;
        $product->visible_discount_amount = $price['discount_amount'] ?? 0;

        // Attach tax information.
        $product->gst_rate = $price['gst_rate'] ?? 0;
        $product->tax_amount = $price['tax_amount'] ?? null;
        $product->price_with_gst = $price['price_after_gst'] ?? null;
        $product->visible_currency = $price['currency'] ?? null;

        // Attach pricing type and variant details.
        $product->visible_price_type = $price['price_type'] ?? null;
        $product->visible_variant_id = $price['product_variant_id'] ?? null;
        $product->visible_variant_sku = $price['variant_sku'] ?? null;
        $product->visible_variant_name = $price['variant_name'] ?? null;

        // Attach order quantity constraints.
        $product->visible_min_order_quantity = $price['min_order_quantity'] ?? 1;
        $product->visible_max_order_quantity = $price['max_order_quantity'] ?? null;
        $product->visible_lot_size = $price['lot_size'] ?? 1;

        return $product;
    }

    // Attach technical specifications for the visible variant.
    private function attachVisibleVariantTechnicalSpecifications(object $product): object
    {
        // Default to empty specs list.
        $product->technical_specification_json = [];

        // Skip if no visible variant exists.
        if (!filled($product->visible_variant_id ?? null)) {
            return $product;
        }

        // Load the visible variant.
        $visibleVariant = ProductVariant::query()
            ->select(['id', 'technical_specification_json'])
            ->find((int) $product->visible_variant_id);

        // Attach specs from variant.
        $product->technical_specification_json = $visibleVariant?->technical_specification_json ?? [];

        return $product;
    }

    // Attach downloadable technical files to the product.
    private function attachActiveTechnicalResources(object $product): object
    {
        // Default to empty resource list.
        $product->technical_resources = collect();

        // Get visible variant id if exists.
        $visibleVariantId = filled($product->visible_variant_id ?? null)
            ? (int) $product->visible_variant_id
            : null;

        // Load active technical resources for product or variant.
        $resources = ProductTechnicalResource::query()
            ->select([
                'id',
                'product_id',
                'product_variant_id',
                'title',
                'resource_type',
                'description',
                'stored_file_path',
                'original_file_name',
                'mime_type',
                'file_size',
                'sort_order',
            ])
            ->where('product_id', (int) $product->id)
            ->where('is_active', true)
            ->where(function ($builder) use ($visibleVariantId): void {
                // Always include product-level files.
                $builder->whereNull('product_variant_id');

                // Include variant-level files only for visible variant.
                if ($visibleVariantId !== null) {
                    $builder->orWhere('product_variant_id', $visibleVariantId);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->values();

        // Attach resources list to product.
        $product->technical_resources = $resources;

        return $product;
    }

    // Attach bulk pricing tiers for the visible variant.
    private function attachVisibleVariantBulkPriceTiers(object $product, ?User $user): object
    {
        // Default to empty tiers list.
        $product->bulk_price_tiers = collect();

        // Skip if no visible variant exists.
        if (!filled($product->visible_variant_id ?? null)) {
            return $product;
        }

        // Get bulk pricing tiers from pricing service.
        $bulkTiers = $this->priceService->listBulkPriceTiers((int) $product->visible_variant_id, $user);

        // Attach tiers to product.
        $product->bulk_price_tiers = $bulkTiers;

        return $product;
    }
}

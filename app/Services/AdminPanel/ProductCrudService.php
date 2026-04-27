<?php

namespace App\Services\AdminPanel;

use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductTechnicalResource;
use App\Services\Utility\FileHandlingService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductCrudService
{
    public function __construct(protected FileHandlingService $fileService)
    {
    }

    // This fetches a paginated list of products with their category and price details.
    public function getAllProductsForAdminList(int $perPage = 10): LengthAwarePaginator
    {
        // Step 1: fetch products with eager loading to avoid N+1 issues in the list view.
        $paginatedProducts = Product::with(['category', 'variants', 'defaultVariant.prices'])
            ->orderBy('name')
            ->paginate($perPage);

        // Step 2: transform the collection items into a format the UI expects for display.
        $paginatedProducts->getCollection()->transform(function ($product) {
            $totalStock = $this->calculateProductTotalStock($product);
            $productPrice = $this->getProductDefaultPrice($product);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'categoryName' => $product->category?->name ?? 'Uncategorized',
                'price' => $productPrice,
                'stock' => $totalStock,
                'status' => $this->determineStockStatus($totalStock),
            ];
        });

        return $paginatedProducts;
    }

    // Step-by-step product creation including master, multiple variants, and media assets.
    public function createProduct(array $productData): int
    {
        return DB::transaction(function () use ($productData) {
            // Step 1: generate a URL-friendly slug from the product name.
            $baseSlug = Str::slug($productData['name']);

            // Step 2: make the slug unique by appending the SKU if the base slug already exists.
            $slugExists = DB::table('products')->where('slug', $baseSlug)->exists();
            $finalSlug = $slugExists ? $baseSlug . '-' . Str::slug($productData['main_sku'] ?? time()) : $baseSlug;

            // Step 3: create basic product master record.
            $newProduct = Product::create([
                'name'             => $productData['name'],
                'slug'             => $finalSlug,
                'sku'              => $productData['main_sku'] ?? null,
                'category_id'      => $productData['category_id'],
                'brand'            => $productData['brand'] ?? 'Biogenix',
                'description'      => $productData['description'] ?? null,
                'product_overview' => $productData['product_overview'] ?? null,
                'gst_rate'         => $productData['gst_rate'] ?? 0,
                'visibility_scope' => $productData['visibility_scope'] ?? 'public',
                'is_active'        => $productData['is_active'] ?? true,
            ]);

            // Step 4: create multiple variant records if provided, otherwise create default.
            $variantsInput = $productData['variants'] ?? [];
            if (empty($variantsInput)) {
                // Fallback to a single default variant if no variants were passed (unlikely with new UI)
                $variantsInput[] = [
                    'pack_size' => 'Default',
                    'mrp' => 0,
                    'sku' => $newProduct->sku,
                    'stock_quantity' => 0
                ];
            }

            foreach ($variantsInput as $index => $vData) {
                $variant = ProductVariant::create([
                    'product_id' => $newProduct->id,
                    'sku' => $vData['sku'] ?? ($newProduct->sku . '-' . ($index + 1)),
                    'variant_name' => $vData['pack_size'] ?? 'Default',
                    'pack_size' => $vData['pack_size'] ?? 'Default',
                    'mrp' => $vData['mrp'] ?? 0,
                    'stock_quantity' => $vData['stock_quantity'] ?? 0,
                    'is_active' => true,
                ]);

                // Automatically create a 'public' price row equal to MRP for each variant.
                ProductPrice::create([
                    'product_variant_id' => $variant->id,
                    'price_type' => 'public',
                    'amount' => $vData['mrp'] ?? 0,
                    'currency' => 'INR',
                    'is_active' => true,
                ]);
            }

            // Step 6: handle multiple product image uploads.
            if (!empty($productData['images'])) {
                foreach ($productData['images'] as $index => $imageFile) {
                    $savedPath = $this->compressAndStoreImage($imageFile);
                    ProductImage::create([
                        'product_id' => $newProduct->id,
                        'file_path' => $savedPath,
                        'is_primary' => ($index === 0),
                        'sort_order' => $index,
                    ]);
                }
            }

            // Step 7: handle technical document resource uploads.
            if (!empty($productData['documents'])) {
                foreach ($productData['documents'] as $docFile) {
                    $originalFileName = $docFile->getClientOriginalName();
                    $mimeType = $docFile->getClientMimeType();
                    $fileSize = (int) ($docFile->getSize() ?? 0);
                    $savedPath = $this->fileService->storeUploadedFile($docFile, FileHandlingService::DOCUMENT_DIRECTORY);
                    ProductTechnicalResource::create([
                        'product_id' => $newProduct->id,
                        'title' => $originalFileName,
                        'stored_file_path' => $savedPath,
                        'original_file_name' => $originalFileName,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'is_active' => true,
                    ]);
                }
            }

            return $newProduct->id;
        });
    }

    // Comprehensive update logic handles master, syncing multiple variants, and assets.
    public function updateProduct(int $productId, array $productData): bool
    {
        return DB::transaction(function () use ($productId, $productData) {
            $product = Product::with(['variants', 'images', 'technicalResources'])->find($productId);
            if (!$product) {
                return false;
            }

            // Step 1: update product master information.
            $product->update([
                'name' => $productData['name'] ?? $product->name,
                'sku' => $productData['main_sku'] ?? $product->sku,
                'category_id' => $productData['category_id'] ?? $product->category_id,
                'brand' => $productData['brand'] ?? $product->brand,
                'description' => $productData['description'] ?? $product->description,
                'product_overview' => $productData['product_overview'] ?? $product->product_overview,
                'gst_rate' => $productData['gst_rate'] ?? $product->gst_rate,
                'visibility_scope' => $productData['visibility_scope'] ?? $product->visibility_scope,
                'is_active' => $productData['is_active'] ?? $product->is_active,
            ]);

            // Step 2: Sync variants (Update existing, Create new, Delete missing).
            $incomingVariants = $productData['variants'] ?? [];
            $existingVariantIds = $product->variants->pluck('id')->toArray();
            $processedVariantIds = [];

            foreach ($incomingVariants as $vData) {
                // Cast ID to integer or null, rejecting empty strings
                $variantId = !empty($vData['id']) ? (int) $vData['id'] : null;
                
                if ($variantId && in_array($variantId, $existingVariantIds)) {
                    // Update existing variant
                    $variant = ProductVariant::find($variantId);
                    $variant->update([
                        'sku' => $vData['sku'] ?? $variant->sku,
                        'variant_name' => $vData['pack_size'] ?? $variant->variant_name,
                        'pack_size' => $vData['pack_size'] ?? $variant->pack_size,
                        'mrp' => $vData['mrp'] ?? $variant->mrp,
                        'stock_quantity' => $vData['stock_quantity'] ?? $variant->stock_quantity,
                    ]);
                    $processedVariantIds[] = $variantId;
                } else {
                    // Create new variant
                    $newVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $vData['sku'] ?? ($product->sku . '-' . time()),
                        'variant_name' => $vData['pack_size'] ?? 'Default',
                        'pack_size' => $vData['pack_size'] ?? 'Default',
                        'mrp' => $vData['mrp'] ?? 0,
                        'stock_quantity' => $vData['stock_quantity'] ?? 0,
                        'is_active' => true,
                    ]);
                    $processedVariantIds[] = $newVariant->id;
                }
                
                // Update associated public price row (standard rule: public price always matches MRP on basic setup).
                ProductPrice::updateOrCreate(
                    ['product_variant_id' => end($processedVariantIds), 'price_type' => 'public'],
                    ['amount' => $vData['mrp'] ?? 0, 'is_active' => true, 'currency' => 'INR']
                );
            }

            // Delete variants that were removed from the UI.
            $idsToDelete = array_diff($existingVariantIds, $processedVariantIds);
            if (!empty($idsToDelete)) {
                ProductVariant::whereIn('id', $idsToDelete)->delete();
            }

            // Step 4: remove selected existing images.
            if (!empty($productData['deleted_images'])) {
                $imagesToDelete = ProductImage::where('product_id', $product->id)
                    ->whereIn('id', $productData['deleted_images'])
                    ->get();
                foreach ($imagesToDelete as $image) {
                    $this->cleanupPhysicalFile($image->file_path);
                    $image->delete();
                }
            }

            // Step 5: remove selected technical documents.
            if (!empty($productData['deleted_documents'])) {
                $documentsToDelete = ProductTechnicalResource::where('product_id', $product->id)
                    ->whereIn('id', $productData['deleted_documents'])
                    ->get();
                foreach ($documentsToDelete as $document) {
                    $this->cleanupPhysicalFile($document->stored_file_path);
                    $document->delete();
                }
            }

            // Step 6: handle new assets.
            if (!empty($productData['images'])) {
                foreach ($productData['images'] as $imageFile) {
                    $savedPath = $this->compressAndStoreImage($imageFile);
                    ProductImage::create(['product_id' => $product->id, 'file_path' => $savedPath, 'is_primary' => false]);
                }
            }

            if (!empty($productData['documents'])) {
                foreach ($productData['documents'] as $docFile) {
                    $originalFileName = $docFile->getClientOriginalName();
                    $mimeType = $docFile->getClientMimeType();
                    $fileSize = (int) ($docFile->getSize() ?? 0);
                    $savedPath = $this->fileService->storeUploadedFile($docFile, FileHandlingService::DOCUMENT_DIRECTORY);
                    ProductTechnicalResource::create([
                        'product_id' => $product->id,
                        'title' => $originalFileName,
                        'stored_file_path' => $savedPath,
                        'original_file_name' => $originalFileName,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'is_active' => true,
                    ]);
                }
            }

            return true;
        });
    }

    // Gets a comprehensive view of product data for the edit form including all variants.
    public function getProductForEdit(int $productId): ?Product
    {
        $product = Product::with(['variants', 'images', 'technicalResources'])->find($productId);
        return $product;
    }

    // This performs a hard delete of the product and cleans up all related physical files.
    public function deleteProduct(int $productId): bool
    {
        return DB::transaction(function () use ($productId) {
            $product = Product::with(['images', 'technicalResources'])->find($productId);
            if (!$product) {
                return false;
            }

            // Step 1: remove all linked product images from storage.
            foreach ($product->images as $image) {
                $this->cleanupPhysicalFile($image->file_path);
            }

            // Step 2: remove all linked technical documents from storage.
            foreach ($product->technicalResources as $doc) {
                $this->cleanupPhysicalFile($doc->stored_file_path);
            }

            // Step 3: delete database record (related rows will be deleted if on-cascade is set, otherwise handled here).
            $product->delete();

            return true;
        });
    }


    // Helper to store an uploaded image to the same public folder used by the storefront.
    private function compressAndStoreImage($file): string
    {
        $directory = FileHandlingService::PRODUCT_IMAGE_DIRECTORY;
        $extension = $file->getClientOriginalExtension();

        // Build a clean base name from the original file name, without extension.
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        // If baseName is empty after sanitizing, fall back to a timestamp.
        if ($baseName === '') {
            $baseName = 'product-' . time();
        }

        // If a file with this name already exists, append an incrementing number.
        $finalName = $baseName . '.' . $extension;
        $absoluteDir = public_path($directory);
        $counter = 1;
        while (file_exists($absoluteDir . '/' . $finalName)) {
            $finalName = $baseName . '-' . $counter . '.' . $extension;
            $counter++;
        }

        // Use the shared file service to move the file into the public upload folder.
        return $this->fileService->storeUploadedFile($file, $directory, pathinfo($finalName, PATHINFO_FILENAME));
    }

    // Helper to safely remove a physical file from the public upload area.
    private function cleanupPhysicalFile(string $relativePath): void
    {
        $absolutePath = public_path($relativePath);
        if (File::exists($absolutePath)) {
            File::delete($absolutePath);
        }
    }

    // Calculate total stock by summing all variant stock quantities.
    private function calculateProductTotalStock(Product $product): int
    {
        $variants = $product->variants ?? [];
        $totalStock = 0;
        foreach ($variants as $variant) {
            $totalStock += $variant->stock_quantity ?? 0;
        }
        return $totalStock;
    }

    // Get the price of the default variant.
    private function getProductDefaultPrice(Product $product): ?float
    {
        // Now using MRP as the base reference or the public price row
        $mrp = $product->defaultVariant?->mrp;
        if ($mrp > 0) {
            return (float)$mrp;
        }

        $publicPrice = $product->defaultVariant?->prices->where('price_type', 'public')->first();
        return $publicPrice ? (float)$publicPrice->amount : null;
    }

    // Determine stock status for display.
    private function determineStockStatus(int $stock): string
    {
        if ($stock <= 0) return 'Out of Stock';
        if ($stock <= 20) return 'Low Stock';
        return 'In Stock';
    }
}

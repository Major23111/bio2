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

    // Step-by-step product creation including master, variant, and media assets.
    public function createProduct(array $productData): int
    {
        return DB::transaction(function () use ($productData) {
            // Step 1: generate a URL-friendly slug from the product name.
            $baseSlug = Str::slug($productData['name']);

            // Step 2: make the slug unique by appending the SKU if the base slug already exists.
            $slugExists = Product::where('slug', $baseSlug)->exists();
            $finalSlug = $slugExists ? $baseSlug . '-' . Str::slug($productData['sku']) : $baseSlug;

            // Step 3: create basic product master record.
            $newProduct = Product::create([
                'name'             => $productData['name'],
                'slug'             => $finalSlug,
                'sku'              => $productData['sku'],
                'category_id'      => $productData['category_id'],
                'brand'            => $productData['brand'] ?? null,
                'description'      => $productData['description'] ?? null,
                'product_overview' => $productData['product_overview'] ?? null,
                'gst_rate'         => $productData['gst_rate'] ?? 0,
                'visibility_scope' => $productData['visibility_scope'] ?? 'all',
                'is_active'        => $productData['is_active'] ?? true,
            ]);

            // Step 4: create the default variant record to track stock.
            $newVariant = ProductVariant::create([
                'product_id' => $newProduct->id,
                'sku' => $newProduct->sku,
                'variant_name' => 'Default',
                'stock_quantity' => $productData['stock_quantity'] ?? 0,
                'is_active' => true,
            ]);

            // Step 5: create default pricing for the new variant.
            ProductPrice::create([
                'product_variant_id' => $newVariant->id,
                'price_type' => 'base',
                'amount' => $productData['base_price'] ?? 0,
                'is_active' => true,
            ]);

            // Step 4: handle multiple product image uploads with compression.
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

            // Step 5: handle technical document resource uploads.
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

    // Step-by-step update logic for existing products and their linked records.
    public function updateProduct(int $productId, array $productData): bool
    {
        return DB::transaction(function () use ($productId, $productData) {
            $product = Product::with(['defaultVariant.prices', 'images', 'technicalResources'])->find($productId);
            if (!$product) {
                return false;
            }

            // Step 1: update product master information.
            $product->update([
                'name' => $productData['name'] ?? $product->name,
                'sku' => $productData['sku'] ?? $product->sku,
                'category_id' => $productData['category_id'] ?? $product->category_id,
                'brand' => $productData['brand'] ?? $product->brand,
                'description' => $productData['description'] ?? $product->description,
                'product_overview' => $productData['product_overview'] ?? $product->product_overview,
                'gst_rate' => $productData['gst_rate'] ?? $product->gst_rate,
                'visibility_scope' => $productData['visibility_scope'] ?? $product->visibility_scope,
                'is_active' => $productData['is_active'] ?? $product->is_active,
            ]);

            // Step 2: update stock level in the default variant.
            $defaultVariant = $product->defaultVariant;
            if ($defaultVariant) {
                $defaultVariant->update([
                    'stock_quantity' => $productData['stock_quantity'] ?? $defaultVariant->stock_quantity,
                ]);

                // Step 3: update base price record.
                $basePrice = $defaultVariant->prices()->where('price_type', 'base')->first();
                if ($basePrice) {
                    $basePrice->update([
                        'amount' => $productData['base_price'] ?? $basePrice->amount,
                    ]);
                }
            }

            // Step 4: remove selected existing images and their physical files.
            if (! empty($productData['deleted_images'])) {
                $imagesToDelete = ProductImage::where('product_id', $product->id)
                    ->whereIn('id', $productData['deleted_images'])
                    ->get();

                foreach ($imagesToDelete as $image) {
                    $this->cleanupPhysicalFile($image->file_path);
                    $image->delete();
                }
            }

            // Step 5: remove selected technical documents and their physical files.
            if (! empty($productData['deleted_documents'])) {
                $documentsToDelete = ProductTechnicalResource::where('product_id', $product->id)
                    ->whereIn('id', $productData['deleted_documents'])
                    ->get();

                foreach ($documentsToDelete as $document) {
                    $this->cleanupPhysicalFile($document->stored_file_path);
                    $document->delete();
                }
            }

            // Step 6: handle new images if provided by user.
            if (!empty($productData['images'])) {
                foreach ($productData['images'] as $imageFile) {
                    $savedPath = $this->compressAndStoreImage($imageFile);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_path' => $savedPath,
                        'is_primary' => false,
                    ]);
                }
            }

            // Step 7: handle new technical documents if provided by user.
            if (! empty($productData['documents'])) {
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

    // Gets a comprehensive view of product data for the edit form.
    public function getProductForEdit(int $productId): ?Product
    {
        $product = Product::with(['defaultVariant.prices', 'images', 'technicalResources'])->find($productId);
        if (!$product) {
            return null;
        }

        $defaultVariant = $product->defaultVariant;
        $basePrice = $defaultVariant?->prices->where('price_type', 'base')->first();

        $product->setAttribute('stock_quantity', (int) ($defaultVariant->stock_quantity ?? 0));
        $product->setAttribute('base_price', (float) ($basePrice->amount ?? 0));

        return $product;
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
        $basePrice = $product->defaultVariant?->prices->where('price_type', 'base')->first();
        return $basePrice ? (float)$basePrice->amount : null;
    }

    // Determine stock status for display.
    private function determineStockStatus(int $stock): string
    {
        if ($stock <= 0) return 'Out of Stock';
        if ($stock <= 20) return 'Low Stock';
        return 'In Stock';
    }
}

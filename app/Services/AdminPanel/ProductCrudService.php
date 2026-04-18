<?php

namespace App\Services\AdminPanel;

use App\Models\Product\Product;
use Illuminate\Support\Collection;

class ProductCrudService
{
    // Get all products with basic information for admin list view.
    public function getAllProductsForAdminList(): Collection
    {
        $allProducts = Product::with(['category', 'defaultVariant.prices'])
            ->orderBy('name')
            ->get();

        $productsList = [];

        // Prepare each product's data for admin display.
        foreach ($allProducts as $product) {
            $totalStock = $this->calculateProductTotalStock($product);
            $productPrice = $this->getProductDefaultPrice($product);

            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'categoryName' => $product->category?->name ?? 'Uncategorized',
                'price' => $productPrice,
                'stock' => $totalStock,
                'status' => $this->determineStockStatus($totalStock),
            ];

            $productsList[] = $productData;
        }

        return collect($productsList);
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
        $defaultVariant = $product->defaultVariant;

        if (!$defaultVariant) {
            return null;
        }

        $prices = $defaultVariant->prices ?? [];

        // Return the first active price or null.
        foreach ($prices as $price) {
            if ($price->is_active) {
                return (float) $price->amount;
            }
        }

        return null;
    }

    // Determine stock status based on quantity.
    private function determineStockStatus(int $stock): string
    {
        if ($stock <= 0) {
            return 'Out of Stock';
        }

        if ($stock <= 20) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    // Create new product with provided information.
    public function createProduct(array $productData): int
    {
        // Prepare product information.
        $name = $productData['name'] ?? null;
        $sku = $productData['sku'] ?? null;
        $categoryId = $productData['category_id'] ?? null;
        $brand = $productData['brand'] ?? null;
        $description = $productData['description'] ?? null;
        $isActive = $productData['is_active'] ?? true;

        // Create new product record.
        $newProduct = Product::create([
            'name' => $name,
            'sku' => $sku,
            'category_id' => $categoryId,
            'brand' => $brand,
            'description' => $description,
            'is_active' => $isActive,
        ]);

        // Return product ID.
        return $newProduct->id;
    }

    // Get product information for editing.
    public function getProductForEdit(int $productId): ?array
    {
        // Fetch product with category information.
        $product = Product::with('category')->find($productId);

        // Return null if product not found.
        if (!$product) {
            return null;
        }

        // Return product data as array.
        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'category_id' => $product->category_id,
            'brand' => $product->brand,
            'description' => $product->description,
            'is_active' => $product->is_active,
        ];
    }

    // Update product with provided information.
    public function updateProduct(int $productId, array $productData): bool
    {
        // Fetch product record.
        $product = Product::find($productId);

        // Return false if product not found.
        if (!$product) {
            return false;
        }

        // Prepare updated product information.
        $name = $productData['name'] ?? $product->name;
        $sku = $productData['sku'] ?? $product->sku;
        $categoryId = $productData['category_id'] ?? $product->category_id;
        $brand = $productData['brand'] ?? $product->brand;
        $description = $productData['description'] ?? $product->description;
        $isActive = $productData['is_active'] ?? $product->is_active;

        // Update product record.
        $product->update([
            'name' => $name,
            'sku' => $sku,
            'category_id' => $categoryId,
            'brand' => $brand,
            'description' => $description,
            'is_active' => $isActive,
        ]);

        // Return success status.
        return true;
    }
}

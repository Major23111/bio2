<?php

namespace App\Services\Inventory;

use App\Models\Product\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

// Service to manage product stock across the order lifecycle
class InventoryManagementService
{
    // Check if sufficient stock exists for the requested quantity
    public function checkAvailability(int $productId, ?int $variantId, int $requestedQuantity): bool
    {
        // Find the variant to check stock
        $variant = $this->findProductVariant($productId, $variantId);

        // Check if enough stock is available
        $availableStock = (int) ($variant->stock_quantity ?? 0);

        return $availableStock >= $requestedQuantity;
    }

    // Reserve stock when customer proceeds to checkout (tentative hold)
    public function reserveStock(int $productId, ?int $variantId, int $quantityToReserve): void
    {
        // Find the variant and lock it for this transaction
        $variant = ProductVariant::query()
            ->where('product_id', $productId)
            ->when($variantId, fn ($query) => $query->where('id', $variantId))
            ->lockForUpdate()
            ->first();

        // Stop if variant no longer exists
        if (! $variant) {
            throw ValidationException::withMessages([
                'product' => 'Product is no longer available.',
            ]);
        }

        // Calculate remaining stock after reservation
        $remainingStock = (int) ($variant->stock_quantity ?? 0) - $quantityToReserve;

        // Stop if reservation would make stock negative
        if ($remainingStock < 0) {
            throw ValidationException::withMessages([
                'quantity' => "Insufficient stock. Only {$variant->stock_quantity} units available.",
            ]);
        }

        // Reserve the stock by deducting from available quantity
        $this->updateStock($variant, $remainingStock);
    }

    // Deduct stock after successful order placement (permanent removal)
    public function deductStock(int $productId, ?int $variantId, int $quantityToDeduct): void
    {
        // Start transaction to ensure atomic stock update
        DB::beginTransaction();

        try {
            // Find and lock the variant
            $variant = ProductVariant::query()
                ->where('product_id', $productId)
                ->when($variantId, fn ($query) => $query->where('id', $variantId))
                ->lockForUpdate()
                ->first();

            // Stop if variant no longer exists
            if (! $variant) {
                throw ValidationException::withMessages([
                    'product' => 'Product is no longer available for deduction.',
                ]);
            }

            // Calculate stock after deduction
            $newStock = (int) ($variant->stock_quantity ?? 0) - $quantityToDeduct;

            // Stop if deduction would create negative stock
            if ($newStock < 0) {
                throw ValidationException::withMessages([
                    'inventory' => "Cannot deduct {$quantityToDeduct} units. Only {$variant->stock_quantity} available.",
                ]);
            }

            // Update stock in database
            $this->updateStock($variant, $newStock);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    // Release reserved stock back to available quantity (for cancellations/failures)
    public function releaseStock(int $productId, ?int $variantId, int $quantityToRelease): void
    {
        // Find the variant to release stock back
        $variant = $this->findProductVariant($productId, $variantId);

        // Calculate stock after releasing reservation
        $newStock = (int) ($variant->stock_quantity ?? 0) + $quantityToRelease;

        // Update stock to add released quantity back
        $this->updateStock($variant, $newStock);
    }

    // Find the correct variant for the product
    private function findProductVariant(int $productId, ?int $variantId): ProductVariant
    {
        // Build query to find product's variant
        $query = ProductVariant::query()
            ->where('product_id', $productId);

        // If specific variant ID provided, use it
        if ($variantId) {
            $query->where('id', $variantId);
        } else {
            // Otherwise use first active variant
            $query->where('is_active', true)->orderBy('id');
        }

        // Fetch the variant
        $variant = $query->first();

        // Stop if variant not found
        if (! $variant) {
            throw ValidationException::withMessages([
                'product' => 'Product variant not found.',
            ]);
        }

        return $variant;
    }

    // Update stock quantity in database for a variant
    private function updateStock(ProductVariant $variant, int $newStockQuantity): void
    {
        // Set the new stock amount
        $variant->stock_quantity = max(0, $newStockQuantity);

        // Save to database
        $variant->save();
    }
}

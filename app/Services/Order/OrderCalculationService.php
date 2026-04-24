<?php

namespace App\Services\Order;

use Illuminate\Validation\ValidationException;

class OrderCalculationService
{
    public function __construct() {}

    // Calculate order totals from items and optional header amounts.
    public function calculateOrderTotals(array $extraData, array $preparedOrderItems, string $priceSource = 'current_visible_price'): array
    {
        // Read extra amounts from the form.
        $shippingAmount = round((float) ($extraData['shipping_amount'] ?? 0), 4);
        $adjustmentAmount = round((float) ($extraData['adjustment_amount'] ?? 0), 4);
        $roundingAmount = round((float) ($extraData['rounding_amount'] ?? 0), 4);
        $currency = (string) ($preparedOrderItems[0]['item_snapshot']['currency'] ?? 'INR');

        // Sum all item amounts.
        $subtotalAmount = round(collect($preparedOrderItems)->sum('subtotal_amount'), 4);
        $taxAmount = round(collect($preparedOrderItems)->sum('tax_amount'), 4);
        $discountAmount = round(collect($preparedOrderItems)->sum('discount_amount'), 4);
        $itemsTotal = round(collect($preparedOrderItems)->sum('total_amount'), 4);
        $totalAmount = round($itemsTotal + $shippingAmount + $adjustmentAmount + $roundingAmount, 4);

        // Create a snapshot for future reference.
        $pricingSnapshot = [
            'source' => $priceSource,
            'currency' => $currency,
            'items_count' => count($preparedOrderItems),
            'subtotal_amount' => $subtotalAmount,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'items_total' => $itemsTotal,
            'shipping_amount' => $shippingAmount,
            'adjustment_amount' => $adjustmentAmount,
            'rounding_amount' => $roundingAmount,
            'total_amount' => $totalAmount,
        ];

        // Add extra fields for reorder context.
        if ($priceSource === 'reorder_checkout') {
            $pricingSnapshot['coupon_code'] = $extraData['coupon_code'] ?? null;
        } else {
            $pricingSnapshot['price_types'] = collect($preparedOrderItems)
                ->map(fn (array $item) => $item['item_snapshot']['price_type'] ?? null)
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        return [
            'currency' => $currency,
            'subtotal_amount' => $subtotalAmount,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'shipping_amount' => $shippingAmount,
            'adjustment_amount' => $adjustmentAmount,
            'rounding_amount' => $roundingAmount,
            'total_amount' => $totalAmount,
            'pricing_snapshot' => $pricingSnapshot,
        ];
    }

    // Validate that the quantity meets min, max, and lot-size rules.
    public function validateOrderQuantity(int $quantity, array $price, int $index): void
    {
        if (! $this->isValidQuantity($quantity, $price)) {
            $errorMessage = $this->getQuantityErrorMessage($quantity, $price);
            throw ValidationException::withMessages([
                "quantity.$index" => "Quantity for item ".($index + 1)." - ".$errorMessage.".",
            ]);
        }
    }

    // Check if quantity is valid for the order.
    public function isValidQuantity(int $quantity, array $priceData): bool
    {
        // Get minimum order quantity required.
        $minQuantity = (int) ($priceData['min_order_quantity'] ?? 1);

        // Get maximum order quantity allowed.
        $maxQuantity = $priceData['max_order_quantity'] ?? null;

        // Get lot size (must be multiple of this).
        $lotSize = (int) ($priceData['lot_size'] ?? 1);

        // Check if quantity meets minimum.
        if ($quantity < $minQuantity) {
            return false;
        }

        // Check if quantity exceeds maximum (if set).
        if ($maxQuantity !== null && $quantity > $maxQuantity) {
            return false;
        }

        // Check if quantity is multiple of lot size.
        if ($lotSize > 1 && $quantity % $lotSize !== 0) {
            return false;
        }

        return true;
    }

    // Get error message for invalid quantity.
    public function getQuantityErrorMessage(int $quantity, array $priceData): string
    {
        // Get quantity constraints.
        $minQuantity = (int) ($priceData['min_order_quantity'] ?? 1);
        $maxQuantity = $priceData['max_order_quantity'] ?? null;
        $lotSize = (int) ($priceData['lot_size'] ?? 1);

        // Check minimum quantity failure.
        if ($quantity < $minQuantity) {
            return "Minimum order quantity is {$minQuantity}";
        }

        // Check maximum quantity failure.
        if ($maxQuantity !== null && $quantity > $maxQuantity) {
            return "Maximum order quantity is {$maxQuantity}";
        }

        // Check lot size failure.
        if ($lotSize > 1 && $quantity % $lotSize !== 0) {
            return "Quantity must be in multiples of {$lotSize}";
        }

        return "Quantity is invalid";
    }

    // Build one order item from a product and resolved price.
    public function buildOrderItemPayload(object $visibleProduct, array $price, int $quantity, int $index): array
    {
        // Calculate the row totals.
        $unitPrice = round((float) ($price['amount'] ?? 0), 4);
        $unitBasePrice = round((float) ($price['base_amount'] ?? $unitPrice), 4);
        $unitTaxAmount = round((float) ($price['tax_amount'] ?? 0), 4);
        $unitPriceAfterGst = round((float) ($price['price_after_gst'] ?? 0), 4);
        $subtotalAmount = round($unitPrice * $quantity, 4);
        $taxAmount = round($unitTaxAmount * $quantity, 4);
        $discountAmount = round((float) ($price['discount_amount'] ?? 0) * $quantity, 4);
        $totalAmount = round($unitPriceAfterGst * $quantity, 4);

        // Store pricing details for future reference.
        $itemSnapshot = [
            'currency' => $price['currency'] ?? 'INR',
            'price_type' => $price['price_type'] ?? null,
            'base_unit_price' => $unitBasePrice,
            'pricing_stage' => $price['pricing_stage'] ?? 'base_price',
            'gst_rate' => round((float) ($price['gst_rate'] ?? 0), 4),
            'unit_tax_amount' => $unitTaxAmount,
            'unit_price_after_gst' => $unitPriceAfterGst,
            'unit_discount_amount' => round((float) ($price['discount_amount'] ?? 0), 4),
            'product_discount_amount' => round((float) ($price['product_discount_amount'] ?? 0), 4),
            'bulk_discount_amount' => round((float) ($price['bulk_discount_amount'] ?? 0), 4),
            'coupon_discount_amount' => round((float) ($price['coupon_discount_amount'] ?? 0), 4),
            'applied_coupon_code' => $price['applied_coupon_code'] ?? null,
            'coupon_status' => $price['coupon_status'] ?? null,
            'coupon_message' => $price['coupon_message'] ?? null,
            'min_order_quantity' => (int) ($price['min_order_quantity'] ?? 1),
            'max_order_quantity' => $price['max_order_quantity'] === null ? null : (int) $price['max_order_quantity'],
            'lot_size' => (int) ($price['lot_size'] ?? 1),
            'product_variant_id' => $price['product_variant_id'] ?? null,
            'variant_sku' => $price['variant_sku'] ?? null,
            'variant_name' => $price['variant_name'] ?? null,
        ];

        return [
            'product_id' => (int) $visibleProduct->id,
            'product_variant_id' => $price['product_variant_id'] ?? null,
            'sku' => (string) ($price['variant_sku'] ?? $visibleProduct->sku),
            'product_name' => (string) $visibleProduct->name,
            'variant_name' => $price['variant_name'] ?? null,
            'description' => 'Resolved from current visible product price.',
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal_amount' => $subtotalAmount,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'sort_order' => $index,
            'item_snapshot' => $itemSnapshot,
        ];
    }
}

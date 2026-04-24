<?php
namespace App\Services\Utility;

class OrderItemCalculator
{
    // Calculate all pricing for one order item.
    public function calculateItemPricing(array $priceData, int $quantity): array
    {
        // Get unit price (base selling price).
        $unitPrice = (float) ($priceData['amount'] ?? 0);

        // Get base MRP price for display.
        $unitBasePrice = (float) ($priceData['base_amount'] ?? $unitPrice);

        // Get tax amount per unit.
        $unitTaxAmount = (float) ($priceData['tax_amount'] ?? 0);

        // Get final price with tax.
        $unitPriceAfterTax = (float) ($priceData['price_after_gst'] ?? 0);

        // Calculate subtotal (before tax).
        $subtotalAmount = $unitPrice * $quantity;

        // Calculate total tax.
        $totalTaxAmount = $unitTaxAmount * $quantity;

        // Calculate final total (after tax).
        $finalTotalAmount = $unitPriceAfterTax * $quantity;

        // Calculate discount if applied.
        $discountPerUnit = (float) ($priceData['discount_amount'] ?? 0);
        $totalDiscountAmount = $discountPerUnit * $quantity;

        $finalCurrency = (string) ($priceData['currency'] ?? 'INR');
        $finalPriceType = $priceData['price_type'] ?? null;
        $finalGstRate = (float) ($priceData['gst_rate'] ?? 0);
        $minimumQuantity = (int) ($priceData['min_order_quantity'] ?? 1);
        $maximumQuantity = $priceData['max_order_quantity'] ?? null;
        $lotSize = (int) ($priceData['lot_size'] ?? 1);

        return [
            'currency' => $finalCurrency,
            'price_type' => $finalPriceType,
            'unit_price' => $this->roundToCurrency($unitPrice),
            'base_unit_price' => $this->roundToCurrency($unitBasePrice),
            'gst_rate' => $this->roundToCurrency($finalGstRate),
            'unit_tax_amount' => $this->roundToCurrency($unitTaxAmount),
            'unit_tax' => $this->roundToCurrency($unitTaxAmount),
            'unit_price_after_gst' => $this->roundToCurrency($unitPriceAfterTax),
            'subtotal_amount' => $this->roundToCurrency($subtotalAmount),
            'tax_amount' => $this->roundToCurrency($totalTaxAmount),
            'total_amount' => $this->roundToCurrency($finalTotalAmount),
            'discount_amount' => $this->roundToCurrency($totalDiscountAmount),
            'min_order_quantity' => max(1, $minimumQuantity),
            'max_order_quantity' => $maximumQuantity === null ? null : (int) $maximumQuantity,
            'lot_size' => max(1, $lotSize),
        ];
    }

    // Round currency value to 4 decimals (standard for Indian rupees).
    private function roundToCurrency(float $value): float
    {
        return round($value, 4);
    }

    // Extract minimal snapshot fields needed for audit and reorder.
    public function buildMinimalSnapshot(array $priceData): array
    {
        return [
            'currency' => $priceData['currency'] ?? 'INR',
            'unit_price' => $this->roundToCurrency((float) ($priceData['amount'] ?? 0)),
            'tax_rate' => (float) ($priceData['gst_rate'] ?? 0),
            'price_type' => $priceData['price_type'] ?? 'catalog',
            'coupon_code' => $priceData['applied_coupon_code'] ?? null,
        ];
    }
}

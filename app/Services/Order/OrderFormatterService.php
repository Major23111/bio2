<?php

namespace App\Services\Order;

use Illuminate\Support\Collection;

class OrderFormatterService
{
    /**
     * Format orders for customer profile display.
     * Returns raw data; views handle all formatting (dates, currency, etc).
     */
    public function formatCustomerOrdersForDisplay(Collection $savedOrders): array
    {
        $orders = [];
        $fallbackImages = [
            asset('upload/categories/image1.jpg'),
            asset('upload/categories/image2.jpg'),
            asset('upload/categories/image5.jpg'),
        ];

        foreach ($savedOrders as $orderIndex => $savedOrder) {
            $preparedItems = $this->formatOrderItems($savedOrder, $fallbackImages);
            $firstItem = $preparedItems[0] ?? null;
            $itemCount = count($preparedItems);

            $orders[] = [
                // IDs and URLs
                'id' => $savedOrder->id,
                'order_id' => $savedOrder->id,
                'reorder_url' => route('orders.reorder', ['orderId' => encrypt_url_value($savedOrder->id)]),

                // Status
                'status' => $savedOrder->status,
                'status_key' => $this->mapStatusToKey($savedOrder->status),

                // Product and summary
                'product_name' => $firstItem['name'] ?? 'Order',
                'item_count' => $itemCount,
                'summary_note' => $savedOrder->notes ?: null,

                // Dates and amounts (raw, unformatted)
                'submitted_at' => $savedOrder->submitted_at,
                'created_at' => $savedOrder->created_at,
                'subtotal_amount' => (float) $savedOrder->subtotal_amount,
                'tax_amount' => (float) $savedOrder->tax_amount,
                'shipping_amount' => (float) $savedOrder->shipping_amount,
                'total_amount' => (float) $savedOrder->total_amount,
                'currency' => $savedOrder->currency ?: 'INR',

                // Card display
                'image' => $firstItem['image'] ?? $fallbackImages[$orderIndex % count($fallbackImages)],
                'image_background' => $firstItem['background'] ?? 'bg-slate-50',

                // Modal display
                'tracking_id' => 'Order #'.$savedOrder->id,
                'carrier' => 'Live shipment tracking is not available yet.',
                'address_lines' => $this->formatAddressLines($savedOrder),

                // Items for detailed view (with raw amounts)
                'items' => $preparedItems,
            ];
        }

        return ['orders' => $orders];
    }

    /**
     * Format order items for display - returns raw amounts, view formats.
     */
    private function formatOrderItems(mixed $savedOrder, array $fallbackImages): array
    {
        $items = [];

        foreach ($savedOrder->items as $itemIndex => $savedItem) {
            $productImagePath = $savedItem->product?->primaryImage?->file_path;
            $imageUrl = $productImagePath ? asset($productImagePath) : $fallbackImages[$itemIndex % count($fallbackImages)];

            $items[] = [
                'name' => $savedItem->product_name,
                'subtitle' => $savedItem->variant_name ?: 'Order item',
                'sku' => $savedItem->sku ?: 'N/A',
                'quantity' => (int) $savedItem->quantity,
                'unit_price' => (float) $savedItem->unit_price,
                'total_amount' => (float) $savedItem->total_amount,
                'image' => $imageUrl,
                'background' => $itemIndex % 2 === 0 ? 'bg-primary-50' : 'bg-slate-50',
            ];
        }

        return $items;
    }

    /**
     * Map order status to UI status key.
     */
    private function mapStatusToKey(string $status): string
    {
        return match ($status) {
            'submitted' => 'shipped',
            'cancelled' => 'archived',
            default => 'processing',
        };
    }

    /**
     * Format address lines for display.
     */
    private function formatAddressLines(mixed $savedOrder): array
    {
        $address = $savedOrder->shippingAddress ?: $savedOrder->billingAddress;
        $lines = [];

        if ($address) {
            if ($address->company_name) {
                $lines[] = $address->company_name;
            }
            if ($address->contact_name) {
                $lines[] = $address->contact_name;
            }

            $lines[] = $address->line1;

            if ($address->line2) {
                $lines[] = $address->line2;
            }

            $cityLine = trim($address->city.', '.$address->state.' '.$address->postal_code);
            $lines[] = $cityLine;
        }

        return $lines ?: ['Address details not available'];
    }
}

<?php

namespace App\Services\AdminPanel\Order;

use App\Models\Order\Order;
use Illuminate\Support\Collection;

class OrderCrudService
{
    // Get all orders with basic information for admin list view.
    public function getAllOrdersForAdminList(): Collection
    {
        $allOrders = Order::with([
            'placedByUser:id,name',
            'company:id,name',
            'items:id,order_id,product_name',
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return $allOrders->map(function (Order $order): array {
            $status = $order->status ?? 'draft';

            return [
                'id' => $order->id,
                'customerName' => $order->placedByUser?->name ?? 'Unknown Customer',
                'companyName' => $order->company?->name ?? 'Direct Customer',
                'status' => $status,
                'primaryItemName' => $order->items->first()?->product_name ?? 'Items awaiting review',
                'itemCount' => $order->items->count(),
                'totalAmount' => (float) ($order->total_amount ?? 0),
                'currency' => $order->currency ?? 'INR',
                'createdDate' => $order->created_at,
            ];
        });
    }

    // Get order information for viewing and editing.
    public function getOrderForView(int $orderId): ?array
    {
        // Fetch order with related information.
        $order = Order::with(['placedByUser', 'company', 'items', 'shippingAddress'])
            ->find($orderId);

        // Return null if order not found.
        if (!$order) {
            return null;
        }

        // Build order information array.
        $customerName = $order->placedByUser?->name ?? 'Unknown Customer';
        $companyName = $order->company?->name ?? 'N/A';

        // Return order data as array.
        return [
            'id' => $order->id,
            'customerName' => $customerName,
            'companyName' => $companyName,
            'status' => $order->status,
            'currency' => $order->currency,
            'subtotalAmount' => $order->subtotal_amount,
            'taxAmount' => $order->tax_amount,
            'discountAmount' => $order->discount_amount,
            'shippingAmount' => $order->shipping_amount,
            'totalAmount' => $order->total_amount,
            'notes' => $order->notes,
            'createdDate' => $order->created_at,
        ];
    }

    // Update order with provided information.
    public function updateOrder(int $orderId, array $orderData): bool
    {
        // Fetch order record.
        $order = Order::find($orderId);

        // Return false if order not found.
        if (!$order) {
            return false;
        }

        // Prepare updated order information.
        $status = $orderData['status'] ?? $order->status;
        $notes = $orderData['notes'] ?? $order->notes;

        // Update order record.
        $order->update([
            'status' => $status,
            'notes' => $notes,
        ]);

        // Return success status.
        return true;
    }
}

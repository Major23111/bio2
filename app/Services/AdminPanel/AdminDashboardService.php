<?php

namespace App\Services\AdminPanel;

use App\Enums\OrderStatus;
use App\Models\Order\Order;
use Illuminate\Support\Collection;

class AdminDashboardService
{
    // This gathers all key performance metrics for the admin dashboard.
    public function getDashboardStatistics(): array
    {
        // Step 1: calculate total orders count in the system.
        $totalOrdersCount = Order::count();

        // Step 2: calculate orders placed today.
        $todayOrdersCount = Order::whereDate('created_at', now()->today())->count();

        // Step 3: calculate pending orders that are yet to be dispatched.
        $pendingOrdersCount = Order::where('status', OrderStatus::Submitted->value)->count();

        // Step 4: calculate total revenue from all completed orders.
        $totalRevenueAmount = Order::where('status', OrderStatus::Completed->value)->sum('total_amount');

        return [
            'totalOrders' => $totalOrdersCount,
            'todayOrders' => $todayOrdersCount,
            'pendingOrders' => $pendingOrdersCount,
            'totalRevenue' => (float) $totalRevenueAmount,
        ];
    }

    // This fetches the most recent orders to display in the dashboard table.
    public function getRecentDashboardOrders(int $limit = 5): Collection
    {
        // Step 1: load orders with customer and item relations.
        $recentOrders = Order::with(['placedByUser', 'items'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        // Step 2: prepare the order data for table display.
        return $recentOrders->map(function ($order) {
            return [
                'id' => $order->id,
                'orderNumber' => '#BGX-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                'clientName' => $order->placedByUser?->name ?? 'Guest Customer',
                'clientInitial' => strtoupper(substr($order->placedByUser?->name ?? 'G', 0, 1)),
                'primaryItemName' => $order->items->first()?->product_name ?? 'N/A',
                'orderValue' => (float) $order->total_amount,
                'status' => $order->status,
                'statusLabel' => ucfirst($order->status),
            ];
        });
    }
}

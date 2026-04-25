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
        $yesterdayOrdersCount = Order::whereDate('created_at', now()->subDay())->count();

        // Step 3: calculate pending orders that are yet to be dispatched.
        $pendingOrdersCount = Order::where('status', OrderStatus::Submitted->value)->count();

        // Step 4: calculate total revenue from all completed orders.
        $totalRevenueAmount = Order::where('status', OrderStatus::Completed->value)->sum('total_amount');

        // Growth calculations
        $thisMonthOrdersCount = Order::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)->count();
        $lastMonthOrdersCount = Order::whereMonth('created_at', now()->subMonth()->month)
                                     ->whereYear('created_at', now()->subMonth()->year)->count();
        
        $thisMonthRevenue = Order::where('status', OrderStatus::Completed->value)
                                 ->whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)->sum('total_amount');
        $lastMonthRevenue = Order::where('status', OrderStatus::Completed->value)
                                 ->whereMonth('created_at', now()->subMonth()->month)
                                 ->whereYear('created_at', now()->subMonth()->year)->sum('total_amount');

        $todayOrdersGrowth = $this->calculateGrowth($todayOrdersCount, $yesterdayOrdersCount);
        $totalOrdersGrowth = $this->calculateGrowth($thisMonthOrdersCount, $lastMonthOrdersCount);
        $revenueGrowth = $this->calculateGrowth($thisMonthRevenue, $lastMonthRevenue);

        $chartData = $this->generateChartData();

        return [
            'totalOrders' => $totalOrdersCount,
            'todayOrders' => $todayOrdersCount,
            'pendingOrders' => $pendingOrdersCount,
            'totalRevenue' => (float) $totalRevenueAmount,
            'todayOrdersGrowth' => $todayOrdersGrowth,
            'totalOrdersGrowth' => $totalOrdersGrowth,
            'revenueGrowth' => $revenueGrowth,
            'chartData' => $chartData,
        ];
    }

    private function calculateGrowth($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function generateChartData(): array
    {
        return [
            'weekly' => $this->getChartSeriesData('day', 7),
            'monthly' => $this->getChartSeriesData('week', 7),
            'yearly' => $this->getChartSeriesData('month', 7),
        ];
    }

    private function getChartSeriesData(string $period, int $points): array
    {
        $values = [];
        $labels = [];

        for ($i = $points - 1; $i >= 0; $i--) {
            $query = Order::where('status', OrderStatus::Completed->value);
            
            if ($period === 'day') {
                $date = now()->subDays($i);
                $query->whereDate('created_at', $date);
                $labels[] = strtoupper($date->format('D')); // MON, TUE
            } elseif ($period === 'week') {
                $start = now()->subWeeks($i)->startOfWeek();
                $end = now()->subWeeks($i)->endOfWeek();
                $query->whereBetween('created_at', [$start, $end]);
                $labels[] = 'W' . $start->format('W'); // W12
            } elseif ($period === 'month') {
                $date = now()->subMonths($i);
                $query->whereMonth('created_at', $date->month)
                      ->whereYear('created_at', $date->year);
                $labels[] = strtoupper($date->format('M')); // JAN, FEB
            }

            $values[] = (float) $query->sum('total_amount');
        }

        // Normalize to percentages (0 to 100) for UI bar height
        $max = max($values);
        $percentages = array_map(function ($val) use ($max) {
            return $max > 0 ? round(($val / $max) * 100) : 0;
        }, $values);

        // UI expects minimum 10% height for visual aesthetics if value is 0 or very small
        $percentages = array_map(function ($val) {
            return max(10, $val);
        }, $percentages);

        return [
            'values' => $values,
            'percentages' => $percentages,
            'labels' => $labels,
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

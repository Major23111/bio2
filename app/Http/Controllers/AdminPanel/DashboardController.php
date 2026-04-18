<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\Order\OrderCrudService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected OrderCrudService $orderCrudService)
    {
    }

    public function index(): View
    {
        $orders = $this->orderCrudService->getAllOrdersForAdminList();

        $dashboardMetrics = [
            'totalOrders' => $orders->count(),
            'todayOrders' => $orders->filter(
                fn (array $order): bool => $order['createdDate']?->isToday() === true
            )->count(),
            'openOrders' => $orders->filter(
                fn (array $order): bool => !in_array($order['status'], ['completed', 'cancelled'], true)
            )->count(),
            'bookedRevenue' => $orders->reject(
                fn (array $order): bool => in_array($order['status'], ['draft', 'cancelled'], true)
            )->sum('totalAmount'),
        ];

        return view('admin.dashboard', [
            'dashboardMetrics' => $dashboardMetrics,
            'recentOrders' => $orders->take(5)->values(),
        ]);
    }
}

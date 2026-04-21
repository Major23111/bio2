<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\AdminDashboardService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminDashboardController extends Controller
{
    public function __construct(protected AdminDashboardService $dashboardService)
    {
    }

    // This displays the admin dashboard with overview metrics and recent orders.
    public function index(): View
    {
        try {
            // Step 1: fetch quantitative metrics for the dashboard cards.
            $dashboardMetrics = $this->dashboardService->getDashboardStatistics();

            // Step 2: fetch the list of most recent orders for the dashboard table.
            $recentDashboardOrders = $this->dashboardService->getRecentDashboardOrders(5);

            // Step 3: return the dashboard view with prepared business data.
            return view('admin.dashboard', [
                'dashboardMetrics' => $dashboardMetrics,
                'recentOrders' => $recentDashboardOrders,
            ]);
        } catch (Throwable $exception) {
            // Step 4: log the failure and show an empty dashboard state if something goes wrong.
            Log::error('Failed to load admin dashboard.', [
                'errorMessage' => $exception->getMessage()
            ]);

            return view('admin.dashboard', [
                'dashboardMetrics' => [],
                'recentOrders' => collect([]),
            ]);
        }
    }
}

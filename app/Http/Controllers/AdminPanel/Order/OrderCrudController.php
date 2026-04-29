<?php

namespace App\Http\Controllers\AdminPanel\Order;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\Order\OrderCrudService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class OrderCrudController extends Controller
{
    public function __construct(protected OrderCrudService $orderCrudService)
    {
    }

    // Display orders list for admin panel.
    public function index(Request $request): View
    {
        try {
            // Get filter parameters from request
            $status = $request->query('status');
            $search = $request->query('search');
            $sort = $request->query('sort', 'date');
            $direction = $request->query('direction', 'desc');

            // Fetch all orders with filters applied.
            $orders = $this->orderCrudService->getAllOrdersForAdminList(
                perPage: 10,
                status: $status,
                search: $search,
                sort: $sort,
                direction: $direction
            );
        } catch (Throwable $exception) {
            // Return view with empty orders if error occurs.
            $orders = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.orders.index', [
            'orders' => $orders,
        ]);
    }

    // Display order details for viewing and editing.
    public function show(int $orderId): View
    {
        try {
            // Fetch order information for viewing.
            $order = $this->orderCrudService->getOrderForView($orderId);
        } catch (Throwable $exception) {
            // Abort with error when the page data cannot be loaded.
            abort(500);
        }

        abort_if(! $order, 404);

        $orderDetailsPage = view('admin.orders.details', [
            'order' => $order,
        ]);

        return $orderDetailsPage;
    }

    // Update order from form submission.
    public function update(Request $request, int $orderId): RedirectResponse
    {
        try {
            // Validate the order values submitted from the page.
            $validatedOrderDetails = $request->validate([
                'order_stage' => 'required|string|in:Order Received,Payment Received (Prepaid),Processing,Dispatched,Delivered,Payment Received (COD),Cancelled',
                'tracking_number' => 'nullable|string|max:100',
                'tracking_url' => 'nullable|url|max:255',
                'customer_name' => 'nullable|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:50',
                'shipping_address_text' => 'nullable|string|max:500',
                'notes' => 'nullable|string',
            ]);

            // Save the order changes in the service layer.
            $isOrderUpdated = $this->orderCrudService->updateOrder($orderId, $validatedOrderDetails);

            // Redirect back to the same order when save is successful.
            if ($isOrderUpdated) {
                $response = redirect()->route('admin.orders.view', [
                    'orderId' => $orderId,
                ])->with('success', 'Order changes have been saved successfully.');
            }

            // Redirect back with an error when the order is missing.
            if (! $isOrderUpdated) {
                $response = redirect()->back()
                    ->with('error', 'Order not found.');
            }
        } catch (Throwable $exception) {
            // Redirect back to form with error message.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update order. Please try again.');
        }

        return $response;
    }

    // AJAX endpoint to cancel an order.
    public function cancel(Request $request, int $orderId)
    {
        try {
            $this->orderCrudService->cancelOrder($orderId);
            return response()->json(['success' => true, 'message' => 'Order cancelled successfully.']);
        } catch (Throwable $exception) {
            return response()->json(['success' => false, 'message' => 'Failed to cancel order.'], 500);
        }
    }

    // Export orders to CSV based on current filters.
    public function exportCsv(Request $request)
    {
        try {
            $status = $request->query('status');
            $search = $request->query('search');
            $sort = $request->query('sort', 'date');
            $direction = $request->query('direction', 'desc');

            $fileName = 'orders_export_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($status, $search, $sort, $direction) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Order ID', 'Customer', 'Email', 'Date', 'Amount', 'Status']);

                // Fetch data in chunks to handle large datasets
                $this->orderCrudService->getAllOrdersForExport($status, $search, $sort, $direction)
                    ->chunk(100, function($orders) use($file) {
                        foreach ($orders as $order) {
                            fputcsv($file, [
                                '#ORD-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
                                $order->placedByUser?->name ?? 'Guest',
                                $order->placedByUser?->email ?? 'Not Available',
                                $order->created_at->format('Y-m-d H:i'),
                                $order->total_amount,
                                $order->status
                            ]);
                        }
                    });

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'CSV Export failed: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\AdminPanel\Order;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\Order\OrderCrudService;
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
    public function index(): View
    {
        try {
            // Fetch all orders with basic information.
            $orders = $this->orderCrudService->getAllOrdersForAdminList();

            // Return view with orders data.
            return view('admin.orders.index', [
                'orders' => $orders,
            ]);
        } catch (Throwable $exception) {
            // Return view with empty orders if error occurs.
            $orders = collect([]);

            return view('admin.orders.index', [
                'orders' => $orders,
            ]);
        }
    }

    // Display order details for viewing and editing.
    public function show(int $orderId): View
    {
        try {
            // Fetch order information for viewing.
            $order = $this->orderCrudService->getOrderForView($orderId);

            // Abort if order not found.
            if (!$order) {
                abort(404);
            }

            // Return view with order data.
            return view('admin.orders.details', [
                'order' => $order,
            ]);
        } catch (Throwable $exception) {
            // Abort with error if order cannot be fetched.
            abort(500);
        }
    }

    // Update order from form submission.
    public function update(Request $request, int $orderId): RedirectResponse
    {
        try {
            // Validate order information to update.
            $validated = $request->validate([
                'status' => 'required|string|max:50',
                'notes' => 'nullable|string',
            ]);

            // Update order record in database.
            $isUpdated = $this->orderCrudService->updateOrder($orderId, $validated);

            // Check if update was successful.
            if (!$isUpdated) {
                return redirect()->back()
                    ->with('error', 'Order not found.');
            }

            // Redirect to orders list with success message.
            return redirect()->route('admin.orders')
                ->with('success', 'Order updated successfully.');
        } catch (Throwable $exception) {
            // Redirect back to form with error message.
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update order. Please try again.');
        }
    }
}

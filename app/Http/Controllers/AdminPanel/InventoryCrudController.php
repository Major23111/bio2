<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\InventoryCrudService;
use Illuminate\Http\Request;

class InventoryCrudController extends Controller
{
    protected InventoryCrudService $inventoryService;

    public function __construct(InventoryCrudService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display the inventory management page.
     */
    public function index(Request $request)
    {
        $stats = $this->inventoryService->getInventoryStats();
        $categories = $this->inventoryService->getCategories();

        $items = $this->inventoryService->getInventoryItems(
            search: $request->input('search'),
            categoryFilter: $request->input('category'),
            statusFilter: $request->input('status'),
            perPage: 15,
        );

        // If AJAX request, return only the table partial
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'items' => $items,
            ]);
        }

        return view('admin.inventory.index', compact('stats', 'categories', 'items'));
    }

    /**
     * Update stock quantity for a variant via AJAX.
     */
    public function updateStock(Request $request, int $variantId)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0|max:999999',
        ]);

        try {
            $variant = $this->inventoryService->updateStock($variantId, $validated['stock_quantity']);

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully.',
                'variant' => [
                    'id' => $variant->id,
                    'stock_quantity' => $variant->stock_quantity,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock. ' . $e->getMessage(),
            ], 500);
        }
    }
}

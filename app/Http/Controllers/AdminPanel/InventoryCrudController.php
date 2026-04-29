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
     * Update stock quantity and tracking info for a variant via AJAX.
     */
    public function updateStock(Request $request, int $variantId)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0|max:999999',
            'pack_size' => 'nullable|string|max:255',
            'coa_no' => 'nullable|string|max:255',
            'batch_no' => 'nullable|string|max:255',
            'mfg_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:mfg_date',
        ]);

        try {
            $variant = $this->inventoryService->updateStock($variantId, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully.',
                'variant' => [
                    'id' => $variant->id,
                    'stock_quantity' => $variant->stock_quantity,
                    'pack_size' => $variant->pack_size,
                    'coa_no' => $variant->coa_no,
                    'batch_no' => $variant->batch_no,
                    'mfg_date' => $variant->mfg_date ? $variant->mfg_date->format('Y-m-d') : null,
                    'expiry_date' => $variant->expiry_date ? $variant->expiry_date->format('Y-m-d') : null,
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

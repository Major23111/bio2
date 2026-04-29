<?php

namespace App\Services\AdminPanel;

use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\Category;

class InventoryCrudService
{
    // Low stock threshold
    private const LOW_STOCK_THRESHOLD = 10;

    /**
     * Get inventory summary stats for the dashboard cards.
     */
    public function getInventoryStats(): array
    {
        $totalVariants = ProductVariant::where('is_active', true)->count();
        $availableCount = ProductVariant::where('is_active', true)->where('stock_quantity', '>', self::LOW_STOCK_THRESHOLD)->count();
        $lowStockCount = ProductVariant::where('is_active', true)->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', self::LOW_STOCK_THRESHOLD)->count();
        $outOfStockCount = ProductVariant::where('is_active', true)->where('stock_quantity', '<=', 0)->count();

        $availablePercent = $totalVariants > 0 ? round(($availableCount / $totalVariants) * 100) : 0;

        return [
            'totalVariants' => $totalVariants,
            'availableCount' => $availableCount,
            'availablePercent' => $availablePercent,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
        ];
    }

    /**
     * Get paginated inventory items for the table.
     */
    public function getInventoryItems(?string $search = null, ?string $categoryFilter = null, ?string $statusFilter = null, int $perPage = 15)
    {
        $query = ProductVariant::with(['product.category', 'product.primaryImage'])
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc');

        // Search by product name, SKU, or variant name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('variant_name', 'like', "%{$search}%")
                  ->orWhere('catalog_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('brand', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->whereHas('product', function ($q) use ($categoryFilter) {
                $q->where('category_id', $categoryFilter);
            });
        }

        // Filter by stock status
        if ($statusFilter) {
            switch ($statusFilter) {
                case 'available':
                    $query->where('stock_quantity', '>', self::LOW_STOCK_THRESHOLD);
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', self::LOW_STOCK_THRESHOLD);
                    break;
                case 'out_of_stock':
                    $query->where('stock_quantity', '<=', 0);
                    break;
            }
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all categories for the filter dropdown.
     */
    public function getCategories(): array
    {
        return Category::orderBy('name')->pluck('name', 'id')->toArray();
    }

    /**
     * Get stock status label and badge info for a variant.
     */
    public static function getStockStatus(int $quantity): array
    {
        if ($quantity <= 0) {
            return [
                'label' => 'OUT OF STOCK',
                'color' => 'rose',
                'dot' => 'bg-rose-500',
                'bg' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
            ];
        }

        if ($quantity <= self::LOW_STOCK_THRESHOLD) {
            return [
                'label' => 'LOW STOCK',
                'color' => 'amber',
                'dot' => 'bg-amber-500',
                'bg' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
            ];
        }

        return [
            'label' => 'AVAILABLE',
            'color' => 'emerald',
            'dot' => 'bg-emerald-500',
            'bg' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        ];
    }

    /**
     * Update stock quantity and tracking details for a specific variant.
     */
    public function updateStock(int $variantId, array $data): ProductVariant
    {
        $variant = ProductVariant::findOrFail($variantId);
        
        if (isset($data['stock_quantity'])) {
            $variant->stock_quantity = max(0, (int) $data['stock_quantity']);
        }
        
        if (array_key_exists('pack_size', $data)) {
            $variant->pack_size = $data['pack_size'];
        }
        
        if (array_key_exists('coa_no', $data)) {
            $variant->coa_no = $data['coa_no'];
        }
        
        if (array_key_exists('batch_no', $data)) {
            $variant->batch_no = $data['batch_no'];
        }
        
        if (array_key_exists('mfg_date', $data)) {
            $variant->mfg_date = $data['mfg_date'];
        }
        
        if (array_key_exists('expiry_date', $data)) {
            $variant->expiry_date = $data['expiry_date'];
        }
        
        $variant->save();

        return $variant;
    }
}

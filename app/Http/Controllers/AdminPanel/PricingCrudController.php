<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\PricingCrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class PricingCrudController extends Controller
{
    public function __construct(protected PricingCrudService $pricingCrudService)
    {
    }

    // Show the dedicated Map Pricing page for a single variant.
    public function showMapPricingForm(Request $request): View
    {
        $variantId = (int) $request->query('variant_id', 0);

        $variant = \App\Models\Product\ProductVariant::with([
            'product:id,name,sku,category_id',
            'product.category:id,name',
            'prices',
            'bulkPrices' => fn($q) => $q->where('is_active', true)->orderBy('min_quantity'),
        ])->findOrFail($variantId);

        // Load sibling variants (other pack sizes of the same product).
        $siblingVariants = \App\Models\Product\ProductVariant::where('product_id', $variant->product_id)
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id', 'variant_name', 'catalog_number']);

        // Extract current prices.
        $basePrice = $variant->prices->where('price_type', 'base')->where('is_active', true)->first();
        $b2cPrice  = $variant->prices->where('price_type', 'b2c')->where('is_active', true)->first();
        $b2bPrice  = $variant->prices->where('price_type', 'b2b')->where('is_active', true)->first();

        // Load company-specific pricing for this variant.
        $companyPrices = \App\Models\Product\ProductPrice::with(['company:id,name,company_type'])
            ->where('product_variant_id', $variantId)
            ->whereNotNull('company_id')
            ->where('is_active', true)
            ->get();

        // Load all products for dropdown (reuse existing service method).
        $allProductsForDropdown = $this->pricingCrudService->getAllProductsForDropdown();

        return view('admin.pricing.map-pricing', [
            'variant'              => $variant,
            'siblingVariants'      => $siblingVariants,
            'basePrice'            => $basePrice,
            'b2cPrice'             => $b2cPrice,
            'b2bPrice'             => $b2bPrice,
            'companyPrices'        => $companyPrices,
            'allProductsForDropdown' => $allProductsForDropdown,
        ]);
    }

    // Load the pricing management page with all section data from the database.
    public function index(): View
    {
        // Load products that already have pricing rows mapped.
        $mappedProducts = $this->pricingCrudService->getMappedProducts();

        // Load products that have no pricing rows yet.
        $unmappedProducts = $this->pricingCrudService->getUnmappedProducts();

        // Load bulk pricing table data — slab columns and per-product prices.
        $bulkPricingTable = $this->pricingCrudService->getBulkPricingTableData();

        // Load all products for the bulk pricing modal dropdown.
        $allProductsForDropdown = $this->pricingCrudService->getAllProductsForDropdown();

        $companyPricingList = $this->pricingCrudService->getCompanyPricingList();

        return view('admin.pricing.index', [
            'mappedProducts'        => $mappedProducts,
            'unmappedProducts'      => $unmappedProducts,
            'bulkPricingTable'      => $bulkPricingTable,
            'allProductsForDropdown' => $allProductsForDropdown,
            'companyPricingList'    => $companyPricingList,
        ]);
    }

    public function saveMappedPricing(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'variant_id' => 'required|integer|exists:product_variants,id',
                'base_price' => 'required|numeric|min:0',
                'b2c_percentage' => 'required|numeric|min:0',
                'b2b_price' => 'required|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0',
                'apply_discount_to' => 'nullable|string|in:B2C,B2B,Both B2C and B2B',
            ]);

            $this->pricingCrudService->saveMappedPricing(
                $validated['variant_id'],
                $validated['base_price'],
                $validated['b2c_percentage'],
                $validated['b2b_price'],
                $validated['discount_percentage'] ?? 0,
                $validated['apply_discount_to'] ?? 'B2C'
            );

            return redirect()->route('admin.pricing.index')->with('success', 'Pricing mapped successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Unable to map pricing: ' . $e->getMessage());
        }
    }

    public function updatePricing(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'variant_id' => 'required|integer|exists:product_variants,id',
                'base_price' => 'required|numeric|min:0',
                'b2c_percentage' => 'required|numeric|min:0',
                'b2b_price' => 'required|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0',
                'apply_discount_to' => 'nullable|string|in:B2C,B2B,Both B2C and B2B',
            ]);

            $this->pricingCrudService->updatePricing(
                $validated['variant_id'],
                $validated['base_price'],
                $validated['b2c_percentage'],
                $validated['b2b_price'],
                $validated['discount_percentage'] ?? 0,
                $validated['apply_discount_to'] ?? 'B2C'
            );

            return redirect()->route('admin.pricing.index')->with('success', 'Pricing updated successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Unable to update pricing: ' . $e->getMessage());
        }
    }

    public function saveCompanyPricing(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'company_id' => 'required|integer|exists:companies,id',
                'product_selection' => 'required|string|in:Apply to All,Specific Category,Single Product',
                'variant_id' => 'nullable|integer|exists:product_variants,id',
                'specific_b2b_price' => 'required|numeric|min:0',
                'exclusive_discount' => 'nullable|numeric|min:0',
                'bulk_slabs' => 'nullable|array',
            ]);

            $this->pricingCrudService->saveCompanyPricing(
                $validated['company_id'],
                $validated['product_selection'],
                $validated['variant_id'] ?? null,
                $validated['specific_b2b_price'],
                $validated['exclusive_discount'] ?? 0,
                $validated['bulk_slabs'] ?? []
            );

            return redirect()->route('admin.pricing.index')->with('success', 'Company pricing saved successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Unable to save company pricing: ' . $e->getMessage());
        }
    }

    // Save bulk pricing slabs submitted from the modal form.
    public function saveBulkPricingSlabs(Request $request): RedirectResponse
    {
        try {
            // Validate the variant id and the slabs array.
            $validatedData = $request->validate([
                'variant_id'             => 'required|integer|exists:product_variants,id',
                'slabs'                  => 'required|array|min:1',
                'slabs.*.min_quantity'   => 'required|integer|min:1',
                'slabs.*.amount'         => 'required|numeric|min:0.01',
            ]);

            // Save the slabs through the service layer.
            $this->pricingCrudService->saveBulkPricingSlabs(
                (int) $validatedData['variant_id'],
                $validatedData['slabs'],
            );

            $response = redirect()->route('admin.pricing.index')
                ->with('success', 'Bulk pricing slabs saved successfully.');
        } catch (Throwable $exception) {
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save bulk pricing slabs.');
        }

        return $response;
    }
}

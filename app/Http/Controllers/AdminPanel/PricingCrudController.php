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

        return view('admin.pricing.index', [
            'mappedProducts'        => $mappedProducts,
            'unmappedProducts'      => $unmappedProducts,
            'bulkPricingTable'      => $bulkPricingTable,
            'allProductsForDropdown' => $allProductsForDropdown,
        ]);
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

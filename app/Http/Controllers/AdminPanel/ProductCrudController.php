<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use App\Services\AdminPanel\ProductCrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProductCrudController extends Controller
{
    public function __construct(protected ProductCrudService $productCrudService)
    {
    }

    // This displays the main list of products with pagination and category data for filtering.
    public function index(Request $request): View
    {
        try {
            // Step 1: fetch all categories to populate the frontend filter pills.
            $categories = Category::orderBy('name')->get();

            // Step 2: fetch paginated products from the service.
            $products = $this->productCrudService->getAllProductsForAdminList();

            return view('admin.products.index', [
                'products' => $products,
                'categories' => $categories,
            ]);
        } catch (Throwable $exception) {
            // Step 3: fallback to empty state when list fetching fails.
            return view('admin.products.index', [
                'products' => collect([]),
                'categories' => collect([]),
            ]);
        }
    }

    // This shows the empty form for adding a new product to the catalog.
    public function create(): View
    {
        try {
            // Step 1: fetch all available categories for the dropdown selection.
            $categories = Category::orderBy('name')->get();

            return view('admin.products.create', [
                'categories' => $categories,
            ]);
        } catch (Throwable $exception) {
            return view('admin.products.create', [
                'categories' => collect([]),
            ]);
        }
    }

    // This processes the creation form, validates input, and saves the product master and files.
    public function store(Request $request): RedirectResponse
    {
        try {
            // Step 1: validate provided product info including file upload limits (max 3 images, 5MB each).
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:products,sku',
                'category_id' => 'required|integer|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'description' => 'required|string',
                'product_overview' => 'nullable|string',
                'gst_rate' => 'nullable|numeric',
                'visibility_scope' => 'required|string|in:public,b2b,b2c',
                'stock_quantity' => 'required|integer|min:0',
                'base_price' => 'required|numeric|min:0',
                'images' => 'nullable|array|max:3',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
                'documents' => 'nullable|array',
                'documents.*' => 'file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            ]);

            // Step 2: ensure logical boolean status for product visibility.
            $validated['is_active'] = $request->has('is_active');

            // Step 3: delegate heavy creation logic to the service layer.
            $this->productCrudService->createProduct($validated);

            return redirect()->route('admin.products')
                ->with('success', 'Product has been successfully added to catalog.');
        } catch (Throwable $exception) {
            // Step 4: redirect back with errors when creation fails.
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create product: ' . $exception->getMessage());
        }
    }

    // This shows the edit form populated with existing product database values.
    public function edit(int $productId): View
    {
        // Step 1: fetch full product data with images and variant details.
        $product = $this->productCrudService->getProductForEdit($productId);

        abort_if(!$product, 404);

        // Step 2: fetch all categories for the dropdown selection.
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    // This processes the edit form, updates product details, and handles new uploads.
    public function update(Request $request, int $productId): RedirectResponse
    {
        try {
            // Step 1: validate product updates ensuring SKU remains unique (except for current record).
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:products,sku,' . $productId,
                'category_id' => 'required|integer|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'description' => 'required|string',
                'product_overview' => 'nullable|string',
                'gst_rate' => 'nullable|numeric',
                'visibility_scope' => 'required|string|in:public,b2b,b2c',
                'stock_quantity' => 'required|integer|min:0',
                'base_price' => 'required|numeric|min:0',
                'images' => 'nullable|array|max:3',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
                'documents' => 'nullable|array',
                'documents.*' => 'file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
                'deleted_images' => 'nullable|array',
                'deleted_images.*' => 'integer|exists:product_image,id',
                'deleted_documents' => 'nullable|array',
                'deleted_documents.*' => 'integer|exists:product_technical_resources,id',
            ]);

            $validated['is_active'] = $request->has('is_active');

            // Step 2: call the service to update records and sync files.
            $isUpdated = $this->productCrudService->updateProduct($productId, $validated);

            if (!$isUpdated) {
                return redirect()->back()->with('error', 'Product not found.');
            }

            return redirect()->route('admin.products')
                ->with('success', 'Product details have been updated successfully.');
        } catch (Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Update failed: ' . $exception->getMessage());
        }
    }

    // This handles the request to remove a product and its associated files permanently.
    public function destroy(int $productId): RedirectResponse
    {
        try {
            // Step 1: call the service to delete records and physical media.
            $isDeleted = $this->productCrudService->deleteProduct($productId);

            if (!$isDeleted) {
                return redirect()->back()->with('error', 'Product not found or already deleted.');
            }

            return redirect()->route('admin.products')
                ->with('success', 'Product and its assets have been removed permanently.');
        } catch (Throwable $exception) {
            return redirect()->back()
                ->with('error', 'Deletion failed: ' . $exception->getMessage());
        }
    }
}

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

    // Display products list for admin panel.
    public function index(): View
    {
        try {
            // Fetch all products with basic information.
            $products = $this->productCrudService->getAllProductsForAdminList();

            // Return view with products data.
            return view('admin.products.index', [
                'products' => $products,
            ]);
        } catch (Throwable $exception) {
            // Log error and return empty list.
            $products = collect([]);

            return view('admin.products.index', [
                'products' => $products,
            ]);
        }
    }

    // Display create product form.
    public function create(): View
    {
        try {
            // Fetch all categories for dropdown selection.
            $categories = Category::orderBy('name')->get();

            // Return view with categories data.
            return view('admin.products.create', [
                'categories' => $categories,
            ]);
        } catch (Throwable $exception) {
            // Return view with empty categories.
            $categories = collect([]);

            return view('admin.products.create', [
                'categories' => $categories,
            ]);
        }
    }

    // Store new product from form submission.
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate required product information.
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:products',
                'category_id' => 'required|integer|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            // Set default active status if not provided.
            $validated['is_active'] = $request->boolean('is_active', true);

            // Create new product record in database.
            $productId = $this->productCrudService->createProduct($validated);

            // Redirect to products list with success message.
            return redirect()->route('admin.products')
                ->with('success', 'Product created successfully.');
        } catch (Throwable $exception) {
            // Redirect back to form with error message.
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product. Please try again.');
        }
    }

    // Display edit product form.
    public function edit(int $productId): View
    {
        try {
            // Fetch product information for editing.
            $product = $this->productCrudService->getProductForEdit($productId);

            // Abort if product not found.
            if (!$product) {
                abort(404);
            }

            // Fetch all categories for dropdown selection.
            $categories = Category::orderBy('name')->get();

            // Return view with product and categories data.
            return view('admin.products.edit', [
                'product' => $product,
                'categories' => $categories,
            ]);
        } catch (Throwable $exception) {
            // Abort with error if product cannot be fetched.
            abort(500);
        }
    }

    // Update product from form submission.
    public function update(Request $request, int $productId): RedirectResponse
    {
        try {
            // Validate product information to update.
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:products,sku,' . $productId,
                'category_id' => 'required|integer|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            // Set active status from form.
            $validated['is_active'] = $request->boolean('is_active', true);

            // Update product record in database.
            $isUpdated = $this->productCrudService->updateProduct($productId, $validated);

            // Check if update was successful.
            if (!$isUpdated) {
                return redirect()->back()
                    ->with('error', 'Product not found.');
            }

            // Redirect to products list with success message.
            return redirect()->route('admin.products')
                ->with('success', 'Product updated successfully.');
        } catch (Throwable $exception) {
            // Redirect back to form with error message.
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }
}

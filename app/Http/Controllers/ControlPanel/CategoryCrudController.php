<?php

namespace App\Http\Controllers\ControlPanel;

use App\Http\Controllers\Controller;
use App\Services\ControlPanel\CategoryCrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CategoryCrudController extends Controller
{
    public function __construct(protected CategoryCrudService $categoryCrudService)
    {
    }

    public function index(Request $request): View
    {
        try {
            // Read the selected category from the request.
            $selectedCategoryId = null;

            if ($request->filled('category_id')) {
                $selectedCategoryId = (int) $request->input('category_id');
            }

            // Get the category page data.
            $categoryPageData = $this->categoryCrudService->getCategoryPageData($selectedCategoryId);

            // Prepare the category values for the view.
            $categoryList = $categoryPageData['categoryList'];
            $selectedCategory = $categoryPageData['selectedCategory'];
        } catch (Throwable $exception) {
            // Keep the page open with empty data when loading fails.
            $categoryList = collect();
            $selectedCategory = null;
        }

        return view('admin.categories.index', [
            'categoryList' => $categoryList,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            // Read the category values from the request.
            $validatedCategoryData = $request->validate([
                'category_id' => 'required|integer|exists:categories,id',
                'hsm_code' => 'nullable|string|max:100',
                'application' => 'nullable|string|max:255',
                'gst_rate' => 'nullable|numeric|min:0|max:100',
            ]);

            // Save the current category values.
            $isCategorySaved = $this->categoryCrudService->updateCategoryDetails(
                (int) $validatedCategoryData['category_id'],
                $validatedCategoryData,
            );

            // Prepare the response for the same category page.
            $response = redirect()->route('admin.categories', [
                'category_id' => (int) $validatedCategoryData['category_id'],
            ]);

            // Show an error when the category record is not available.
            if (! $isCategorySaved) {
                $response = redirect()->route('admin.categories')
                    ->with('error', 'Selected category was not found.');
            }

            // Show the success message after saving.
            if ($isCategorySaved) {
                $response = $response->with('success', 'Category changes have been saved successfully.');
            }
        } catch (Throwable $exception) {
            // Return to the same page with the failure message.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save category changes: ' . $exception->getMessage());
        }

        return $response;
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            // Read the new category values from the request.
            $validatedCategoryData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string|max:255',
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            // Create the new category record.
            $newCategoryId = $this->categoryCrudService->createCategory($validatedCategoryData);

            // Return to the category page with the new record selected.
            $response = redirect()->route('admin.categories', [
                'category_id' => $newCategoryId,
            ])->with('success', 'Category has been created successfully.');
        } catch (Throwable $exception) {
            // Return to the same page with the failure message.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create category: ' . $exception->getMessage());
        }

        return $response;
    }
}

<?php

namespace App\Http\Controllers\ControlPanel;

use App\Http\Controllers\Controller;
use App\Services\ControlPanel\CategoryCrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        try {
            // Read the category values from the request.
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|integer|exists:categories,id',
                'hsm_code' => 'nullable|string|max:100',
                'application' => 'nullable|string|max:255',
                'gst_rate' => 'nullable|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Please check the category details.',
                        'errors' => $validator->errors(),
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validatedCategoryData = $validator->validated();

            // Save the current category values.
            $isCategorySaved = $this->categoryCrudService->updateCategoryDetails(
                (int) $validatedCategoryData['category_id'],
                $validatedCategoryData,
            );

            if ($request->expectsJson()) {
                if (! $isCategorySaved) {
                    return response()->json([
                        'message' => 'Selected category was not found.',
                    ], 404);
                }

                return response()->json([
                    'message' => 'Category changes have been saved successfully.',
                    'category' => [
                        'id' => (int) $validatedCategoryData['category_id'],
                        'hsm_code' => $validatedCategoryData['hsm_code'] ?? '',
                        'application' => $validatedCategoryData['application'] ?? '',
                        'gst_rate' => number_format((float) ($validatedCategoryData['gst_rate'] ?? 0), 2, '.', ''),
                    ],
                ]);
            }

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
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unable to save category changes: ' . $exception->getMessage(),
                ], 500);
            }

            // Return to the same page with the failure message.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save category changes: ' . $exception->getMessage());
        }

        return $response;
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            // Read the new category values from the request.
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string|max:255',
                'hsm_code' => 'nullable|string|max:100',
                'IsDisplayedOnHomePage' => 'nullable|boolean',
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Please check the new category details.',
                        'errors' => $validator->errors(),
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validatedCategoryData = $validator->validated();

            // Create the new category record.
            $newCategoryId = $this->categoryCrudService->createCategory($validatedCategoryData);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Category has been created successfully.',
                    'category' => [
                        'id' => $newCategoryId,
                        'name' => $validatedCategoryData['name'],
                        'hsm_code' => $validatedCategoryData['hsm_code'] ?? '',
                        'application' => '',
                        'gst_rate' => '18.00',
                    ],
                ], 201);
            }

            // Return to the category page with the new record selected.
            $response = redirect()->route('admin.categories', [
                'category_id' => $newCategoryId,
            ])->with('success', 'Category has been created successfully.');
        } catch (Throwable $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unable to create category: ' . $exception->getMessage(),
                ], 500);
            }

            // Return to the same page with the failure message.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create category: ' . $exception->getMessage());
        }

        return $response;
    }
}

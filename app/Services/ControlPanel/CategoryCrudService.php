<?php

namespace App\Services\ControlPanel;

use App\Models\Product\Category;
use App\Services\Utility\FileHandlingService;
use Illuminate\Support\Str;

class CategoryCrudService
{
    public function __construct(protected FileHandlingService $fileHandlingService)
    {
    }

    public function getCategoryPageData(?int $selectedCategoryId): array
    {
        // Load the category list for the page.
        $categoryList = Category::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'description',
                'application',
                'hsm_code',
                'slug',
                'gst_rate',
            ]);

        // Start with no selected category.
        $selectedCategory = null;

        // Pick the requested category when it exists.
        if ($selectedCategoryId !== null) {
            $selectedCategory = $categoryList->firstWhere('id', $selectedCategoryId);
        }

        // Pick the first category when no category is selected.
        if (!$selectedCategory) {
            $selectedCategory = $categoryList->first();
        }

        // Prepare the page data for the controller.
        $categoryPageData = [];
        $categoryPageData['categoryList'] = $categoryList;
        $categoryPageData['selectedCategory'] = $selectedCategory;

        return $categoryPageData;
    }

    public function updateCategoryDetails(int $categoryId, array $categoryData): bool
    {
        // Load the selected category record.
        $selectedCategory = Category::query()->find($categoryId);

        // Stop when the category does not exist.
        if (! $selectedCategory) {
            return false;
        }

        // Read the current form values.
        $categoryHsmCode = $categoryData['hsm_code'] ?? null;
        $categoryApplication = $categoryData['application'] ?? null;
        $categoryGstRate = $categoryData['gst_rate'] ?? $selectedCategory->gst_rate;

        // Save the updated values.
        $selectedCategory->hsm_code = $categoryHsmCode;
        $selectedCategory->application = $categoryApplication;
        $selectedCategory->gst_rate = $categoryGstRate;
        $selectedCategory->save();

        return true;
    }

    public function createCategory(array $categoryData): int
    {
        // Read the current category name.
        $categoryName = trim((string) $categoryData['name']);

        // Build the first slug value from the name.
        $baseCategorySlug = Str::slug($categoryName);

        // Keep a fallback slug when the name does not produce one.
        if ($baseCategorySlug === '') {
            $baseCategorySlug = 'category';
        }

        // Start with the base slug value.
        $finalCategorySlug = $baseCategorySlug;
        $slugNumber = 1;

        // Make the slug unique before saving.
        while (Category::query()->where('slug', $finalCategorySlug)->exists()) {
            $finalCategorySlug = $baseCategorySlug . '-' . $slugNumber;
            $slugNumber++;
        }

        // Read the next sort order for the new category.
        $highestSortOrder = (int) Category::query()->max('sort_order');
        $nextSortOrder = $highestSortOrder + 1;

        // Start without an image path.
        $categoryImagePath = null;

        // Store the uploaded category image when it is available.
        if (! empty($categoryData['category_image'])) {
            $categoryImagePath = $this->fileHandlingService->storeUploadedFile(
                $categoryData['category_image'],
                FileHandlingService::CATEGORY_IMAGE_DIRECTORY,
                $finalCategorySlug,
            );
        }

        // Create the new category record.
        $newCategory = Category::query()->create([
            'name' => $categoryName,
            'description' => $categoryData['description'] ?? null,
            'application' => null,
            'hsm_code' => null,
            'slug' => $finalCategorySlug,
            'default_image_path' => $categoryImagePath,
            'gst_rate' => 18.00,
            'sort_order' => $nextSortOrder,
        ]);

        return (int) $newCategory->id;
    }
}

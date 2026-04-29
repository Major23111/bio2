<?php

namespace App\Services\ControlPanel;

use App\Models\Product\Category;
use App\Services\Utility\FileHandlingService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategoryCrudService
{
    public function __construct(protected FileHandlingService $fileHandlingService)
    {
    }

    public function getCategoryPageData(?int $selectedCategoryId): array
    {
        $this->ensureCategoryAdminColumns();

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
        $this->ensureCategoryAdminColumns();

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
        $this->ensureCategoryAdminColumns();

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
            'hsm_code' => $categoryData['hsm_code'] ?? null,
            'slug' => $finalCategorySlug,
            'default_image_path' => $categoryImagePath,
            'IsDisplayedOnHomePage' => (bool) ($categoryData['IsDisplayedOnHomePage'] ?? false),
            'gst_rate' => 18.00,
            'sort_order' => $nextSortOrder,
        ]);

        return (int) $newCategory->id;
    }

    private function ensureCategoryAdminColumns(): void
    {
        if (! Schema::hasTable('categories')) {
            return;
        }

        $missingColumns = [
            'description' => ! Schema::hasColumn('categories', 'description'),
            'application' => ! Schema::hasColumn('categories', 'application'),
            'hsm_code' => ! Schema::hasColumn('categories', 'hsm_code'),
            'IsDisplayedOnHomePage' => ! Schema::hasColumn('categories', 'IsDisplayedOnHomePage'),
            'default_image_path' => ! Schema::hasColumn('categories', 'default_image_path'),
            'gst_rate' => ! Schema::hasColumn('categories', 'gst_rate'),
            'sort_order' => ! Schema::hasColumn('categories', 'sort_order'),
            'created_at' => ! Schema::hasColumn('categories', 'created_at'),
            'updated_at' => ! Schema::hasColumn('categories', 'updated_at'),
        ];

        if (! in_array(true, $missingColumns, true)) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) use ($missingColumns): void {
            if ($missingColumns['description']) {
                $table->string('description')->nullable();
            }

            if ($missingColumns['application']) {
                $table->string('application')->nullable();
            }

            if ($missingColumns['hsm_code']) {
                $table->string('hsm_code')->nullable();
            }

            if ($missingColumns['IsDisplayedOnHomePage']) {
                $table->boolean('IsDisplayedOnHomePage')->default(false);
            }

            if ($missingColumns['default_image_path']) {
                $table->string('default_image_path')->nullable();
            }

            if ($missingColumns['gst_rate']) {
                $table->decimal('gst_rate', 5, 2)->default(18.00);
            }

            if ($missingColumns['sort_order']) {
                $table->unsignedInteger('sort_order')->default(0);
            }

            if ($missingColumns['created_at']) {
                $table->timestamp('created_at')->nullable();
            }

            if ($missingColumns['updated_at']) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }
}

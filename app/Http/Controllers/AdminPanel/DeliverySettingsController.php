<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\DeliverySettingsService;
use Illuminate\Http\Request;

class DeliverySettingsController extends Controller
{
    protected DeliverySettingsService $deliverySettingsService;

    public function __construct(DeliverySettingsService $deliverySettingsService)
    {
        $this->deliverySettingsService = $deliverySettingsService;
    }

    /**
     * Display the delivery & logistics settings page.
     */
    public function index()
    {
        try {
            $rateCards = $this->deliverySettingsService->getSettingsForAdminView();
        } catch (\Throwable $e) {
            // Fallback to hardcoded defaults if DB is not yet migrated
            $rateCards = [
                [
                    'badge' => 'Regional',
                    'badge_classes' => 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100',
                    'title' => 'PAN India (Excluding Lucknow)',
                    'description' => 'Define the standard flat-rate delivery fee applicable across all operational Indian states and union territories, with the specific exclusion of the Lucknow city limits.',
                    'label' => 'Base Delivery Rate (INR)',
                    'field' => 'pan_india_rate',
                    'value' => '150.00',
                    'helper' => 'This rate will be applied automatically at checkout for all non-local zip codes.',
                    'icon' => 'regional',
                ],
                [
                    'badge' => 'Hyperlocal',
                    'badge_classes' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
                    'title' => 'Lucknow Local Delivery',
                    'description' => 'Specific logistical pricing for last-mile delivery within the Lucknow municipal boundaries. This rate overrides the standard PAN India configuration.',
                    'label' => 'Local Delivery Rate (INR)',
                    'field' => 'lucknow_rate',
                    'value' => '40.00',
                    'helper' => 'Zip code validation (226xxx) is required for this rate activation.',
                    'icon' => 'local',
                ],
            ];
        }

        return view('admin.delivery-logistics', compact('rateCards'));
    }

    /**
     * Save delivery rate settings via AJAX.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'pan_india_rate' => 'required|numeric|min:0|max:99999.99',
            'lucknow_rate'   => 'required|numeric|min:0|max:99999.99',
        ]);

        try {
            $this->deliverySettingsService->saveRates($validated, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Delivery configuration saved successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save delivery configuration. ' . $e->getMessage(),
            ], 500);
        }
    }
}

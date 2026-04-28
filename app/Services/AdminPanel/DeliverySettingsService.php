<?php

namespace App\Services\AdminPanel;

use App\Models\DeliverySetting;

class DeliverySettingsService
{
    /**
     * Get all delivery rate settings as a key => value map.
     */
    public function getAllRates(): array
    {
        return DeliverySetting::getAllRates();
    }

    /**
     * Get full delivery setting records for the admin view.
     */
    public function getSettingsForAdminView(): array
    {
        $rates = $this->getAllRates();

        return [
            [
                'badge' => 'Regional',
                'badge_classes' => 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100',
                'title' => 'PAN India (Excluding Lucknow)',
                'description' => 'Define the standard flat-rate delivery fee applicable across all operational Indian states and union territories, with the specific exclusion of the Lucknow city limits.',
                'label' => 'Base Delivery Rate (INR)',
                'field' => 'pan_india_rate',
                'value' => number_format($rates['pan_india_rate'] ?? 150.00, 2, '.', ''),
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
                'value' => number_format($rates['lucknow_rate'] ?? 40.00, 2, '.', ''),
                'helper' => 'Zip code validation (226xxx) is required for this rate activation.',
                'icon' => 'local',
            ],
        ];
    }

    /**
     * Persist delivery rate changes.
     */
    public function saveRates(array $validatedRates, int $userId): void
    {
        $allowed = ['pan_india_rate', 'lucknow_rate'];
        $filtered = array_intersect_key($validatedRates, array_flip($allowed));

        DeliverySetting::saveRates($filtered, $userId);
    }
}

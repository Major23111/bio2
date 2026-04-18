<?php

namespace App\Services\ContactUs;

use App\Models\ContactUs\ContactUsEnquiry;
use App\Models\ContactUs\EnquiryType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactUsService
{
    // This prepares the contact page data used by the view.
    public function contactPageData(): array
    {
        return [
            'enquiryTypes' => $this->activeEnquiryTypes(),
        ];
    }

    // This loads the active enquiry types in business display order.
    public function activeEnquiryTypes(): Collection
    {
        return EnquiryType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    // This returns only the active enquiry type ids used for safe form validation.
    public function activeEnquiryTypeIds(): array
    {
        return $this->activeEnquiryTypes()
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all();
    }

    // This saves one contact enquiry row from the public contact page.
    public function createEnquiry(array $validated): int
    {
        // Business step: save the enquiry in one simple row so the team can start follow-up quickly.
        $contactUsEnquiry = ContactUsEnquiry::query()->create([
            'enquiry_type_id' => (int) $validated['enquiry_type_id'],
            'full_name' => trim((string) $validated['full_name']),
            'email' => trim((string) $validated['email']),
            'phone' => trim((string) $validated['phone']),
            'message' => trim((string) $validated['message']),
            'status' => 'new',
            'submitted_at' => now(),
        ]);

        Log::info('Contact enquiry saved successfully.', [
            'contact_us_enquiry_id' => $contactUsEnquiry->id,
            'enquiry_type_id' => $contactUsEnquiry->enquiry_type_id,
            'email' => $contactUsEnquiry->email,
        ]);

        return (int) $contactUsEnquiry->id;
    }
}

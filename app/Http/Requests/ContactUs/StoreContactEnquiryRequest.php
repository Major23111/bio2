<?php

namespace App\Http\Requests\ContactUs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contactUsService = app(\App\Services\ContactUs\ContactUsService::class);
        $activeEnquiryTypeIds = $contactUsService->activeEnquiryTypeIds();

        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'digits:10'],
            'enquiry_type_id' => ['required', Rule::in($activeEnquiryTypeIds)],
            'message' => ['required', 'string', 'max:500'],
        ];
    }
}

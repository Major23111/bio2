<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        // Call the service to get dynamic rules based on user type
        $profileService = app(\App\Services\Profile\ProfileService::class);
        return $profileService->profileValidationRules($this->user());
    }
}

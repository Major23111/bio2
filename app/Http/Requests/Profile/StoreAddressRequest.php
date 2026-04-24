<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

// This form request handles validation for creating a new customer address.
class StoreAddressRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize(): bool
    {
        // Authorization is handled at the controller level.
        return true;
    }

    // Get the validation rules that apply to the request.
    public function rules(): array
    {
        return [
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:128'],
            'state' => ['required', 'string', 'max:128'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:128'],
            'is_default_shipping' => ['nullable', 'boolean'],
            'is_default_billing' => ['nullable', 'boolean'],
        ];
    }

    // Get custom attribute names for validation error messages.
    public function attributes(): array
    {
        return [
            'line1' => 'Address Line 1',
            'line2' => 'Address Line 2',
            'city' => 'City',
            'state' => 'State',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
            'is_default_shipping' => 'Default Shipping Address',
            'is_default_billing' => 'Default Billing Address',
        ];
    }
}

<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitCheckoutOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = (int) $this->user()->id;

        return [
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'selected_address_source' => ['required', 'string', Rule::in(['existing', 'new'])],
            'selected_user_address_id' => [
                'nullable',
                'integer',
                'required_if:selected_address_source,existing',
                Rule::exists('user_address', 'id')->where('user_id', $userId),
            ],
            'new_address_label' => ['nullable', 'string', 'max:255', 'required_if:selected_address_source,new'],
            'new_address_line1' => ['nullable', 'string', 'max:255', 'required_if:selected_address_source,new'],
            'new_address_city' => ['nullable', 'string', 'max:128', 'required_if:selected_address_source,new'],
            'new_address_state' => ['nullable', 'string', 'max:128', 'required_if:selected_address_source,new'],
            'new_address_postal_code' => ['nullable', 'string', 'max:20', 'required_if:selected_address_source,new'],
            'new_address_country' => ['nullable', 'string', 'max:128'],
            'new_address_phone' => ['nullable', 'string', 'max:32'],
            'gstin' => ['nullable', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'registered_business_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

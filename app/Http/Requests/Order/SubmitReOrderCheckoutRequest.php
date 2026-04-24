<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class SubmitReOrderCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'reorder_items' => ['required', 'string'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'selected_address_source' => ['required', 'string', 'in:existing,new'],
            'selected_user_address_id' => ['nullable', 'integer'],
            'new_address_label' => ['nullable', 'string', 'max:255'],
            'new_address_line1' => ['nullable', 'string', 'max:255'],
            'new_address_city' => ['nullable', 'string', 'max:128'],
            'new_address_state' => ['nullable', 'string', 'max:128'],
            'new_address_postal_code' => ['nullable', 'string', 'max:20'],
            'new_address_country' => ['nullable', 'string', 'max:128'],
            'new_address_phone' => ['nullable', 'string', 'max:32'],
            'gstin' => ['nullable', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'registered_business_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

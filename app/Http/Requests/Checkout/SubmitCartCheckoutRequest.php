<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCartCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'shipping_amount' => ['nullable', 'numeric', 'min:0'],
            'adjustment_amount' => ['nullable', 'numeric'],
            'rounding_amount' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

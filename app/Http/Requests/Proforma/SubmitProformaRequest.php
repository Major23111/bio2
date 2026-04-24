<?php

namespace App\Http\Requests\Proforma;

use Illuminate\Foundation\Http\FormRequest;

class SubmitProformaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['nullable', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'array', 'min:1'],
            'quantity.*' => ['nullable', 'integer', 'min:1'],
            'purpose' => ['required', 'in:self,other'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'target_company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

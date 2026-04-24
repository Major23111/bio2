<?php

namespace App\Http\Requests\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class UpsertRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }
}

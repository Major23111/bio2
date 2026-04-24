<?php

namespace App\Http\Requests\Quize;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_type' => ['required', 'string', 'in:common,doctor,lab,dealer,other'],
            'participant_first_name' => ['required', 'string', 'max:100'],
            'participant_last_name' => ['nullable', 'string', 'max:100'],
            'participant_email' => ['required', 'email', 'max:150'],
            'selected_answers' => ['required', 'array', 'min:1'],
            'selected_answers.*' => ['required', 'integer'],
        ];
    }
}

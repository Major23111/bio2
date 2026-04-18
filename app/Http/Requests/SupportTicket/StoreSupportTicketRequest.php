<?php

namespace App\Http\Requests\SupportTicket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupportTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $supportTicketService = app(\App\Services\SupportTicket\SupportTicketService::class);
        $categorySlugs = $supportTicketService->availableCategorySlugs();

        return [
            'subject' => ['required', 'string', 'max:150'],
            'category' => ['required', Rule::in($categorySlugs)],
            'priority' => ['nullable', Rule::in($supportTicketService::PRIORITIES)],
            'description' => ['required', 'string', 'max:4000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:5120'],
            'support_ticket_form_source' => ['nullable', 'string', 'max:50'],
        ];
    }
}

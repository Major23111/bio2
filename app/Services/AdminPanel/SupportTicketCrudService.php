<?php

namespace App\Services\AdminPanel;

use App\Models\SupportTicket\SupportTicket;
use Illuminate\Pagination\LengthAwarePaginator;

class SupportTicketCrudService
{
    // Retrieve all active support tickets with minimal pagination
    public function getActiveTickets(): LengthAwarePaginator
    {
        $activeTickets = SupportTicket::query()
            ->with(['ownerUser:id,first_name,last_name'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return $activeTickets;
    }

    // Update the priority of a specific ticket
    public function updateTicketPriority(int $ticketId, string $newPriority): bool
    {
        $ticketRecord = SupportTicket::findOrFail($ticketId);
        $ticketRecord->priority = $newPriority;
        $isSaved = $ticketRecord->save();

        return $isSaved;
    }

    // Update the status of a specific ticket
    public function updateTicketStatus(int $ticketId, string $newStatus): bool
    {
        $ticketRecord = SupportTicket::findOrFail($ticketId);
        $ticketRecord->status = $newStatus;
        $isSaved = $ticketRecord->save();

        return $isSaved;
    }
}

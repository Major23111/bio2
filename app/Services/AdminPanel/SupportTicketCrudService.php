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

    // Retrieve details for a specific ticket including owner and comments
    public function getTicketDetails(int $ticketId): SupportTicket
    {
        return SupportTicket::with([
            'ownerUser:id,first_name,last_name',
            'comments' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'comments.commenter:id,first_name,last_name,role_id' // You can select what you need
        ])->findOrFail($ticketId);
    }

    // Add a comment to a ticket
    public function addTicketComment(int $ticketId, int $userId, string $commentText)
    {
        $ticketRecord = SupportTicket::findOrFail($ticketId);
        
        $comment = $ticketRecord->comments()->create([
            'commenter_user_id' => $userId,
            'comment' => $commentText,
        ]);
        
        // Update last activity
        $ticketRecord->last_activity_at = now();
        $ticketRecord->save();
        
        // Return the newly created comment loaded with commenter details
        return $comment->load('commenter:id,first_name,last_name');
    }
}

<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\SupportTicketCrudService;
use Illuminate\Http\Request;
use Exception;

class SupportTicketCrudController extends Controller
{
    private SupportTicketCrudService $supportTicketCrudService;

    public function __construct(SupportTicketCrudService $supportTicketCrudService)
    {
        $this->supportTicketCrudService = $supportTicketCrudService;
    }

    // Load active tickets and render the inbox view
    public function index()
    {
        try {
            $ticketList = $this->supportTicketCrudService->getActiveTickets();

            return view('admin.support-tickets.index', [
                'ticketList' => $ticketList
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'Unable to load support tickets at this time.');
        }
    }

    // Update ticket priority via AJAX
    public function updatePriority(Request $request, int $ticketId)
    {
        try {
            $newPriorityName = $request->input('priority');
            $this->supportTicketCrudService->updateTicketPriority($ticketId, $newPriorityName);

            return response()->json([
                'success' => true,
                'message' => 'Priority updated successfully.'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket priority.'
            ], 500);
        }
    }

    // Update ticket status via AJAX
    public function updateStatus(Request $request, int $ticketId)
    {
        try {
            $newStatusName = $request->input('status');
            $this->supportTicketCrudService->updateTicketStatus($ticketId, $newStatusName);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket status.'
            ], 500);
        }
    }

    // Get details of a ticket including comments
    public function getDetails(int $ticketId)
    {
        try {
            $ticket = $this->supportTicketCrudService->getTicketDetails($ticketId);
            return response()->json([
                'success' => true,
                'ticket' => $ticket
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load ticket details.'
            ], 500);
        }
    }

    // Add a comment to a ticket
    public function addComment(Request $request, int $ticketId)
    {
        try {
            $validated = $request->validate([
                'comment' => 'required|string|max:5000'
            ]);

            // Assuming user is authenticated and we can get their ID
            // For now, we'll try auth()->id(), fallback to 1 if not authenticated (for testing)
            $userId = auth()->id() ?? 1;

            $comment = $this->supportTicketCrudService->addTicketComment($ticketId, $userId, $validated['comment']);

            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => 'Comment added successfully.'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $exception->getMessage()
            ], 500);
        }
    }
}

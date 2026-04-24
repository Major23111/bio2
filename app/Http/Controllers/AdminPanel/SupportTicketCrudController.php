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
}

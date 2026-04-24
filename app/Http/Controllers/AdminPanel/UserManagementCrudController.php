<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\UserManagementCrudService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class UserManagementCrudController extends Controller
{
    private UserManagementCrudService $userManagementCrudService;

    public function __construct(UserManagementCrudService $userManagementCrudService)
    {
        $this->userManagementCrudService = $userManagementCrudService;
    }

    /**
     * Display the User Management Customers Index page.
     */
    public function index()
    {
        try {
            // Fetch Pending Approvals array.
            $pendingVerifications = $this->userManagementCrudService->getPendingVerifications();
            
            // Fetch verified Active lists
            $verifiedCustomers = $this->userManagementCrudService->getCustomersDashboardList();
            
            // Fetch specific B2B targeted
            $b2bSpecificList = $this->userManagementCrudService->getB2BCustomersList();

            return view('admin.customers.index', [
                'pendingVerifications' => $pendingVerifications,
                'verifiedCustomers' => $verifiedCustomers,
                'b2bSpecificList' => $b2bSpecificList,
            ]);
        } catch (Exception $exception) {
            Log::error('Error loading Customer Index: ' . $exception->getMessage());
            return back()->with('error', 'Unable to load customer management dashboard.');
        }
    }

    /**
     * Display the paginated Customer Directory.
     */
    public function directory(Request $request)
    {
        try {
            $categoryFilter = $request->query('category');
            $statusFilter = $request->query('status');
            $searchKeyword = $request->query('search');

            $paginatedCustomers = $this->userManagementCrudService->getPaginatedDirectory($categoryFilter, $statusFilter, $searchKeyword);

            return view('admin.customers.directory', [
                'customers' => $paginatedCustomers,
                'categoryFilter' => $categoryFilter,
                'statusFilter' => $statusFilter,
                'searchKeyword' => $searchKeyword
            ]);
        } catch (Exception $exception) {
            Log::error('Error loading Customer Directory: ' . $exception->getMessage());
            return back()->with('error', 'Unable to load customer directory.');
        }
    }

    /**
     * Display specific Customer Details Interface.
     */
    public function details(int $customerId)
    {
        try {
            $customerRecord = $this->userManagementCrudService->getCustomerDetails($customerId);
            
            return view('admin.customers.details', [
                'customer' => $customerRecord
            ]);
        } catch (Exception $exception) {
            Log::error('Error loading Customer Details: ' . $exception->getMessage());
            return redirect()->route('admin.customer-directory')->with('error', 'Customer record not found.');
        }
    }

    /**
     * AJAX Endpoint to Approve Pending Validations.
     */
    public function approvePending(Request $request)
    {
        try {
            $userIdToApprove = $request->input('user_id');
            $creditLimit = $request->input('credit_limit');
            $creditDays = $request->input('credit_days');
            $unlimitedCredit = filter_var($request->input('unlimited_credit', false), FILTER_VALIDATE_BOOL);

            $this->userManagementCrudService->approveVerification(
                $userIdToApprove,
                $creditLimit,
                $creditDays,
                $unlimitedCredit
            );

            return response()->json(['success' => true, 'message' => 'Customer account successfully approved.']);
        } catch (Exception $exception) {
            Log::error('Error Approving Customer: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to approve customer account.'], 500);
        }
    }

    /**
     * AJAX Endpoint to Reject Pending Validations.
     */
    public function rejectPending(Request $request)
    {
        try {
            $userIdToReject = $request->input('user_id');

            $this->userManagementCrudService->rejectVerification($userIdToReject);

            return response()->json(['success' => true, 'message' => 'Customer account rejected.']);
        } catch (Exception $exception) {
            Log::error('Error Rejecting Customer: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to reject customer account.'], 500);
        }
    }

    /**
     * AJAX Endpoint to Save specific Customer Details modifications.
     */
    public function updateDetails(Request $request, int $customerId)
    {
        try {
            $updateDataPayload = [
                'internal_admin_notes' => $request->input('internal_admin_notes'),
                'status' => $request->input('status'),
                'user_type' => $request->input('user_type'),
            ];

            if ($request->input('user_type') === 'B2B' || $request->input('user_type') === 'b2b') {
                $updateDataPayload['credit_limit'] = $request->input('credit_limit');
                $updateDataPayload['credit_days'] = $request->input('credit_days');
                $updateDataPayload['unlimited_credit'] = filter_var($request->input('unlimited_credit', false), FILTER_VALIDATE_BOOL);
            }

            $this->userManagementCrudService->updateCustomerDetails($customerId, $updateDataPayload);

            return response()->json(['success' => true, 'message' => 'Customer details saved successfully.']);
        } catch (Exception $exception) {
            Log::error('Error Updating Customer Details: ' . $exception->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to persist customer details modifications.'], 500);
        }
    }
}

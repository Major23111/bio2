<?php

namespace App\Services\AdminPanel;

use App\Models\Authorization\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserManagementCrudService
{
    /**
     * Get users with 'pending' status.
     */
    public function getPendingVerifications()
    {
        return User::query()
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get the list of all customers, formatted logic for the general active state.
     */
    public function getCustomersDashboardList($limit = 8)
    {
        return User::query()
            ->whereIn('status', ['Active', 'Suspended', 'Inactive', 'active', 'inactive', 'suspended'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Retrieve all B2B categorized customers for the Quick Access dropdown.
     */
    public function getB2BCustomersList()
    {
        return User::query()
            ->where('user_type', 'b2b')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'user_type', 'status', 'created_at']);
    }

    /**
     * Full customer directory paginated logic.
     */
    public function getPaginatedDirectory(string $category = null, string $status = null, string $search = null): LengthAwarePaginator
    {
        $query = User::query()->orderBy('created_at', 'desc');

        if (!empty($category)) {
            $query->where('user_type', strtolower($category));
        }

        if (!empty($status)) {
            $query->where('status', strtolower($status));
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('b2b_type', 'like', "%{$search}%");
            });
        }

        return $query->paginate(30);
    }

    /**
     * Fetch a distinct customer securely by specific Customer ID.
     */
    public function getCustomerDetails(int $customerId): User
    {
        return User::findOrFail($customerId);
    }

    /**
     * Process verification approval transitioning account correctly.
     */
    public function approveVerification(int $userId, ?float $creditLimit, ?int $creditDays, bool $unlimitedCredit): User
    {
        $user = User::findOrFail($userId);
        
        $user->status = 'Active';
        $user->approved_at = now();
        $user->approved_by_user_id = auth()->id();

        // B2B processing logic limits
        if ($user->user_type === 'b2b' || $user->user_type === 'B2B') {
            $user->credit_limit = $creditLimit;
            $user->credit_days = $creditDays;
            $user->unlimited_credit = $unlimitedCredit;
        }

        $user->save();

        return $user;
    }

    /**
     * Process rejection effectively moving to inactive status.
     */
    public function rejectVerification(int $userId): User
    {
        $user = User::findOrFail($userId);
        
        $user->status = 'Rejected';
        $user->save();

        return $user;
    }

    /**
     * Update customer administrative attributes.
     */
    public function updateCustomerDetails(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);

        if (array_key_exists('internal_admin_notes', $data)) {
            $user->internal_admin_notes = $data['internal_admin_notes'];
        }
        
        if (array_key_exists('status', $data)) {
            $user->status = $data['status'];
        }
        
        if (array_key_exists('user_type', $data)) {
            $user->user_type = strtolower($data['user_type']);
        }
        
        if ($user->user_type === 'b2b' || $user->user_type === 'B2B') {
            if (array_key_exists('credit_limit', $data)) {
                $user->credit_limit = $data['credit_limit'] === '' ? null : $data['credit_limit'];
            }
            if (array_key_exists('credit_days', $data)) {
                $user->credit_days = $data['credit_days'] === '' ? null : $data['credit_days'];
            }
            if (array_key_exists('unlimited_credit', $data)) {
                $user->unlimited_credit = $data['unlimited_credit'];
            }
        }

        $user->save();

        return $user;
    }
}

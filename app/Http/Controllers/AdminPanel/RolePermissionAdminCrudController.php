<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\RolePermissionAdminCrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class RolePermissionAdminCrudController extends Controller
{
    public function __construct(protected RolePermissionAdminCrudService $rolePermissionAdminCrudService)
    {
    }

    // Display the role permission page with backend data.
    public function index(Request $request): View
    {
        try {
            // Read the selected role from the current page query.
            $selectedRoleId = $request->integer('role_id');

            // Load the full page data from the service.
            $pageData = $this->rolePermissionAdminCrudService->getRolePermissionPageData($selectedRoleId);
        } catch (Throwable $exception) {
            // Prepare a safe empty page when loading fails.
            $pageData = [];
            $pageData['stats'] = [];
            $pageData['roles'] = collect();
            $pageData['selectedRole'] = null;
            $pageData['permissionGroups'] = [];
            $pageData['users'] = collect();
            $pageData['departments'] = collect();
            $pageData['overrides'] = [];
            $pageData['delegates'] = [];
            $pageData['impersonations'] = [];
            $pageData['permissions'] = collect();
        }

        $rolePermissionPage = view('admin.RolePermissions.index', $pageData);

        return $rolePermissionPage;
    }

    // Save a new role from the page modal.
    public function storeRole(Request $request): RedirectResponse
    {
        try {
            // Validate the submitted role values.
            $validatedRoleData = $request->validate([
                'role_name' => 'required|string|max:255|unique:roles,name',
                'role_description' => 'nullable|string|max:1000',
            ]);

            // Save the role through the service layer.
            $savedRoleId = $this->rolePermissionAdminCrudService->storeRole($validatedRoleData);

            // Redirect back to the page with the saved role selected.
            $response = redirect()->route('admin.role-permission', [
                'role_id' => $savedRoleId,
            ])->with('success', 'Role saved successfully.');
        } catch (Throwable $exception) {
            // Redirect back with the page input on failure.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save the role.');
        }

        return $response;
    }

    // Save a new internal user from the page modal.
    public function storeUser(Request $request): RedirectResponse
    {
        try {
            // Validate the submitted user values.
            $validatedUserData = $request->validate([
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255|unique:users,email',
                'user_phone' => 'nullable|string|max:20',
                'employee_id' => 'required|string|max:50|unique:users,employee_id',
                'department_id' => 'required|integer|exists:departments,id',
                'role_id' => 'required|integer|exists:roles,id',
            ]);

            // Save the internal user through the service layer.
            $this->rolePermissionAdminCrudService->storeInternalUser($validatedUserData);

            // Redirect back with a success message.
            $response = redirect()->route('admin.role-permission')
                ->with('success', 'User created successfully. Employee ID is set as the temporary password.');
        } catch (Throwable $exception) {
            // Redirect back with the page input on failure.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create the user.');
        }

        return $response;
    }

    // Save the selected role permission matrix.
    public function saveRolePermissions(Request $request): RedirectResponse
    {
        try {
            // Validate the selected role and permission ids.
            $validatedMatrixData = $request->validate([
                'selected_role_id' => 'required|integer|exists:roles,id',
                'permission_ids' => 'nullable|array',
                'permission_ids.*' => 'integer|exists:permissions,id',
            ]);

            // Save the current permission matrix through the service layer.
            $this->rolePermissionAdminCrudService->saveRolePermissionMatrix(
                (int) $validatedMatrixData['selected_role_id'],
                $validatedMatrixData['permission_ids'] ?? [],
            );

            // Redirect back with the same role still selected.
            $response = redirect()->route('admin.role-permission', [
                'role_id' => (int) $validatedMatrixData['selected_role_id'],
            ])->with('success', 'Role permission mapping saved successfully.');
        } catch (Throwable $exception) {
            // Redirect back with the page input on failure.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save the permission mapping.');
        }

        return $response;
    }

    // Save user level permission overrides from the page modal.
    public function storeUserOverride(Request $request): RedirectResponse
    {
        try {
            // Validate the selected user and permissions.
            $validatedOverrideData = $request->validate([
                'override_user_id' => 'required|integer|exists:users,id',
                'permission_ids' => 'required|array|min:1',
                'permission_ids.*' => 'integer|exists:permissions,id',
            ]);

            // Save the selected overrides through the service layer.
            $this->rolePermissionAdminCrudService->storeUserOverride($validatedOverrideData);

            // Redirect back with a success message.
            $response = redirect()->route('admin.role-permission')
                ->with('success', 'User override saved successfully.');
        } catch (Throwable $exception) {
            // Redirect back with the page input on failure.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save the user override.');
        }

        return $response;
    }

    // Save delegated access from the page modal.
    public function storeDelegatedAccess(Request $request): RedirectResponse
    {
        try {
            // Validate the submitted delegated access values.
            $validatedDelegationData = $request->validate([
                'delegate_email' => 'required|email|max:255',
                'delegate_password' => 'required|string|min:8|max:255',
                'role_id' => 'required|integer|exists:roles,id',
                'expires_at' => 'required|date|after:now',
            ]);

            // Save the delegated access through the service layer.
            $this->rolePermissionAdminCrudService->storeDelegatedAccess($validatedDelegationData);

            // Redirect back with a success message.
            $response = redirect()->route('admin.role-permission')
                ->with('success', 'Delegated access saved successfully.');
        } catch (Throwable $exception) {
            // Redirect back with the page input on failure.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save delegated access.');
        }

        return $response;
    }

    // Save an impersonation audit row from the page modal.
    public function storeImpersonationSession(Request $request): RedirectResponse
    {
        try {
            // Validate the submitted impersonation values.
            $validatedImpersonationData = $request->validate([
                'impersonated_user_id' => 'required|integer|exists:users,id',
                'impersonator_name' => 'required|string|max:255',
                'ended_at' => 'required|date|after:now',
            ]);

            // Add request audit details used by the save flow.
            $validatedImpersonationData['ip_address'] = $request->ip();
            $validatedImpersonationData['user_agent'] = $request->userAgent();

            // Save the impersonation audit through the service layer.
            $this->rolePermissionAdminCrudService->storeImpersonationSession($validatedImpersonationData);

            // Redirect back with a success message.
            $response = redirect()->route('admin.role-permission')
                ->with('success', 'Impersonation audit saved successfully.');
        } catch (Throwable $exception) {
            // Redirect back with the page input on failure.
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save the impersonation audit.');
        }

        return $response;
    }
}

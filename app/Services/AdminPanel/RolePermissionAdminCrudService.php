<?php

namespace App\Services\AdminPanel;

use App\Models\Authorization\DelegatedAdminScope;
use App\Models\Authorization\Department;
use App\Models\Authorization\Permission;
use App\Models\Authorization\Role;
use App\Models\Authorization\User;
use App\Models\Authorization\UserPermission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RolePermissionAdminCrudService
{
    // This loads all page data used by the admin role permission page.
    public function getRolePermissionPageData(?int $selectedRoleId = null): array
    {
        // Load roles with the count details used by the page.
        $savedRoleList = Role::query()
            ->withCount(['users', 'permissions'])
            ->orderBy('name')
            ->get();

        // Load permissions once for the mapping section and override modal.
        $savedPermissionList = Permission::query()
            ->orderBy('name')
            ->get();

        // Load departments for the add user modal.
        $savedDepartmentList = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Load users with role and department details for the page tables.
        $savedUserList = User::query()
            ->with([
                'roles:id,name,slug',
                'departments:id,name',
            ])
            ->orderBy('name')
            ->get();

        // Start without a selected role.
        $selectedRole = null;

        // Load the selected role when a role id is given.
        if ($selectedRoleId) {
            $selectedRole = Role::query()
                ->with('permissions:id')
                ->find($selectedRoleId);
        }

        // Use the first role when the page opens without a selected role.
        if (! $selectedRole && $savedRoleList->count() > 0) {
            $firstSavedRole = $savedRoleList->first();

            if ($firstSavedRole) {
                $selectedRole = Role::query()
                    ->with('permissions:id')
                    ->find((int) $firstSavedRole->id);
            }
        }

        // Collect the selected permission ids for the mapping matrix.
        $selectedPermissionIdList = [];

        if ($selectedRole) {
            foreach ($selectedRole->permissions as $selectedPermission) {
                $selectedPermissionIdList[] = (int) $selectedPermission->id;
            }
        }

        // Build the permission groups shown on the matrix section.
        $permissionGroupMap = [];
        $defaultIconPath = 'M3.75 5.25h16.5v13.5H3.75zm4.5 3.75h7.5m-7.5 3h4.5';

        foreach ($savedPermissionList as $savedPermission) {
            // Read the slug used for the current permission.
            $permissionSlug = trim((string) $savedPermission->slug);
            $permissionGroupKey = 'general';

            // Build the group key from the permission slug.
            if ($permissionSlug !== '') {
                $permissionGroupParts = explode('.', $permissionSlug);
                $permissionGroupKey = trim((string) ($permissionGroupParts[0] ?? 'general'));
            }

            // Prepare the group title from the current key.
            $permissionGroupTitle = str_replace(['-', '_'], ' ', $permissionGroupKey);
            $permissionGroupTitle = Str::title($permissionGroupTitle);

            if ($permissionGroupTitle === '') {
                $permissionGroupTitle = 'General Access';
            }

            // Create the group row when it does not exist yet.
            if (! isset($permissionGroupMap[$permissionGroupKey])) {
                $permissionGroupMap[$permissionGroupKey] = [
                    'title' => $permissionGroupTitle,
                    'subtitle' => strtoupper($permissionGroupTitle) . ' ACCESS',
                    'iconPath' => $defaultIconPath,
                    'permissions' => [],
                ];
            }

            // Prepare the checked state for the current role.
            $isChecked = in_array((int) $savedPermission->id, $selectedPermissionIdList, true);

            // Add the permission row to the group.
            $permissionGroupMap[$permissionGroupKey]['permissions'][] = [
                'id' => (int) $savedPermission->id,
                'label' => $savedPermission->name,
                'description' => $savedPermission->slug,
                'checked' => $isChecked,
            ];
        }

        // Convert the permission groups into the final list.
        $permissionGroupList = [];

        foreach ($permissionGroupMap as $permissionGroup) {
            $permissionGroupList[] = $permissionGroup;
        }

        // Load the saved user override rows for the table.
        $savedOverrideList = UserPermission::query()
            ->with([
                'user:id,name,email',
                'user.roles:id,name',
                'permission:id,name,slug',
            ])
            ->orderByDesc('id')
            ->get();

        // Build the final override rows.
        $overrideList = [];

        foreach ($savedOverrideList as $savedOverride) {
            // Read the user details for the current row.
            $userName = $savedOverride->user?->name ?? 'Unknown User';
            $userEmail = $savedOverride->user?->email ?? '';
            $roleName = 'No Role Assigned';

            if ($savedOverride->user && $savedOverride->user->roles->count() > 0) {
                $firstUserRole = $savedOverride->user->roles->first();

                if ($firstUserRole) {
                    $roleName = $firstUserRole->name;
                }
            }

            // Build the initials shown in the table.
            $userInitials = $this->getUserInitials($userName);

            // Add the override row to the final list.
            $overrideList[] = [
                'initials' => $userInitials,
                'name' => $userName,
                'role' => $roleName,
                'permission' => $savedOverride->permission?->name ?? ($savedOverride->permission?->slug ?? 'Unknown Permission'),
                'status' => strtoupper($savedOverride->grant_type) === 'DENY' ? 'Pending' : 'Active',
            ];
        }

        // Build a role name map used by delegated access rows.
        $roleNameMap = [];

        foreach ($savedRoleList as $savedRole) {
            $roleNameMap[(string) $savedRole->id] = $savedRole->name;
        }

        // Load delegated access rows used by the page table.
        $savedDelegatedAccessList = DelegatedAdminScope::query()
            ->with('delegatedAdmin:id,name,email')
            ->where('scope_type', 'role')
            ->orderByDesc('id')
            ->get();

        // Build the final delegated rows.
        $delegatedAccessList = [];

        foreach ($savedDelegatedAccessList as $savedDelegatedAccess) {
            // Read the delegated user and role details.
            $delegatedUser = $savedDelegatedAccess->delegatedAdmin;
            $roleIdText = trim((string) $savedDelegatedAccess->scope_value);
            $roleName = $roleNameMap[$roleIdText] ?? 'Delegated Access';
            $expiryText = 'Not Set';
            $statusText = 'Active';

            // Build the expiry text and status.
            if ($savedDelegatedAccess->expires_at) {
                $expiryText = $savedDelegatedAccess->expires_at->format('d M Y h:i A');

                if ($savedDelegatedAccess->expires_at->lt(now())) {
                    $statusText = 'Expired';
                }
            }

            // Add the delegated row to the final list.
            $delegatedAccessList[] = [
                'id' => (int) $savedDelegatedAccess->id,
                'name' => $delegatedUser?->name ?? 'Unknown User',
                'email' => $delegatedUser?->email ?? '',
                'role' => $roleName,
                'expiry' => $expiryText,
                'status' => $statusText,
            ];
        }

        // Load impersonation audit rows with related user names.
        $savedImpersonationAuditList = DB::table('impersonation_audits')
            ->leftJoin('users as impersonator_users', 'impersonator_users.id', '=', 'impersonation_audits.impersonator_user_id')
            ->leftJoin('users as target_users', 'target_users.id', '=', 'impersonation_audits.impersonated_user_id')
            ->select([
                'impersonation_audits.id',
                'impersonation_audits.reason',
                'impersonation_audits.started_at',
                'impersonation_audits.ended_at',
                'impersonator_users.name as impersonator_name',
                'target_users.name as target_name',
            ])
            ->orderByDesc('impersonation_audits.id')
            ->get();

        // Build the final impersonation rows.
        $impersonationAuditList = [];

        foreach ($savedImpersonationAuditList as $savedImpersonationAudit) {
            // Prepare the started time text.
            $startedAtText = 'Not Available';
            $durationText = 'Not Available';
            $statusText = 'Live';

            if ($savedImpersonationAudit->started_at) {
                $startedAt = now()->parse($savedImpersonationAudit->started_at);
                $startedAtText = $startedAt->format('d M Y h:i A');

                $endedAt = now();

                if ($savedImpersonationAudit->ended_at) {
                    $endedAt = now()->parse($savedImpersonationAudit->ended_at);
                }

                if ($savedImpersonationAudit->ended_at && $endedAt->lte(now())) {
                    $statusText = 'Archived';
                }

                $durationInMinutes = $startedAt->diffInMinutes($endedAt);

                if ($durationInMinutes < 1) {
                    $durationInMinutes = 1;
                }

                if ($durationInMinutes >= 60) {
                    $durationHours = floor($durationInMinutes / 60);
                    $durationMinutes = $durationInMinutes % 60;
                    $durationText = $durationHours . 'h ' . $durationMinutes . 'm';
                }

                if ($durationInMinutes < 60) {
                    $durationText = $durationInMinutes . ' mins';
                }
            }

            // Add the impersonation row to the final list.
            $impersonationAuditList[] = [
                'initiator' => $savedImpersonationAudit->impersonator_name ?? 'System Admin',
                'target' => $savedImpersonationAudit->target_name ?? 'Unknown User',
                'started' => $startedAtText,
                'duration' => $durationText,
                'action' => $savedImpersonationAudit->reason ?: 'Manual Session',
                'status' => $statusText,
            ];
        }

        // Build the stats shown in the header section.
        $activeDelegateCount = 0;

        foreach ($delegatedAccessList as $delegatedAccess) {
            if ($delegatedAccess['status'] === 'Active') {
                $activeDelegateCount++;
            }
        }

        $stats = [];
        $stats[] = ['label' => 'Active Roles', 'value' => (string) $savedRoleList->count(), 'meta' => 'Configured access roles', 'tone' => 'primary'];
        $stats[] = ['label' => 'Mapped Permissions', 'value' => (string) $savedPermissionList->count(), 'meta' => 'Available permission rules', 'tone' => 'slate'];
        $stats[] = ['label' => 'Live Delegates', 'value' => str_pad((string) $activeDelegateCount, 2, '0', STR_PAD_LEFT), 'meta' => 'Delegated access sessions', 'tone' => 'secondary'];
        $stats[] = ['label' => 'Active Overrides', 'value' => str_pad((string) count($overrideList), 2, '0', STR_PAD_LEFT), 'meta' => 'User level permission changes', 'tone' => 'primary'];

        // Build the final page data.
        $pageData = [];
        $pageData['stats'] = $stats;
        $pageData['roles'] = $savedRoleList;
        $pageData['selectedRole'] = $selectedRole;
        $pageData['permissionGroups'] = $permissionGroupList;
        $pageData['users'] = $savedUserList;
        $pageData['departments'] = $savedDepartmentList;
        $pageData['overrides'] = $overrideList;
        $pageData['delegates'] = $delegatedAccessList;
        $pageData['impersonations'] = $impersonationAuditList;
        $pageData['permissions'] = $savedPermissionList;

        return $pageData;
    }

    // This creates a new role from the page modal.
    public function storeRole(array $roleData): int
    {
        // Read the role name from the submitted page form.
        $roleName = trim((string) ($roleData['role_name'] ?? ''));

        // Build the final role slug.
        $roleSlug = $this->resolveRoleSlug($roleName);

        // Save the new role row.
        $savedRole = Role::query()->create([
            'name' => $roleName,
            'slug' => $roleSlug,
        ]);

        return (int) $savedRole->id;
    }

    // This creates a new internal user from the page modal.
    public function storeInternalUser(array $userData): int
    {
        // Load the selected role and department rows.
        $savedRole = Role::query()->find((int) $userData['role_id']);
        $savedDepartment = Department::query()->find((int) $userData['department_id']);

        if (! $savedRole) {
            throw ValidationException::withMessages([
                'role_id' => 'Selected role was not found.',
            ]);
        }

        if (! $savedDepartment) {
            throw ValidationException::withMessages([
                'department_id' => 'Selected department was not found.',
            ]);
        }

        // Read the employee code used for login and reference.
        $employeeId = trim((string) ($userData['employee_id'] ?? ''));
        $phoneNumber = trim((string) ($userData['user_phone'] ?? ''));

        // Save the new internal user row.
        $savedUser = User::query()->create([
            'name' => trim((string) $userData['user_name']),
            'email' => trim((string) $userData['user_email']),
            'employee_id' => $employeeId,
            'phone' => $phoneNumber !== '' ? $phoneNumber : null,
            'user_type' => 'internal',
            'status' => 'active',
            'approved_at' => now(),
            'created_by_user_id' => auth()->id(),
            'password' => $employeeId,
        ]);

        // Start with the selected role id list.
        $roleIdList = [];
        $roleIdList[] = (int) $savedRole->id;

        // Add the base internal role when it exists.
        $baseInternalRoleId = Role::query()
            ->where('slug', 'internal_user')
            ->value('id');

        if ($baseInternalRoleId && ! in_array((int) $baseInternalRoleId, $roleIdList, true)) {
            $roleIdList[] = (int) $baseInternalRoleId;
        }

        // Save the user role rows.
        $savedUser->roles()->sync($roleIdList);

        // Save the user department row.
        $savedUser->departments()->sync([(int) $savedDepartment->id]);

        // Clear permission cache for the new user.
        $this->clearUserPermissionCache((int) $savedUser->id);

        return (int) $savedUser->id;
    }

    // This saves the permission mapping for the selected role.
    public function saveRolePermissionMatrix(int $roleId, array $permissionIdList): void
    {
        // Load the selected role row.
        $savedRole = Role::query()->find($roleId);

        if (! $savedRole) {
            throw ValidationException::withMessages([
                'selected_role_id' => 'Selected role was not found.',
            ]);
        }

        // Build the final valid permission id list.
        $validPermissionIdList = [];

        foreach ($permissionIdList as $permissionId) {
            $validPermissionIdList[] = (int) $permissionId;
        }

        $validPermissionIdList = Permission::query()
            ->whereIn('id', $validPermissionIdList)
            ->pluck('id')
            ->all();

        // Save the role permission mapping.
        $savedRole->permissions()->sync($validPermissionIdList);

        // Clear permission cache for users assigned to this role.
        $this->clearRolePermissionCache($roleId);
    }

    // This saves the selected user override permissions.
    public function storeUserOverride(array $overrideData): void
    {
        // Load the selected user row.
        $savedUser = User::query()->find((int) $overrideData['override_user_id']);

        if (! $savedUser) {
            throw ValidationException::withMessages([
                'override_user_id' => 'Selected user was not found.',
            ]);
        }

        // Build the final permission id list for the override.
        $permissionIdList = [];

        foreach ($overrideData['permission_ids'] as $permissionId) {
            $permissionIdList[] = (int) $permissionId;
        }

        $savedPermissionList = Permission::query()
            ->whereIn('id', $permissionIdList)
            ->orderBy('name')
            ->get();

        // Save one allow override for each selected permission.
        foreach ($savedPermissionList as $savedPermission) {
            UserPermission::query()->updateOrCreate(
                [
                    'user_id' => (int) $savedUser->id,
                    'permission_id' => (int) $savedPermission->id,
                ],
                [
                    'grant_type' => 'allow',
                    'granted_by_user_id' => auth()->id(),
                ],
            );
        }

        // Clear permission cache for the selected user.
        $this->clearUserPermissionCache((int) $savedUser->id);
    }

    // This saves delegated access from the page modal.
    public function storeDelegatedAccess(array $delegationData): int
    {
        // Load the selected role row.
        $savedRole = Role::query()->find((int) $delegationData['role_id']);

        if (! $savedRole) {
            throw ValidationException::withMessages([
                'role_id' => 'Selected role was not found.',
            ]);
        }

        // Read the submitted email and build a fallback user name.
        $delegateEmail = trim((string) $delegationData['delegate_email']);
        $emailNamePart = $delegateEmail;

        if (str_contains($delegateEmail, '@')) {
            $emailParts = explode('@', $delegateEmail);
            $emailNamePart = (string) ($emailParts[0] ?? $delegateEmail);
        }

        $delegateName = str_replace(['.', '_', '-'], ' ', $emailNamePart);
        $delegateName = Str::title($delegateName);

        if ($delegateName === '') {
            $delegateName = 'Delegated User';
        }

        // Load the delegated user by email when it already exists.
        $savedUser = User::query()
            ->where('email', $delegateEmail)
            ->first();

        // Create the delegated user when no user exists yet.
        if (! $savedUser) {
            $savedUser = User::query()->create([
                'name' => $delegateName,
                'email' => $delegateEmail,
                'user_type' => 'delegated_admin',
                'status' => 'active',
                'approved_at' => now(),
                'created_by_user_id' => auth()->id(),
                'password' => (string) $delegationData['delegate_password'],
            ]);
        }

        // Update the existing user with delegated access values.
        if ($savedUser) {
            $savedUser->user_type = 'delegated_admin';
            $savedUser->status = 'active';
            $savedUser->approved_at = now();
            $savedUser->created_by_user_id = auth()->id();
            $savedUser->password = (string) $delegationData['delegate_password'];
            $savedUser->save();
        }

        // Start with the selected role id list.
        $roleIdList = [];
        $roleIdList[] = (int) $savedRole->id;

        // Add the delegated admin base role when it exists.
        $delegatedAdminRoleId = Role::query()
            ->where('slug', 'delegated_admin')
            ->value('id');

        if ($delegatedAdminRoleId && ! in_array((int) $delegatedAdminRoleId, $roleIdList, true)) {
            $roleIdList[] = (int) $delegatedAdminRoleId;
        }

        // Save the delegated user role rows.
        $savedUser->roles()->sync($roleIdList);

        // Remove old delegated role scope rows for this user.
        DelegatedAdminScope::query()
            ->where('delegated_admin_user_id', (int) $savedUser->id)
            ->where('scope_type', 'role')
            ->delete();

        // Save the current delegated role row.
        $savedDelegatedAccess = DelegatedAdminScope::query()->create([
            'delegated_admin_user_id' => (int) $savedUser->id,
            'scope_type' => 'role',
            'scope_value' => (string) $savedRole->id,
            'assigned_by_user_id' => auth()->id(),
            'expires_at' => $delegationData['expires_at'],
        ]);

        // Clear permission cache for the delegated user.
        $this->clearUserPermissionCache((int) $savedUser->id);

        return (int) $savedDelegatedAccess->id;
    }

    // This stores a new impersonation audit row from the page modal.
    public function storeImpersonationSession(array $impersonationData): int
    {
        // Load the selected target user row.
        $savedTargetUser = User::query()->find((int) $impersonationData['impersonated_user_id']);

        if (! $savedTargetUser) {
            throw ValidationException::withMessages([
                'impersonated_user_id' => 'Selected target user was not found.',
            ]);
        }

        // Start with the logged-in user as the actor.
        $actorUserId = auth()->id();

        // Use the first saved user when the page is used without login.
        if (! $actorUserId) {
            $actorUserId = User::query()->orderBy('id')->value('id');
        }

        if (! $actorUserId) {
            throw ValidationException::withMessages([
                'impersonator_name' => 'No audit user is available for this action.',
            ]);
        }

        // Build the saved reason text from the page input.
        $savedReason = 'Manual impersonation session by ' . trim((string) $impersonationData['impersonator_name']);

        // Save the audit row.
        $savedAuditId = DB::table('impersonation_audits')->insertGetId([
            'impersonator_user_id' => (int) $actorUserId,
            'impersonated_user_id' => (int) $savedTargetUser->id,
            'reason' => $savedReason,
            'ip_address' => $impersonationData['ip_address'] ?? null,
            'user_agent' => $impersonationData['user_agent'] ?? null,
            'started_at' => now(),
            'ended_at' => $impersonationData['ended_at'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return (int) $savedAuditId;
    }

    // This builds a unique slug for a new role.
    protected function resolveRoleSlug(string $roleName): string
    {
        // Start with a clean base slug from the role name.
        $baseRoleSlug = Str::slug($roleName, '_');

        if ($baseRoleSlug === '') {
            $baseRoleSlug = 'role';
        }

        $roleSlug = $baseRoleSlug;
        $slugCounter = 1;

        // Keep changing the slug until it becomes unique.
        while (Role::query()->where('slug', $roleSlug)->exists()) {
            $roleSlug = $baseRoleSlug . '_' . $slugCounter;
            $slugCounter++;
        }

        return $roleSlug;
    }

    // This builds short initials from a user name.
    protected function getUserInitials(string $userName): string
    {
        // Start with an empty initials value.
        $userInitials = '';
        $namePartList = preg_split('/\s+/', trim($userName)) ?: [];

        // Read the first letter from the first two name parts.
        foreach ($namePartList as $namePart) {
            if ($namePart === '') {
                continue;
            }

            $userInitials .= strtoupper(substr($namePart, 0, 1));

            if (strlen($userInitials) >= 2) {
                break;
            }
        }

        if ($userInitials === '') {
            $userInitials = 'NA';
        }

        return $userInitials;
    }

    // This clears saved permission cache for one user.
    protected function clearUserPermissionCache(int $userId): void
    {
        // Remove the saved role and permission cache keys.
        Cache::forget('user_role_slugs_' . $userId);
        Cache::forget('user_permission_slugs_' . $userId);
    }

    // This clears saved permission cache for users assigned to one role.
    protected function clearRolePermissionCache(int $roleId): void
    {
        // Load the role with all linked user ids.
        $savedRole = Role::query()
            ->with('users:id')
            ->find($roleId);

        if (! $savedRole) {
            return;
        }

        // Clear cache for each linked user.
        foreach ($savedRole->users as $savedUser) {
            $this->clearUserPermissionCache((int) $savedUser->id);
        }
    }
}

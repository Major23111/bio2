<?php

namespace App\Services\Authorization;

use App\Models\Authorization\Permission;
use App\Models\Authorization\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class RolePermissionAdminCrudService
{
    // This builds the role page data for list and edit mode.
    public function rolePageData(?int $editRoleId = null, ?int $editPermissionId = null): array
    {
        // Step 1: load all roles with usage counts for the list table.
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->orderBy('name')
            ->get();

        // Step 2: load all permissions with usage counts for the permission table.
        $permissions = Permission::query()
            ->withCount(['roles', 'userOverrides'])
            ->orderBy('name')
            ->get();

        // Step 3: load one role when the page is opened in edit mode.
        $editingRole = $editRoleId
            ? Role::query()->with('permissions:id')->find($editRoleId)
            : null;

        // Step 4: load one permission when the page is opened in permission edit mode.
        $editingPermission = $editPermissionId
            ? Permission::query()->find($editPermissionId)
            : null;

        // Step 5: collect checked permission ids for the selected role.
        $editingRolePermissionIds = $editingRole
            ? $editingRole->permissions->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];

        return [
            'roles' => $roles,
            'permissions' => $permissions,
            'editingRole' => $editingRole,
            'editingPermission' => $editingPermission,
            'editingRolePermissionIds' => $editingRolePermissionIds,
        ];
    }

    // This creates a new role record.
    public function addRole(array $validated): int
    {
        // Step 1: build a unique slug from submitted data.
        $roleSlug = $this->resolveRoleSlug((string) ($validated['slug'] ?? ''), (string) $validated['name']);

        // Step 2: create the role row.
        $role = Role::query()->create([
            'name' => trim((string) $validated['name']),
            'slug' => $roleSlug,
        ]);

        return (int) $role->id;
    }

    // This updates an existing role record.
    public function updateRole(int $roleId, array $validated): void
    {
        // Step 1: load the role that should be updated.
        $role = Role::query()->find($roleId);

        if (! $role) {
            throw ValidationException::withMessages([
                'role' => 'Role not found.',
            ]);
        }

        // Step 2: build a unique slug and save the updated fields.
        $roleSlug = $this->resolveRoleSlug((string) ($validated['slug'] ?? ''), (string) $validated['name'], $roleId);

        $role->fill([
            'name' => trim((string) $validated['name']),
            'slug' => $roleSlug,
        ])->save();

        // Step 3: invalidate caches for all users assigned to this role.
        $this->clearRolePermissionCaches($roleId);
    }

    // This deletes a role and clears its pivot relations first.
    public function deleteRole(int $roleId): void
    {
        // Step 1: load the role that should be removed.
        $role = Role::query()->find($roleId);

        if (! $role) {
            throw ValidationException::withMessages([
                'role' => 'Role not found.',
            ]);
        }

        // Step 2: invalidate caches for all users assigned to this role before deletion.
        $this->clearRolePermissionCaches($roleId);

        // Step 3: remove linked users and permissions from pivot tables.
        $role->users()->detach();
        $role->permissions()->detach();

        // Step 4: delete the role row itself.
        $role->delete();
    }

    // This creates a new permission record.
    public function createPermission(array $validated): int
    {
        // Step 1: build a unique permission slug from submitted data.
        $permissionSlug = $this->resolvePermissionSlug((string) ($validated['slug'] ?? ''), (string) $validated['name']);

        // Step 2: create the permission row.
        $permission = Permission::query()->create([
            'name' => trim((string) $validated['name']),
            'slug' => $permissionSlug,
        ]);

        return (int) $permission->id;
    }

    // This updates an existing permission record.
    public function updatePermission(int $permissionId, array $validated): void
    {
        // Step 1: load the permission that should be updated.
        $permission = Permission::query()->find($permissionId);

        if (! $permission) {
            throw ValidationException::withMessages([
                'permission' => 'Permission not found.',
            ]);
        }

        // Step 2: build a unique slug and save the updated fields.
        $permissionSlug = $this->resolvePermissionSlug((string) ($validated['slug'] ?? ''), (string) $validated['name'], $permissionId);

        $permission->fill([
            'name' => trim((string) $validated['name']),
            'slug' => $permissionSlug,
        ])->save();

        // Step 3: invalidate caches for all affected roles and users.
        $this->clearPermissionCaches($permissionId);
    }

    // This deletes one permission and clears its linked rows first.
    public function deletePermission(int $permissionId): void
    {
        // Step 1: load the permission that should be removed.
        $permission = Permission::query()->find($permissionId);

        if (! $permission) {
            throw ValidationException::withMessages([
                'permission' => 'Permission not found.',
            ]);
        }

        // Step 2: invalidate caches for all affected roles and users before deletion.
        $this->clearPermissionCaches($permissionId);

        // Step 3: remove linked role and user override rows.
        $permission->roles()->detach();
        $permission->userOverrides()->delete();

        // Step 4: delete the permission row itself.
        $permission->delete();
    }

    // This saves the checked permission list for one role.
    public function upsertPermissionsForRole(int $roleId, array $permissionIds): void
    {
        // Step 1: load the role that should receive permission changes.
        $role = Role::query()->find($roleId);

        if (! $role) {
            throw ValidationException::withMessages([
                'role' => 'Role not found.',
            ]);
        }

        // Step 2: keep only valid permission ids before syncing the pivot table.
        $validPermissionIds = Permission::query()
            ->whereIn('id', collect($permissionIds)->map(fn ($id) => (int) $id)->filter(fn ($id) => $id > 0))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        // Step 3: sync will add checked rows and remove unchecked rows.
        $role->permissions()->sync($validPermissionIds);

        // Step 4: invalidate caches for all users assigned to this role.
        $this->clearRolePermissionCaches($roleId);
    }

    // This creates a readable unique slug for the role.
    protected function resolveRoleSlug(string $slugInput, string $name, ?int $ignoreRoleId = null): string
    {
        $seedValue = trim($slugInput) !== '' ? $slugInput : $name;
        $baseSlug = Str::slug($seedValue, '_');
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'role';
        $roleSlug = $baseSlug;
        $counter = 1;

        while (Role::query()
            ->where('slug', $roleSlug)
            ->when($ignoreRoleId, fn ($query) => $query->where('id', '!=', $ignoreRoleId))
            ->exists()) {
            $roleSlug = $baseSlug.'_'.$counter;
            $counter++;
        }

        return $roleSlug;
    }

    // This creates a readable unique slug for the permission.
    protected function resolvePermissionSlug(string $slugInput, string $name, ?int $ignorePermissionId = null): string
    {
        $seedValue = trim($slugInput) !== '' ? $slugInput : $name;
        $baseSlug = Str::slug($seedValue, '.');
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'permission';
        $permissionSlug = $baseSlug;
        $counter = 1;

        while (Permission::query()
            ->where('slug', $permissionSlug)
            ->when($ignorePermissionId, fn ($query) => $query->where('id', '!=', $ignorePermissionId))
            ->exists()) {
            $permissionSlug = $baseSlug.'.'.$counter;
            $counter++;
        }

        return $permissionSlug;
    }

    // Helper method to clear all permission caches for users assigned to a role.
    protected function clearRolePermissionCaches(int $roleId): void
    {
        $role = Role::query()->with('users:id')->find($roleId);

        if (! $role) {
            return;
        }

        // Clear cache for each user assigned to this role
        foreach ($role->users as $user) {
            Cache::forget("user_role_slugs_{$user->id}");
            Cache::forget("user_permission_slugs_{$user->id}");
        }
    }

    // Helper method to clear all permission caches for users with this permission.
    protected function clearPermissionCaches(int $permissionId): void
    {
        $permission = Permission::query()->with('roles.users:id')->find($permissionId);

        if (! $permission) {
            return;
        }

        // Collect all unique user IDs from all roles that have this permission
        $userIds = collect();
        foreach ($permission->roles as $role) {
            foreach ($role->users as $user) {
                $userIds->push($user->id);
            }
        }

        // Also get users who have direct overrides for this permission
        Permission::query()
            ->where('id', $permissionId)
            ->with('userOverrides:user_id')
            ->first()?->userOverrides->each(fn ($override) => $userIds->push($override->user_id));

        // Clear permutation caches for all affected users
        $userIds->unique()->each(function ($userId) {
            Cache::forget("user_role_slugs_{$userId}");
            Cache::forget("user_permission_slugs_{$userId}");
        });
    }
}

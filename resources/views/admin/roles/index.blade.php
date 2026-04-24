@extends('admin.layout')

@section('title', 'Role And Permission Management - Biogenix Admin')

@section('admin_content')



    <!-- Welcome Header -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Role And Permission Management</h1>
            <p class="text-sm text-slate-500 mt-1">Create roles, organize permissions, and assign access levels.</p>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Stats Sidebar Grid -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[var(--ui-shadow-soft)] md:p-8">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Roles</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ count($roles) }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Permissions</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ count($permissions) }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Editing Role</p>
                    <p class="mt-2 text-[14px] font-bold text-primary-600 truncate">{{ $editingRole->name ?? 'None Selected' }}</p>
                </div>
            </div>
        </section>

        @if (session('status'))
            <div class="rounded-xl border border-primary-200 bg-primary-50 px-4 py-3 text-[13px] font-bold text-primary-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-[13px] text-rose-700">
                <p class="font-bold uppercase tracking-widest text-[10px] mb-2 text-rose-500">Validation Errors</p>
                <ul class="space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <span class="h-1 w-1 rounded-full bg-rose-400"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-2">
            <!-- Add/Update Role Form -->
            <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-slate-900">{{ $editingRole ? 'Update Role' : 'Add Role' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Define access levels with meaningful names.</p>
                </div>

                <form method="POST" action="{{ $editingRole ? route('admin.roles.update', $editingRole->id) : route('admin.roles.store') }}" class="space-y-4">
                    @csrf
                    @if ($editingRole) @method('PUT') @endif

                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Role Name</label>
                        <input name="name" value="{{ old('name', $editingRole->name ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Role Slug</label>
                        <input name="slug" value="{{ old('slug', $editingRole->slug ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" placeholder="Auto-generated if empty">
                    </div>

                    <div class="flex items-center gap-3">
                        <button class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition shadow-sm cursor-pointer" type="submit">
                            {{ $editingRole ? 'Update Role' : 'Add Role' }}
                        </button>
                        @if ($editingRole)
                            <a class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition cursor-pointer" href="{{ route('admin.roles.index') }}">Cancel</a>
                        @endif
                    </div>
                </form>
            </section>

            <!-- Add/Update Permission Form -->
            <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-slate-900">{{ $editingPermission ? 'Update Permission' : 'Add Permission' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Keep slugs focused on single system actions.</p>
                </div>

                <form method="POST" action="{{ $editingPermission ? route('admin.roles.permissions.update', $editingPermission->id) : route('admin.roles.permissions.store') }}" class="space-y-4">
                    @csrf
                    @if ($editingPermission) @method('PUT') @endif

                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Permission Name</label>
                        <input name="name" value="{{ old('name', $editingPermission->name ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Permission Slug</label>
                        <input name="slug" value="{{ old('slug', $editingPermission->slug ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" placeholder="Auto-generated if empty">
                    </div>

                    <div class="flex items-center gap-3">
                        <button class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition shadow-md shadow-primary-600/20 cursor-pointer" type="submit">
                            {{ $editingPermission ? 'Update Permission' : 'Add Permission' }}
                        </button>
                        @if ($editingPermission)
                            <a class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition cursor-pointer" href="{{ $editingRole ? route('admin.roles.show', $editingRole->id) : route('admin.roles.index') }}">Cancel</a>
                        @endif
                    </div>
                </form>
            </section>
        </div>

        <!-- Roles Table -->
        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Existing Roles</h2>
                    <p class="text-sm text-slate-500 mt-1">Review adoptance and usage counts.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-[11px] font-bold text-primary-700 border border-primary-200/60">{{ count($roles) }} configured</span>
            </div>

            @if (count($roles))
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Name</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Slug</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Users</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($roles as $role)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] font-bold text-slate-900">{{ $role->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] text-slate-600">{{ $role->slug }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-600 text-[11px] font-bold rounded-md border border-slate-200">{{ $role->users_count }} users</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a class="text-primary-600 hover:text-primary-700 transition font-bold text-xs uppercase tracking-widest" href="{{ route('admin.roles.show', $role->id) }}">Edit</a>
                                            <form method="POST" action="{{ route('admin.roles.delete', $role->id) }}" onsubmit="return confirm('Delete this role?');">
                                                @csrf @method('DELETE')
                                                <button class="text-rose-600 hover:text-rose-700 transition font-bold text-xs uppercase tracking-widest cursor-pointer" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-[13px] text-slate-400 py-8 bg-slate-50/50 rounded-xl border border-slate-100 border-dashed">No roles found.</p>
            @endif
        </section>

        <!-- Permissions Table -->
        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Existing Permissions</h2>
                    <p class="text-sm text-slate-500 mt-1">Watch for overrides that may indicate missing rules.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-[11px] font-bold text-primary-700 border border-primary-200/60">{{ count($permissions) }} available</span>
            </div>

            @if (count($permissions))
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Name</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Slug</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($permissions as $permission)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] font-bold text-slate-900">{{ $permission->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] text-slate-600 font-mono">{{ $permission->slug }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a class="text-primary-600 hover:text-primary-700 transition font-bold text-xs uppercase tracking-widest" href="{{ $editingRole ? route('admin.roles.show', ['roleId' => $editingRole->id, 'edit_permission_id' => $permission->id]) : route('admin.roles.index', ['edit_permission_id' => $permission->id]) }}">Edit</a>
                                            <form method="POST" action="{{ route('admin.roles.permissions.delete', $permission->id) }}" onsubmit="return confirm('Delete this permission?');">
                                                @csrf @method('DELETE')
                                                <button class="text-rose-600 hover:text-rose-700 transition font-bold text-xs uppercase tracking-widest cursor-pointer" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-[13px] text-slate-400 py-8 bg-slate-50/50 rounded-xl border border-slate-100 border-dashed">No permissions found.</p>
            @endif
        </section>

        <!-- Manage Role Permissions -->
        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Permissions for Selected Role</h2>
                <p class="text-sm text-slate-500 mt-1">Assign the permissions for the active role.</p>
            </div>

            @if ($editingRole)
                <div class="rounded-xl border border-primary-100 bg-primary-50/50 p-4 mb-6">
                    <p class="text-[13px] text-primary-700 font-medium leading-relaxed">
                        Managing permissions for <span class="font-extrabold">{{ $editingRole->name }}</span>. Unchecked items will be removed.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.roles.permissions.upsert', $editingRole->id) }}" class="space-y-6">
                    @csrf
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($permissions as $permission)
                            <label class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/30 p-4 transition hover:bg-white hover:border-primary-200 group cursor-pointer">
                                <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600" {{ in_array($permission->id, $editingRolePermissionIds, true) ? 'checked' : '' }}>
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-900">{{ $permission->name }}</span>
                                    <span class="text-[10px] uppercase font-black tracking-widest text-slate-400 mt-1">{{ $permission->slug }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <button class="px-6 py-2.5 rounded-xl text-sm font-extrabold text-white bg-primary-600 hover:bg-primary-700 transition shadow-lg shadow-primary-600/20 cursor-pointer" type="submit">Save Role Permissions</button>
                </form>
            @else
                <div class="flex flex-col items-center justify-center py-12 rounded-xl border border-slate-200 border-dashed bg-slate-50/50">
                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 1-2.625.372 9.337 9.337 0 0 1-4.121-.952 4.125 4.125 0 0 0-7.533 2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <p class="text-[13px] font-bold text-slate-600 uppercase tracking-widest">No Role Selected</p>
                    <p class="text-xs text-slate-400 mt-1">Select a role from the list above to manage its permissions.</p>
                </div>
            @endif
        </section>
    </div>
@endsection

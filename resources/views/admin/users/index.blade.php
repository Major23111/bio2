@extends('admin.layout')

@section('title', 'Admin Access Control Console - Biogenix Admin')

@section('admin_content')



    <!-- Welcome Header -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Admin Access Control Console</h1>
            <p class="text-sm text-slate-500 mt-1">Manage B2B approvals, internal users, delegated admins, and permission overrides.</p>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Stats Sidebar Grid -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[var(--ui-shadow-soft)] md:p-8">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pending B2B</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $pendingB2bUsers->count() }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Overrides</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $userOverrides->count() }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Delegated Scopes</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $delegatedScopes->count() }}</p>
                </div>
            </div>
        </section>

        <!-- B2B Approvals Section -->
        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Pending B2B Approvals</h2>
                    <p class="text-sm text-slate-500 mt-1">Review pending requests and approve or reject them.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-[11px] font-bold text-primary-700 border border-primary-200/60">{{ $pendingB2bUsers->count() }} pending</span>
            </div>

            @if ($pendingB2bUsers->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Name</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Email</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">B2B Type</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Company</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pendingB2bUsers as $pending)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] font-bold text-slate-900">{{ $pending->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] text-slate-600">{{ $pending->email }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-700 border border-slate-200/60 text-[10px] font-black uppercase tracking-widest rounded-full">{{ $pending->b2b_type ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] text-slate-600">{{ $pending->company_name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.users.b2b.approve', $pending->id) }}">
                                                @csrf
                                                <button class="px-3 py-1.5 rounded-lg text-[12px] font-bold text-white bg-primary-600 hover:bg-primary-700 transition cursor-pointer">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.b2b.reject', $pending->id) }}">
                                                @csrf
                                                <button class="px-3 py-1.5 rounded-lg text-[12px] font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition cursor-pointer">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 px-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No pending B2B approvals</p>
                    <p class="text-[13px] text-slate-400 mt-1">All requests have been processed.</p>
                </div>
            @endif
        </section>

        <!-- User Creation Grid -->
        <div class="grid gap-6 xl:grid-cols-2">
            <!-- Internal User Form -->
            <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-slate-900">Create Internal User</h2>
                    <p class="text-sm text-slate-500 mt-1">Internal users can belong to multiple departments.</p>
                </div>

                <form method="POST" action="{{ route('admin.users.internal.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Name</label>
                            <input name="name" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Email</label>
                            <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Password</label>
                            <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Departments</label>
                        <select name="department_ids[]" multiple size="4" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="w-full px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition shadow-sm cursor-pointer" type="submit">Create Internal User</button>
                </form>
            </section>

            <!-- Delegated Admin Form -->
            <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-slate-900">Create Delegated Admin</h2>
                    <p class="text-sm text-slate-500 mt-1">Receive limited admin-level access with scope controls.</p>
                </div>

                <form method="POST" action="{{ route('admin.users.delegated.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Name</label>
                            <input name="name" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Email</label>
                            <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Password</label>
                            <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" required>
                        </div>
                    </div>
                    <button class="w-full px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition shadow-sm cursor-pointer" type="submit">Create Delegated Admin</button>
                </form>
            </section>
        </div>

        <!-- Permission Overrides Section -->
        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">User-Level Permission Overrides</h2>
                <p class="text-sm text-slate-500 mt-1">Assign allow/deny overrides to specific users.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.permissions.set', 0) }}" id="override-form" class="grid gap-4 lg:grid-cols-4 items-end">
                @csrf
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">User</label>
                    <select id="override_user_id" name="override_user_id" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                        <option value="">Select user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Permission</label>
                    <select name="permission_id" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                        <option value="">Select permission</option>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->slug }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Override Type</label>
                    <select name="grant_type" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                        <option value="allow">Allow</option>
                        <option value="deny">Deny</option>
                    </select>
                </div>
                <button class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition shadow-sm cursor-pointer" type="submit">Save Override</button>
            </form>

            @if ($userOverrides->count())
                <div class="mt-8 overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">User</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Permission</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Type</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($userOverrides as $override)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-slate-900">{{ $override->user_name }}</span>
                                            <span class="text-[11px] text-slate-400">{{ $override->user_email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] font-semibold text-slate-600">{{ $override->permission_slug }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-700 border border-slate-200/60 text-[10px] font-black uppercase tracking-widest rounded-full">{{ $override->grant_type }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.users.permissions.delete', $override->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-rose-600 hover:text-rose-700 transition font-bold text-xs uppercase tracking-widest cursor-pointer" type="submit">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-6 text-center text-[13px] text-slate-400 py-4 bg-slate-50/50 rounded-xl border border-slate-100 border-dashed">No user-level overrides configured.</p>
            @endif
        </section>
    </div>

    <script>
        const adminUsersBase = "{{ url('/admin/users') }}";
        const overrideForm = document.getElementById('override-form');
        const overrideUserSelect = document.getElementById('override_user_id');
        overrideForm.addEventListener('submit', function () {
            const selectedUserId = overrideUserSelect.value;
            overrideForm.action = `${adminUsersBase}/${selectedUserId || '0'}/permissions`;
        });
    </script>
@endsection

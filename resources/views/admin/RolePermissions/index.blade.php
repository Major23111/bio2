@extends('admin.layout')

@section('title', 'Roles & Permissions Center - Biogenix Admin')

@section('admin_content')
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Roles & Permissions Management</h2>
                <p class="text-sm text-[var(--ui-text-muted)] mt-1 font-medium">Configure systemic access levels and fine-grained user overrides.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="AdminPermissionModal.show(this)" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--ui-surface)] border border-[var(--ui-border)] px-4 py-2.5 text-xs font-bold text-[var(--ui-text)] shadow-sm hover:bg-[var(--ui-surface-subtle)] transition">
                    <svg class="h-4 w-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.744c0 1.506.276 2.947.781 4.279.487 1.283 1.157 2.463 1.986 3.486m2.138 2.388c.923.65 1.936 1.187 3.024 1.588M12 21.351c1.104 0 2.176-.23 3.15-.65m2.417-1.12c.981-.62 1.866-1.393 2.628-2.288m2.138-2.388a11.821 11.821 0 00.781-4.279c0-1.326-.217-2.597-.619-3.784a11.916 11.916 0 00-6.381-6.833z" />
                    </svg>
                    Add New Permission
                </button>
                <button type="button" onclick="AdminRoleModal.show(this)" aria-haspopup="dialog" aria-controls="create-role-modal" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-primary-600 bg-primary-600 px-4 text-[13px] font-bold text-white shadow-sm transition hover:bg-primary-700 shadow-lg shadow-primary-600/20 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New Role
                </button>
            </div>
        </div>

        <!-- Permission Mapping Matrix Card -->
        <section class="overflow-hidden rounded-2xl border border-[var(--ui-border)] bg-[var(--ui-surface)] shadow-[var(--ui-shadow-soft)]">
            <div class="flex flex-col md:flex-row md:items-center justify-between p-6 border-b border-[var(--ui-border)] gap-4">
                <div>
                    <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Permission Mapping Matrix</h2>
                    <p class="text-[13px] font-medium text-[var(--ui-text-muted)] mt-1">Configure access capabilities for the selected user role.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative min-w-[200px]">
                        <select class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-xl py-2 pl-4 pr-10 text-sm font-bold text-[var(--ui-text)] appearance-none outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20 transition">
                            <option>Department Manager</option>
                            <option>Super Admin</option>
                            <option>Sales Lead</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-[var(--ui-text-muted)]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 uppercase tracking-wider border border-emerald-100">Synced With Core</span>
                </div>
            </div>

            <div class="p-0">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[var(--ui-border)]">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Capability / Entity</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ui-border)]">
                        @php
                            $matrixItems = [
                                ['title' => 'Edit Product Catalog', 'desc' => 'Modify SKU, Pricing, and Descriptions', 'checked' => true],
                                ['title' => 'View Financial Orders', 'desc' => 'Access transaction history and billing', 'checked' => true],
                                ['title' => 'Manage System Users', 'desc' => 'Create, Suspend, or Delete profiles', 'checked' => false],
                                ['title' => 'Override Clinical Data', 'desc' => 'Manual adjustments to laboratory findings', 'checked' => false],
                                ['title' => 'Export System Logs', 'desc' => 'Download raw CSV data of all system interactions', 'checked' => true],
                                ['title' => 'API Management', 'desc' => 'Generate and revoke developer tokens', 'checked' => false],
                            ];
                        @endphp
                        @foreach($matrixItems as $item)
                        <tr class="group hover:bg-[var(--ui-surface-subtle)] transition-colors">
                            <td class="px-6 py-5">
                                <div class="text-sm font-extrabold text-[var(--ui-text)]">{{ $item['title'] }}</div>
                                <div class="text-[11px] font-medium text-[var(--ui-text-muted)] mt-0.5">{{ $item['desc'] }}</div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" {{ $item['checked'] ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-800"></div>
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-6 border-t border-[var(--ui-border)] bg-[var(--ui-surface-subtle)]/30 flex justify-end">
                    <button class="bg-primary-600 border border-primary-600 text-white px-8 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-primary-700 transition shadow-lg shadow-primary-600/20 active:scale-[0.98]">
                        Save Permission Changes
                    </button>
                </div>
            </div>
        </section>

        <!-- Bottom Grid: Overrides & Delegation -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- User-Based Overrides -->
            <section class="bg-[var(--ui-surface)] rounded-2xl border border-[var(--ui-border)] shadow-[var(--ui-shadow-soft)] flex flex-col">
                <div class="p-6 border-b border-[var(--ui-border)] flex items-center justify-between">
                    <div>
                        <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">User-Based Overrides</h2>
                        <p class="text-[13px] font-medium text-[var(--ui-text-muted)] mt-1">Individual exceptions to role-based access.</p>
                    </div>
                    <a href="#" class="text-[11px] font-bold text-primary-600 hover:underline">View All</a>
                </div>
                <div class="p-6 space-y-4 flex-1">
                    @php
                        $overrides = [
                            ['name' => 'Elena Lysenko', 'role' => 'CLINICAL LEAD', 'badge' => 'Manage Users', 'expires' => 'Expires in 12d', 'color' => 'rose'],
                            ['name' => 'Julian Kross', 'role' => 'STANDARD SUPPORT', 'badge' => 'Billing View', 'expires' => 'Permanent', 'color' => 'indigo'],
                        ];
                    @endphp
                    @foreach($overrides as $user)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-[var(--ui-surface-subtle)]/50 border border-[var(--ui-border)] group hover:border-primary-200 transition">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-lg bg-[var(--ui-surface-subtle)] flex items-center justify-center font-bold text-[var(--ui-text-muted)] uppercase">{{ substr($user['name'], 0, 1) }}{{ substr(strrchr($user['name'], " "), 1, 1) }}</div>
                            <div>
                                <div class="text-sm font-extrabold text-[var(--ui-text)]">{{ $user['name'] }}</div>
                                <div class="text-[10px] font-bold text-[var(--ui-text-muted)] tracking-wider">{{ $user['role'] }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-[10px] font-black text-{{ $user['color'] }}-600 uppercase tracking-tighter transition group-hover:scale-105">+ {{ $user['badge'] }}</div>
                                <div class="text-[9px] font-medium text-[var(--ui-text-muted)]">{{ $user['expires'] }}</div>
                            </div>
                            <button class="text-[var(--ui-text-muted)]/40 hover:text-rose-500 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    <button type="button" onclick="AdminOverrideModal.show(this)" class="w-full py-4 rounded-xl border-2 border-dashed border-[var(--ui-border)] hover:border-primary-400 hover:bg-primary-50/10 transition text-[11px] font-black text-[var(--ui-text-muted)] uppercase tracking-widest flex items-center justify-center gap-2">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Individual Override
                    </button>
                </div>
            </section>

            <!-- Delegation & Impersonation -->
            <section class="bg-[var(--ui-surface)] rounded-2xl border border-[var(--ui-border)] shadow-[var(--ui-shadow-soft)] flex flex-col">
                <div class="p-6 border-b border-[var(--ui-border)] flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Delegation & Impersonation</h2>
                        <p class="text-[13px] font-medium text-[var(--ui-text-muted)] mt-1">Audit and manage active temporary access grants.</p>
                    </div>
                    <div class="flex items-center gap-2">
                         <button type="button" onclick="AdminImpersonationModal.show(this)" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-primary-600 text-[9px] font-black uppercase tracking-wider border border-primary-100 hover:bg-primary-50 transition shadow-sm active:scale-95">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Impersonate
                        </button>
                        <button type="button" onclick="AdminDelegationModal.show(this)" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-600 text-white text-[9px] font-black uppercase tracking-wider shadow-sm hover:bg-primary-700 transition active:scale-95">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v6m3.374-1.948a11.967 11.967 0 01-1.921 6.103m-3.354 3.354a11.969 11.969 0 01-6.103 1.921m-6.103-1.921a11.967 11.967 0 01-3.354-3.354m-1.921-6.103a11.967 11.967 0 011.921-6.103m3.354-3.354a11.969 11.969 0 016.103-1.921m6.103 1.921a11.967 11.967 0 013.354 3.354" />
                            </svg>
                            Delegate
                        </button>
                    </div>
                </div>
                <div class="p-6 space-y-6 flex-1">
                    <div>
                        <h3 class="text-[10px] font-black text-[var(--ui-text-muted)] uppercase tracking-widest mb-4">Active Access Sessions</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-primary-50/30 border border-primary-100">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-primary-600 flex items-center justify-center font-bold text-white text-xs">AT</div>
                                    <div>
                                        <div class="text-[13px] font-bold text-[var(--ui-text)] leading-none">Aris Thorne <span class="text-[11px] font-medium text-[var(--ui-text-muted)] mx-1">impersonating</span> Marcus Wright</div>
                                        <div class="text-[10px] font-medium text-primary-600 mt-1">Session ends in 42:15</div>
                                    </div>
                                </div>
                                <button class="text-primary-600 hover:text-primary-800">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 017.5 5.25h9a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9a2.25 2.25 0 01-2.25-2.25v-9z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-[var(--ui-surface-subtle)]/50 border border-[var(--ui-border)]">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-[var(--ui-surface-subtle)] flex items-center justify-center font-bold text-[var(--ui-text-muted)] text-xs">SM</div>
                                    <div>
                                        <div class="text-[13px] font-bold text-[var(--ui-text)] leading-none">Sara Miller <span class="text-[11px] font-medium text-[var(--ui-text-muted)] mx-1">acting as</span> Admin Delegate</div>
                                        <div class="text-[10px] font-medium text-[var(--ui-text-muted)] mt-1">Expires on Dec 12, 2023</div>
                                    </div>
                                </div>
                                <button class="text-[var(--ui-text-muted)]/40 hover:text-rose-500 transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 flex gap-4">
                        <div class="shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-white shadow-sm text-amber-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-[11px] font-black text-amber-900 uppercase tracking-widest">Security Audit Active</div>
                            <p class="text-[11px] font-medium text-amber-700/80 leading-relaxed mt-1 italic">
                                "Every session is cryptographically logged and requires a reason string to be entered upon initialization."
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    {{-- Modal Partials --}}
    @include('admin.RolePermissions.add-permission')
    @include('admin.RolePermissions.add-override')
    @include('admin.RolePermissions.add-delegation')
    @include('admin.RolePermissions.grant-impersonation')
    @include('admin.RolePermissions.add-role')

@endsection



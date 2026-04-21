@extends('admin.layout')

@section('title', 'Roles & Permissions Management - Biogenix Admin')

@section('admin_content')
@php
    $stats = [
        ['label' => 'Active Roles', 'value' => '24', 'meta' => '+3 this quarter', 'tone' => 'primary'],
        ['label' => 'Mapped Permissions', 'value' => '186', 'meta' => 'Across 4 core domains', 'tone' => 'slate'],
        ['label' => 'Live Delegates', 'value' => '08', 'meta' => '2 expiring in 24h', 'tone' => 'secondary'],
        ['label' => 'Security Health', 'value' => '99.8%', 'meta' => 'Audit ready', 'tone' => 'primary'],
    ];

    $permissionGroups = [
        [
            'title' => 'Data Operations',
            'subtitle' => 'Functional authorization layer',
            'iconPath' => 'M3.75 5.25h16.5v13.5H3.75zm4.5 3.75h7.5m-7.5 3h4.5',
            'permissions' => [
                ['label' => 'Read Scientific Datasets', 'checked' => true],
                ['label' => 'Write Scientific Datasets', 'checked' => true],
                ['label' => 'Execute Batch Queries', 'checked' => false],
            ],
        ],
        [
            'title' => 'Lab Controls',
            'subtitle' => 'Instrument and process access',
            'iconPath' => 'M6 4.5h12m-9 0v15m6-15v15M6 9h12M6 14h12',
            'permissions' => [
                ['label' => 'Environmental Override', 'checked' => true],
                ['label' => 'Instrument Calibration', 'checked' => false],
                ['label' => 'Hazard Containment Log', 'checked' => true],
            ],
        ],
        [
            'title' => 'System Admin',
            'subtitle' => 'Infrastructure operations',
            'iconPath' => 'M9.75 3v3m4.5-3v3M4.5 9h15M6.75 21h10.5A2.25 2.25 0 0019.5 18.75V8.25A2.25 2.25 0 0017.25 6H6.75A2.25 2.25 0 004.5 8.25v10.5A2.25 2.25 0 006.75 21Z',
            'permissions' => [
                ['label' => 'Edit Audit Protocols', 'checked' => false],
                ['label' => 'Flush Cache Layers', 'checked' => false],
                ['label' => 'View System Logs', 'checked' => true],
            ],
        ],
        [
            'title' => 'Security',
            'subtitle' => 'Policy enforcement matrix',
            'iconPath' => 'M12 3.75 19.5 6v5.25c0 4.526-3.067 8.478-7.5 9.75-4.433-1.272-7.5-5.224-7.5-9.75V6L12 3.75Z',
            'permissions' => [
                ['label' => '2FA Enforcement', 'checked' => true],
                ['label' => 'API Token Rotation', 'checked' => true],
                ['label' => 'IP Whitelisting', 'checked' => false],
            ],
        ],
    ];

    $overrides = [
        ['initials' => 'EV', 'name' => 'Dr. Elena Vance', 'role' => 'Scientific Lead', 'permission' => 'Admin Console Access', 'status' => 'Active'],
        ['initials' => 'MT', 'name' => 'Marcus Thorne', 'role' => 'Facility Manager', 'permission' => 'Hazard Override', 'status' => 'Pending'],
        ['initials' => 'JV', 'name' => 'Julian Vance', 'role' => 'Lab Associate III', 'permission' => 'Bulk Data Export', 'status' => 'Active'],
    ];

    $delegates = [
        ['name' => 'Sarah Connor', 'email' => 's.connor@biogenix.io', 'role' => 'Compliance Auditor', 'expiry' => '24 May 2024', 'status' => 'Expired'],
        ['name' => 'Julian Blackwood', 'email' => 'j.blackwood@partner.io', 'role' => 'External Reviewer', 'expiry' => '30 Oct 2024', 'status' => 'Active'],
        ['name' => 'Priya Nair', 'email' => 'p.nair@auditlabs.io', 'role' => 'Temporary Partner', 'expiry' => '12 Nov 2024', 'status' => 'Scheduled'],
    ];

    $impersonations = [
        ['initiator' => 'Admin Root', 'target' => 'Dr. Elena Vance', 'started' => '14 Oct 2023 09:12', 'duration' => '45 mins', 'action' => 'Full Console Access', 'status' => 'Archived'],
        ['initiator' => 'Support Lead', 'target' => 'Marcus Thorne', 'started' => '12 Oct 2023 14:30', 'duration' => '20 mins', 'action' => 'Read Only View', 'status' => 'Archived'],
        ['initiator' => 'Compliance Head', 'target' => 'Sarah Connor', 'started' => '08 Oct 2023 11:05', 'duration' => '30 mins', 'action' => 'Audit Walkthrough', 'status' => 'Live'],
    ];

    $toneClasses = [
        'primary' => 'bg-primary-50 text-primary-600',
        'slate' => 'bg-slate-100 text-slate-700',
        'secondary' => 'bg-secondary-50 text-secondary-900',
    ];
@endphp

<div class="mx-auto max-w-[1320px] space-y-6">
    <section class="overflow-hidden rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-8 xl:flex-row xl:items-start xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-primary-600">IAM CONTROL PLANE</p>
                <h1 class="mt-2 text-[22px] font-extrabold tracking-tight text-slate-900 font-display">Roles & Permissions Management</h1>
                <p class="mt-2 text-[14px] font-medium leading-6 text-slate-500">
                    Configure global access controls, role hierarchies, and identity-specific exceptions from one unified workspace.
                </p>
            </div>

            <div class="w-full max-w-[620px] space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search IAM entities..." class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" data-role-modal-open="addUserModal" class="h-11 px-6 rounded-xl border border-slate-200 text-[13px] font-bold text-slate-700 hover:bg-slate-50 transition active:scale-95">
                            Add User
                        </button>
                        <button type="button" data-role-modal-open="addRoleModal" class="h-11 px-6 rounded-xl bg-primary-600 text-white text-[13px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95">
                            Create Role
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-2xl border border-slate-50 bg-slate-50/30 p-5 group hover:bg-white hover:shadow-xl hover:shadow-slate-200/40 transition-all duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $stat['label'] }}</p>
                            <p class="mt-3 text-[32px] font-extrabold tracking-tight text-slate-900 font-display">{{ $stat['value'] }}</p>
                            <p class="mt-1 text-[11px] font-bold text-slate-500">{{ $stat['meta'] }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl text-[11px] font-black {{ $toneClasses[$stat['tone']] }}">
                            {{ substr($stat['label'], 0, 2) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="role-panel">
        <div class="role-panel-header">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[19px] font-bold text-slate-900 tracking-tight font-display">Permission Mapping Matrix</h2>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">AUTHORIZATION CLUSTERS</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative w-full sm:w-64">
                        <select class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-10 text-[13px] font-bold text-slate-700 outline-none focus:border-primary-600 appearance-none cursor-pointer">
                            <option>Scientific Lead</option>
                            <option>Facility Manager</option>
                            <option>Compliance Auditor</option>
                            <option>Support Lead</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <button class="h-11 w-full sm:w-auto px-6 rounded-xl bg-primary-600 text-white text-[13px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95">Save Changes</button>
                </div>
            </div>
        </div>

        <div class="grid gap-6 p-8 lg:grid-cols-2 xl:grid-cols-4">
            @foreach ($permissionGroups as $group)
                <div class="rounded-2xl border border-slate-100 bg-white p-5 hover:border-primary-100 hover:shadow-lg hover:shadow-primary-600/5 transition-all">
                    <div class="flex items-center gap-3 mb-5 pb-5 border-b border-slate-50">
                        <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $group['iconPath'] }}" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-bold text-slate-900 leading-tight">{{ $group['title'] }}</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $group['subtitle'] }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach ($group['permissions'] as $permission)
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" {{ $permission['checked'] ? 'checked' : '' }} class="mt-1 h-4.5 w-4.5 rounded border-slate-300 text-primary-600 focus:ring-primary-600 transition">
                                <span class="text-[13px] font-bold text-slate-600 group-hover:text-slate-900 transition">{{ $permission['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- User Role Overrides (full-width, with Extra Permission column) --}}
    <section class="role-panel">
        <div class="role-panel-header flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-slate-900 font-display leading-none">User Role Overrides</h2>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">ACTIVE TEMPORARY ESCALATIONS</p>
                </div>
            </div>
            <button type="button" data-role-modal-open="addOverrideModal" class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg border border-slate-200 text-slate-700 text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                ADD USER ROLE OVERRIDE
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="role-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role Assigned</th>
                        <th>Extra Permission</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($overrides as $override)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-[12px] font-black">{{ $override['initials'] }}</div>
                                    <div class="text-[13px] font-bold text-slate-900">{{ $override['name'] }}</div>
                                </div>
                            </td>
                            <td><span class="role-tag">{{ $override['role'] }}</span></td>
                            <td class="text-[13px] font-medium text-slate-700">{{ $override['permission'] }}</td>
                            <td><span class="role-status-badge {{ $override['status'] === 'Active' ? 'role-status-active' : 'role-status-pending' }}">{{ $override['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Delegated Access Control (full-width, with Email + Actions) --}}
    <section class="role-panel">
        <div class="role-panel-header flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-secondary-50 flex items-center justify-center" style="color:#92400e">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-slate-900 font-display leading-none">Delegated Access Control</h2>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">SECONDARY AUTHORIZATION TOKENS</p>
                </div>
            </div>
            <button type="button" data-role-modal-open="addDelegationModal" class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg border border-slate-200 text-slate-700 text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                ADD DELEGATED ROLE
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="role-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Expiry</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($delegates as $delegate)
                        <tr>
                            <td class="text-[13px] font-bold text-slate-900">{{ $delegate['name'] }}</td>
                            <td class="text-[12px] font-medium text-slate-500">{{ $delegate['email'] }}</td>
                            <td class="text-[12px] font-semibold text-slate-700">{{ $delegate['role'] }}</td>
                            <td>
                                <span class="text-[12px] font-bold {{ $delegate['status'] === 'Active' ? 'text-primary-600' : 'text-slate-600' }}">{{ $delegate['expiry'] }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="role-status-badge {{ $delegate['status'] === 'Active' ? 'role-status-active' : ($delegate['status'] === 'Expired' ? 'role-status-muted' : 'role-status-pending') }}">{{ $delegate['status'] }}</span>
                                    @if($delegate['status'] !== 'Expired')
                                        <button class="h-7 w-7 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-400 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356M2.977 14.652H7.97v4.992m12.573-3.434A9 9 0 116.32 5.106l1.65 1.65" /></svg>
                                        </button>
                                    @endif
                                    <button class="h-7 w-7 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-600 hover:border-red-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="role-panel">
        <div class="role-panel-header flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-slate-900 font-display leading-none">Impersonation Control Cluster</h2>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">ADMINISTRATIVE SHADOW ACCESS LOGS</p>
                </div>
            </div>
            <button type="button" data-role-modal-open="impersonationModal" class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg bg-primary-600 text-white text-[11px] font-black uppercase tracking-widest hover:bg-primary-700 transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                ADD NEW IMPERSONATION
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="role-table">
                <thead>
                    <tr>
                        <th>Initiator</th>
                        <th>Target User</th>
                        <th>Start Time</th>
                        <th>Duration</th>
                        <th>Action Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($impersonations as $imp)
                        <tr>
                            <td class="text-[13px] font-bold text-slate-900">{{ $imp['initiator'] }}</td>
                            <td class="text-[13px] font-semibold text-slate-600">{{ $imp['target'] }}</td>
                            <td class="text-[12px] font-medium text-slate-500">{{ $imp['started'] }}</td>
                            <td class="text-[12px] font-medium text-slate-500">{{ $imp['duration'] }}</td>
                            <td class="text-[12px] font-semibold text-slate-700">{{ $imp['action'] }}</td>
                            <td><span class="role-status-badge {{ $imp['status'] === 'Live' ? 'role-status-active' : 'role-status-muted' }}">{{ $imp['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

@include('admin.RolePermissions.modals.all-modals')

<script>
    (function () {
        const modalIds = ['addRoleModal', 'addUserModal', 'addOverrideModal', 'addDelegationModal', 'impersonationModal'];

        function syncRoleModals() {
            const mainContent = document.getElementById('admin-main-content');
            modalIds.forEach((id) => {
                const liveModal = mainContent ? mainContent.querySelector(`#${id}`) : null;
                const bodyModal = document.querySelector(`body > #${id}`);
                if (bodyModal && liveModal && bodyModal !== liveModal) { bodyModal.remove(); }
                const modal = liveModal || document.getElementById(id);
                if (modal && modal.parentNode !== document.body) { document.body.appendChild(modal); }
            });
        }

        function updateOverrideCount() {
            const grid = document.querySelector('#addOverrideModal [data-override-permission-grid]');
            const counter = document.querySelector('#addOverrideModal [data-override-selected-count]');
            if (!grid || !counter) return;
            const count = grid.querySelectorAll('input[type="checkbox"]:checked').length;
            counter.textContent = `${count} OVERWRITE${count === 1 ? '' : 'S'} ACTIVE`;
        }

        window.RoleModals = window.RoleModals || {};
        Object.assign(window.RoleModals, {
            ids: modalIds,
            init() {
                syncRoleModals();
                updateOverrideCount();
                if (window.__roleModalEventsBound) return;
                document.addEventListener('click', (event) => {
                    const openTrigger = event.target.closest('[data-role-modal-open]');
                    if (openTrigger) { event.preventDefault(); this.open(openTrigger.getAttribute('data-role-modal-open')); return; }
                    const closeTrigger = event.target.closest('[data-role-modal-close]');
                    if (closeTrigger) { event.preventDefault(); const modal = closeTrigger.closest('[data-role-modal-root]'); if (modal) this.close(modal.id); return; }
                    const backdrop = event.target.closest('[data-modal-backdrop]');
                    if (backdrop) { const modal = backdrop.closest('[data-role-modal-root]'); if (modal) this.close(modal.id); return; }
                });
                document.addEventListener('keydown', (event) => { if (event.key === 'Escape') this.closeAll(); });
                document.addEventListener('change', (event) => { if (event.target.closest('#addOverrideModal [data-override-permission-grid]')) updateOverrideCount(); });
                window.__roleModalEventsBound = true;
            },
            open(id) {
                syncRoleModals();
                const modal = document.getElementById(id);
                if (!modal) return;
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                const backdrop = modal.querySelector('[data-modal-backdrop]');
                const dialog = modal.querySelector('[data-modal-dialog]');
                requestAnimationFrame(() => {
                    if (backdrop) backdrop.classList.replace('opacity-0', 'opacity-100');
                    if (dialog) dialog.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                    if (dialog) dialog.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                });
                const focusTarget = modal.querySelector('[data-role-modal-autofocus]');
                if (focusTarget) setTimeout(() => focusTarget.focus(), 140);
            },
            close(id) {
                const modal = document.getElementById(id);
                if (!modal) return;
                const backdrop = modal.querySelector('[data-modal-backdrop]');
                const dialog = modal.querySelector('[data-modal-dialog]');
                if (backdrop) backdrop.classList.replace('opacity-100', 'opacity-0');
                if (dialog) dialog.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    if (![...document.querySelectorAll('[data-role-modal-root]')].some(m => !m.classList.contains('hidden'))) {
                        document.body.classList.remove('overflow-hidden');
                    }
                }, 300);
            },
            closeAll() { this.ids.forEach(id => this.close(id)); }
        });
        window.RoleModals.init();
    })();
</script>

<style>
    .role-panel {
        overflow: hidden;
        border-radius: 24px;
        background: #fff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.03);
    }
    .role-panel-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f8fafc;
    }
    .role-table {
        width: 100%;
        border-collapse: collapse;
    }
    .role-table th {
        padding: 0.85rem 2rem;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #94a3b8;
        background: #fbfcfe;
        text-align: left;
    }
    .role-table td {
        padding: 1.15rem 2rem;
        border-top: 1px solid #f8fafc;
        vertical-align: middle;
    }
    .role-tag {
        display: inline-flex;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        background: #f1f5f9;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #475569;
    }
    .role-status-badge {
        display: inline-flex;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .role-status-active { background: #f0faf4; color: #1a4d2e; }
    .role-status-pending { background: #fffbeb; color: #92400e; }
    .role-status-muted { background: #f8fafc; color: #64748b; }
</style>
@endsection

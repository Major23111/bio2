@extends('admin.layout')

@section('title', 'Roles & Permissions Management - Biogenix Admin')

@section('admin_content')
@php
    // CSS tone classes for stats cards
    $toneClasses = [
        'primary' => 'bg-primary-50 text-primary-600',
        'slate' => 'bg-slate-100 text-slate-700',
        'secondary' => 'bg-secondary-50 text-secondary-900',
    ];
@endphp

<div class="mx-auto max-w-[1320px] space-y-6">
    <section class="overflow-hidden rounded-[28px] border border-[var(--ui-border)] bg-[var(--ui-surface)] p-6 shadow-[var(--ui-shadow-soft)] sm:p-8">
        <div class="flex flex-col gap-8 xl:flex-row xl:items-start xl:justify-between">
            <div class="max-w-2xl">
                <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900 font-display">Roles & Permissions Management</h1>
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
    </section>

    <form method="POST" action="{{ route('admin.role-permission.matrix.save') }}" class="overflow-hidden rounded-[24px] bg-[var(--ui-surface)] border border-[var(--ui-border)] shadow-[var(--ui-shadow-soft)]">
        @csrf
        <div class="px-8 py-6 border-b border-slate-50">
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
                        @php
                            $selectedRoleId = (int) old('selected_role_id', $selectedRole?->id);
                        @endphp
                        <select
                            name="selected_role_id"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-4 text-[13px] font-bold text-slate-700 outline-none focus:border-primary-600 appearance-none cursor-pointer"
                            data-role-switch-select
                            data-role-switch-url="{{ route('admin.role-permission') }}"
                            required
                        >
                            <option value="">Select a role...</option>
                            @forelse ($roles as $role)
                                <option value="{{ $role->id }}" {{ $selectedRoleId === (int) $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @empty
                                <option value="" disabled>No roles available</option>
                            @endforelse
                        </select>
                    </div>
                    <button type="submit" class="h-11 w-full sm:w-auto px-6 rounded-xl bg-primary-600 text-white text-[13px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95">Save Changes</button>
                </div>
            </div>
        </div>

        <div class="grid gap-6 p-8 lg:grid-cols-2 xl:grid-cols-4">
            @foreach ($permissionGroups as $group)
                <div class="rounded-2xl border border-slate-100 bg-[var(--ui-surface)] p-5 hover:border-primary-100 hover:shadow-lg hover:shadow-primary-600/5 transition-all">
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
                                <input type="checkbox" name="permission_ids[]" value="{{ $permission['id'] }}" {{ $permission['checked'] ? 'checked' : '' }} class="mt-1 h-4.5 w-4.5 rounded border-slate-300 text-primary-600 focus:ring-primary-600 transition">
                                <span class="text-[13px] font-bold text-slate-600 group-hover:text-slate-900 transition">{{ $permission['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    {{-- User Role Overrides (full-width, with Extra Permission column) --}}
    <section class="overflow-hidden rounded-[24px] bg-[var(--ui-surface)] border border-[var(--ui-border)] shadow-[var(--ui-shadow-soft)]">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-slate-900 font-display leading-none">User Permission Overrides</h2>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">ACTIVE TEMPORARY ESCALATIONS</p>
                </div>
            </div>
            <button type="button" data-role-modal-open="addOverrideModal" class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg border border-slate-200 text-slate-700 text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                ADD USER PERMISSION OVERRIDE
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">User</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Role Assigned</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Extra Permission</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($overrides as $override)
                        <tr>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-[12px] font-black">{{ $override['initials'] }}</div>
                                    <div class="text-[13px] font-bold text-slate-900">{{ $override['name'] }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle">
                                <span class="inline-flex px-3 py-1.5 rounded-lg bg-slate-100 text-[11px] font-extrabold uppercase text-slate-600">{{ $override['role'] }}</span>
                            </td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[13px] font-medium text-slate-700">{{ $override['permission'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle">
                                <span class="inline-flex px-3 py-1.5 rounded-md text-[10px] font-extrabold uppercase tracking-wide {{ $override['status'] === 'Active' ? 'bg-[#f0faf4] text-primary-800' : 'bg-amber-50 text-amber-800' }}">{{ $override['status'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Delegated Access Control (full-width, with Email + Actions) --}}
    <section class="overflow-hidden rounded-[24px] bg-[var(--ui-surface)] border border-[var(--ui-border)] shadow-[var(--ui-shadow-soft)]">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
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
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">User</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Email</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Role</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Expiry</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($delegates as $delegate)
                        <tr>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[13px] font-bold text-slate-900">{{ $delegate['name'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[12px] font-medium text-slate-500">{{ $delegate['email'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[12px] font-semibold text-slate-700">{{ $delegate['role'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle">
                                <span class="text-[12px] font-bold {{ $delegate['status'] === 'Active' ? 'text-primary-600' : 'text-slate-600' }}">{{ $delegate['expiry'] }}</span>
                            </td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex px-3 py-1.5 rounded-md text-[10px] font-extrabold uppercase tracking-wide {{ $delegate['status'] === 'Active' ? 'bg-[#f0faf4] text-primary-800' : ($delegate['status'] === 'Expired' ? 'bg-slate-100 text-slate-600' : 'bg-amber-50 text-amber-800') }}">{{ $delegate['status'] }}</span>
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

    <section class="overflow-hidden rounded-[24px] bg-[var(--ui-surface)] border border-[var(--ui-border)] shadow-[var(--ui-shadow-soft)]">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
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
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Initiator</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Target User</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Start Time</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Duration</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Status</th>
                        <th class="px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 bg-slate-50/50 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($impersonations as $imp)
                        <tr>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[13px] font-bold text-slate-900">{{ $imp['initiator'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[13px] font-semibold text-slate-600">{{ $imp['target'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[12px] font-medium text-slate-500">{{ $imp['started'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle text-[12px] font-medium text-slate-500">{{ $imp['duration'] }}</td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle">
                                <span class="inline-flex px-3 py-1.5 rounded-md text-[10px] font-extrabold uppercase tracking-wide {{ $imp['status'] === 'Live' ? 'bg-[#f0faf4] text-primary-800' : 'bg-slate-100 text-slate-600' }}">{{ $imp['status'] }}</span>
                            </td>
                            <td class="px-8 py-5 border-t border-slate-50 align-middle">
                                @if ($imp['is_active'])
                                    {{-- Show Stop button only for sessions that are still active --}}
                                    <form method="POST" action="{{ route('admin.role-permission.impersonations.stop', $imp['session_id']) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 h-7 px-3 rounded-lg border border-red-200 text-red-600 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition active:scale-95">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            Stop
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] font-medium text-slate-300">Ended</span>
                                @endif
                            </td>
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
                document.addEventListener('change', (event) => {
                    const roleSwitchSelect = event.target.closest('[data-role-switch-select]');
                    if (roleSwitchSelect) {
                        const roleId = roleSwitchSelect.value;
                        const baseUrl = roleSwitchSelect.getAttribute('data-role-switch-url');
                        if (!roleId || !baseUrl) return;
                        const nextUrl = new URL(baseUrl, window.location.origin);
                        nextUrl.searchParams.set('role_id', roleId);
                        window.location.href = nextUrl.toString();
                        return;
                    }
                    if (event.target.closest('#addOverrideModal [data-override-permission-grid]')) updateOverrideCount();
                });
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

</style>
@endsection

@php
    $roleModalPermissions = [
        'View Catalog', 'Edit Product Details', 'Manage Inventory', 'Delete Inventory',
        'Process Returns', 'Fulfill Orders', 'View Revenue Reports', 'Approve Refunds',
        'Manage Tax Settings', 'Export User Data', 'Modify API Keys', 'View System Health',
    ];
@endphp

{{-- Create New Role Modal --}}
<div id="create-role-modal" class="fixed inset-0 z-[9999]" style="display: none;" aria-hidden="true">
    <div id="role-modal-backdrop" class="absolute inset-0 bg-slate-950/60 opacity-0 backdrop-blur-sm transition-opacity duration-300"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4 py-8 pointer-events-none">
        <div id="role-modal-dialog"
            class="pointer-events-auto relative flex max-h-[85vh] w-[95%] md:w-[780px] translate-y-4 scale-95 flex-col overflow-hidden rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            style="width: 780px; max-width: 95%;">

            <!-- Fixed Header -->
            <div class="shrink-0 border-b border-slate-100 bg-white px-8 py-4 z-10">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-black tracking-tight text-[#0A1633]">Create New Role</h2>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">Define system access levels and functional permissions.</p>
                    </div>
                    <button id="role-modal-close-btn" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <form id="create-role-form" class="flex flex-col min-h-0">
                <!-- Scrollable Body -->
                <div class="flex-1 overflow-y-auto px-8 py-5 space-y-5 scrollbar-thin scrollbar-thumb-slate-200">
                    <!-- Section 01: Basic Info -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-500 whitespace-nowrap">Section 01</span>
                            <div class="h-px flex-1 bg-slate-100"></div>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-300 whitespace-nowrap">Basic Configuration</span>
                        </div>
                        <div class="grid gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-tight">Role Name <span class="text-rose-500">*</span></label>
                                <input id="role-modal-name" type="text" placeholder="e.g. Regional Finance Auditor" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3.5 text-xs font-medium outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-tight">Role Description</label>
                                <textarea id="role-modal-description" rows="2" placeholder="Describe responsibilities..." class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-medium outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20 resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 02: Permissions -->
                    <div class="space-y-4 pt-1">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-500">Section 02</span>
                                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-300">Permissions Matrix</span>
                            </div>
                            <div class="relative w-40">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </span>
                                <input id="permission-search" type="text" placeholder="Search..." class="h-7.5 w-full rounded-lg border border-slate-200 bg-white pl-8 pr-3 text-[10px] font-semibold outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20 shadow-sm shadow-slate-200/30">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <!-- Permissions Header -->
                            <div class="flex items-center justify-between px-1">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-7 w-7 flex items-center justify-center rounded-lg bg-slate-50 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" /></svg>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Available Permissions</span>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer group bg-slate-50 hover:bg-slate-100 px-2.5 py-1.5 rounded-lg transition-colors border border-transparent hover:border-slate-200">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-600 transition tracking-tighter uppercase">Select All</span>
                                    <input id="select-all-permissions" type="checkbox" class="h-3.5 w-3.5 rounded border-slate-300 text-primary-600 focus:ring-primary-600/20">
                                </label>
                            </div>

                            <!-- Grid Layout -->
                            <div class="grid grid-cols-2 gap-x-8 gap-y-1 px-1 py-1">
                                @foreach ($roleModalPermissions as $permission)
                                <label class="flex items-center gap-3 py-2 transition cursor-pointer group hover:bg-slate-50/50 rounded-xl -mx-2 px-2">
                                    <input type="checkbox" class="permission-checkbox h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600/20">
                                    <span class="text-[12px] font-semibold text-slate-600 group-hover:text-[#0A1633] transition leading-tight">{{ $permission }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer -->
                <div class="shrink-0 border-t border-slate-100 bg-white px-8 py-4 flex items-center justify-end gap-5">
                    <button id="role-modal-cancel-btn" type="button" class="text-xs font-bold text-slate-400 hover:text-slate-800 transition-colors uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-primary-600 px-8 text-[11px] font-black uppercase tracking-widest text-white shadow-[0_16px_35px_-18px_rgba(26,77,46,0.35)] transition hover:bg-primary-700 active:scale-95">
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function initializeAdminRoleModal() {
    const modal = document.getElementById('create-role-modal');
    const backdrop = document.getElementById('role-modal-backdrop');
    const dialog = document.getElementById('role-modal-dialog');
    const form = document.getElementById('create-role-form');
    const input = document.getElementById('role-modal-name');
    const search = document.getElementById('permission-search');
    const selectAll = document.getElementById('select-all-permissions');
    const checkboxes = Array.from(modal.querySelectorAll('.permission-checkbox'));

    window.AdminRoleModal = {
        isOpen: false,
        show() {
            this.isOpen = true;
            modal.style.display = 'block';
            document.body.classList.add('overflow-hidden');
            modal.offsetHeight;
            requestAnimationFrame(() => {
                backdrop.classList.replace('opacity-0', 'opacity-100');
                dialog.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                dialog.classList.add('opacity-100', 'translate-y-0', 'scale-100');
            });
            setTimeout(() => input.focus(), 150);
        },
        close() {
            this.isOpen = false;
            backdrop.classList.replace('opacity-100', 'opacity-0');
            dialog.classList.replace('opacity-100', 'opacity-0');
            dialog.classList.add('translate-y-4', 'scale-95');
            document.body.classList.remove('overflow-hidden');
            setTimeout(() => { if(!this.isOpen) modal.style.display = 'none'; }, 300);
        }
    };

    document.getElementById('role-modal-close-btn')?.addEventListener('click', () => AdminRoleModal.close());
    document.getElementById('role-modal-cancel-btn')?.addEventListener('click', () => AdminRoleModal.close());
    backdrop.addEventListener('click', () => AdminRoleModal.close());

    // Simple search & select all logic
    search?.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        checkboxes.forEach(cb => {
            const label = cb.closest('label');
            label.style.display = label.textContent.toLowerCase().includes(q) ? 'flex' : 'none';
        });
    });

    selectAll?.addEventListener('change', (e) => {
        checkboxes.forEach(cb => {
            if (cb.closest('label').style.display !== 'none') cb.checked = e.target.checked;
        });
    });
})();
</script>

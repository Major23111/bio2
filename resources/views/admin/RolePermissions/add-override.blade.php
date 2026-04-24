{{-- Add Individual Override Modal --}}
<div id="add-override-modal" class="fixed inset-0 z-[9999]" style="display: none;" aria-hidden="true">
    <div id="override-modal-backdrop" class="absolute inset-0 bg-slate-950/60 opacity-0 backdrop-blur-sm transition-opacity duration-300"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4 py-8 pointer-events-none">
        <div id="override-modal-dialog"
            class="pointer-events-auto relative flex max-h-[85vh] w-[95%] md:w-[780px] translate-y-4 scale-95 flex-col overflow-hidden rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            style="width: 780px; max-width: 95%;">

            <!-- Fixed Header -->
            <div class="shrink-0 border-b border-slate-100 bg-white px-8 py-4 z-10">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-black tracking-tight text-[#0A1633]">Add Individual Override</h2>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">Specify granular permissions for a specific user that supersede their assigned role.</p>
                    </div>
                    <button id="override-modal-close-btn" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <form id="add-override-form" class="flex flex-col min-h-0">
                <!-- Scrollable Body -->
                <div class="flex-1 overflow-y-auto px-8 py-5 space-y-4 scrollbar-thin scrollbar-thumb-slate-200">
                    <!-- Select User -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-tight">Select User</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-primary-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input id="override-user-search" type="text" placeholder="Search by name, email, or employee ID..." class="h-9 w-full rounded-lg border border-slate-200 bg-white pl-10 pr-4 text-xs font-semibold outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20">
                        </div>
                    </div>

                    <!-- Permissions Area -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Functional Permissions</h3>
                        </div>

                        <div class="space-y-2">
                            @php
                                $overrideOptions = [
                                    ['title' => 'View Revenue', 'desc' => 'Access to all financial reporting and sales analytics.', 'icon' => 'M12 6v12m-3-2.818l.879.659c1.546 1.16 3.743 1.16 5.289 0m-5.289-8.402l.879-.659c1.546-1.16 3.743-1.16 5.289 0m-5.289 8.402a1.5 1.5 0 01-3.322-2.527L12 12m0 0l2-2'],
                                    ['title' => 'Manage API', 'desc' => 'Generate secret keys and configure system webhooks.', 'icon' => 'M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z'],
                                    ['title' => 'Delete Inventory', 'desc' => 'Hard-delete item records from the production database.', 'icon' => 'M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0'],
                                    ['title' => 'Modify Roles', 'desc' => 'Edit system roles and adjust permission clusters.', 'icon' => 'M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818l5.73-5.73a1.5 1.5 0 00.43-1.563 6 6 0 1110.89-4.59z'],
                                ];
                            @endphp

                            @foreach ($overrideOptions as $opt)
                            <label class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 hover:bg-slate-50 transition cursor-pointer group">
                                <div class="pt-0.5">
                                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600/10 transition-all">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700 group-hover:text-[#0A1633] transition">{{ $opt['title'] }}</span>
                                        <svg class="h-4 w-4 text-slate-300 group-hover:text-primary-600/40 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $opt['icon'] }}" />
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-medium text-slate-400 mt-0.5 leading-relaxed">{{ $opt['desc'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <!-- Policy Note -->
                        <div class="flex items-start gap-3.5 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center shadow-sm text-primary-600 shrink-0">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                            </div>
                            <p class="text-[10px] font-medium text-slate-500 leading-relaxed italic">
                                "Overrides apply immediately and are tracked in the <span class="text-primary-600 font-bold not-italic">Security Audit Log</span>. Excessive overrides may trigger system-level alerts."
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer -->
                <div class="shrink-0 border-t border-slate-100 bg-white px-8 py-4 flex items-center justify-end gap-5">
                    <button id="override-modal-cancel-btn" type="button" class="text-xs font-bold text-slate-400 hover:text-slate-800 transition-colors uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-primary-600 px-8 text-[11px] font-black uppercase tracking-widest text-white shadow-[0_16px_35px_-18px_rgba(26,77,46,0.35)] transition hover:bg-primary-700 active:scale-95">
                        Apply Override
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function initializeAdminOverrideModal() {
    const modal = document.getElementById('add-override-modal');
    const backdrop = document.getElementById('override-modal-backdrop');
    const dialog = document.getElementById('override-modal-dialog');
    const input = document.getElementById('override-user-search');

    window.AdminOverrideModal = {
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

    document.getElementById('override-modal-close-btn')?.addEventListener('click', () => AdminOverrideModal.close());
    document.getElementById('override-modal-cancel-btn')?.addEventListener('click', () => AdminOverrideModal.close());
    backdrop.addEventListener('click', () => AdminOverrideModal.close());
})();
</script>

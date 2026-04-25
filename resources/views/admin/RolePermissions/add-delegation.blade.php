{{-- Add Delegation Role Modal --}}
<div id="add-delegation-modal" class="fixed inset-0 z-[9999]" style="display: none;" aria-hidden="true">
    <div id="delegation-modal-backdrop" class="absolute inset-0 bg-slate-950/60 opacity-0 backdrop-blur-sm transition-opacity duration-300"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4 py-8 pointer-events-none">
        <div id="delegation-modal-dialog"
            class="pointer-events-auto relative flex max-h-[85vh] w-[95%] md:w-[780px] translate-y-4 scale-95 flex-col overflow-hidden rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            style="width: 780px; max-width: 95%;">

            <!-- Fixed Header -->
            <div class="shrink-0 border-b border-slate-100 bg-white px-8 py-4 z-10">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-black tracking-tight text-[#0A1633]">Add Delegation Role</h2>
                        <p class="mt-0.5 text-[10px] font-bold text-primary-600 uppercase tracking-widest">Temporary Access Grant</p>
                    </div>
                    <button id="delegation-modal-close-btn" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <form id="add-delegation-form" class="flex flex-col min-h-0">
                <!-- Scrollable Body -->
                <div class="flex-1 overflow-y-auto px-8 py-5 space-y-4 scrollbar-thin scrollbar-thumb-slate-200">
                    <!-- Select User -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Select User</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-primary-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input id="delegation-user-search" type="text" placeholder="Search by name or email..." class="h-9 w-full rounded-lg border border-slate-200 bg-white pl-10 pr-4 text-xs font-semibold outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20">
                        </div>
                    </div>

                    <!-- Role Assignment -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Role Assignment</label>
                        <select class="h-9 w-full rounded-lg border border-slate-200 bg-white px-4 pr-10 text-xs font-bold text-slate-700 outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20">
                            <option value="">Select a role to delegate...</option>
                            <option value="admin">Delegated Administrator</option>
                            <option value="editor">Content Moderator</option>
                            <option value="support">Senior Support Agent</option>
                        </select>
                    </div>

                    <!-- Expiration Date -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Expiration Date</label>
                        <div class="relative">
                            <input type="date" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20">
                        </div>
                        <div class="flex items-center gap-2.5 px-1 mt-1">
                            <svg class="h-3 w-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                            <p class="text-[9px] font-semibold text-slate-400">Access automatically revoked at 12:00 AM on this date.</p>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer -->
                <div class="shrink-0 border-t border-slate-100 bg-white px-8 py-4 flex items-center justify-end gap-5">
                    <button id="delegation-modal-cancel-btn" type="button" class="text-xs font-bold text-slate-400 hover:text-slate-800 transition-colors uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-primary-600 px-8 text-[11px] font-black uppercase tracking-widest text-white shadow-[0_16px_35px_-18px_rgba(26,77,46,0.35)] transition hover:bg-primary-700 active:scale-95">
                        Grant Access
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function initializeAdminDelegationModal() {
    const modal = document.getElementById('add-delegation-modal');
    const backdrop = document.getElementById('delegation-modal-backdrop');
    const dialog = document.getElementById('delegation-modal-dialog');
    const input = document.getElementById('delegation-user-search');

    window.AdminDelegationModal = {
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

    document.getElementById('delegation-modal-close-btn')?.addEventListener('click', () => AdminDelegationModal.close());
    document.getElementById('delegation-modal-cancel-btn')?.addEventListener('click', () => AdminDelegationModal.close());
    backdrop.addEventListener('click', () => AdminDelegationModal.close());
})();
</script>

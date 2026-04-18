{{-- Grant Impersonation Access Modal --}}
<div id="impersonation-modal" class="fixed inset-0 z-[9999]" style="display: none;" aria-hidden="true">
    <div id="impersonation-modal-backdrop" class="absolute inset-0 bg-slate-950/60 opacity-0 backdrop-blur-sm transition-opacity duration-300"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4 py-8 pointer-events-none">
        <div id="impersonation-modal-dialog"
            class="pointer-events-auto relative flex max-h-[85vh] w-[95%] md:w-[780px] translate-y-4 scale-95 flex-col overflow-hidden rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            style="width: 780px; max-width: 95%;">

            <!-- Fixed Header -->
            <div class="shrink-0 border-b border-slate-100 bg-white px-8 py-4 z-10">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="text-[9px] font-black text-primary-600 uppercase tracking-[0.2em] block mb-1">Administrative Action</span>
                        <h2 class="text-base font-black tracking-tight text-[#0A1633]">Grant Impersonation Access</h2>
                    </div>
                    <button id="impersonation-modal-close-btn" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <form id="impersonation-form" class="flex flex-col min-h-0">
                <!-- Scrollable Body -->
                <div class="flex-1 overflow-y-auto px-8 py-5 space-y-4 scrollbar-thin scrollbar-thumb-slate-200">
                    <!-- Target User -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Target User</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-primary-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </span>
                            <input id="impersonation-user-search" type="text" placeholder="marcus.wright@biocobalt.com" class="h-9 w-full rounded-lg border border-slate-200 bg-white pl-10 pr-4 text-xs font-semibold outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20">
                        </div>
                    </div>

                    <!-- Temporary Access Toggle -->
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/30">
                        <div class="flex gap-4">
                            <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-white shadow-sm text-slate-800">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <p class="text-[12px] font-black text-slate-800">Temporary Access</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-0.5">Session will expire automatically</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-10 h-5.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <!-- Access Duration Radio Group -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Access Duration</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative flex cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-slate-50 focus:outline-none transition group">
                                <input type="radio" name="duration" value="30m" class="sr-only peer">
                                <span class="flex flex-1 flex-col items-center gap-1">
                                    <span class="text-[10px] font-black text-slate-600 peer-checked:text-primary-600 group-hover:text-slate-900 transition uppercase tracking-tighter">30 Mins</span>
                                </span>
                                <span class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-primary-600 transition" aria-hidden="true"></span>
                            </label>
                            <label class="relative flex cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-slate-50 focus:outline-none transition group">
                                <input type="radio" name="duration" value="1h" class="sr-only peer" checked>
                                <span class="flex flex-1 flex-col items-center gap-1">
                                    <span class="text-[10px] font-black text-slate-600 peer-checked:text-primary-600 group-hover:text-slate-900 transition uppercase tracking-tighter">1 Hour</span>
                                </span>
                                <span class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-primary-600 transition" aria-hidden="true"></span>
                            </label>
                            <label class="relative flex cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-slate-50 focus:outline-none transition group">
                                <input type="radio" name="duration" value="4h" class="sr-only peer">
                                <span class="flex flex-1 flex-col items-center gap-1">
                                    <span class="text-[10px] font-black text-slate-600 peer-checked:text-primary-600 group-hover:text-slate-900 transition uppercase tracking-tighter">4 Hours</span>
                                </span>
                                <span class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-primary-600 transition" aria-hidden="true"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Audit Policy Alert -->
                    <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-100/50 flex gap-4">
                        <div class="shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-white shadow-sm text-amber-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-[11px] font-black text-amber-900 uppercase tracking-widest">Audit Policy</div>
                            <p class="text-[10px] font-medium text-amber-700/80 leading-relaxed mt-1">
                                All actions performed during this impersonation session will be <span class="font-bold">logged and tied to your administrator ID</span> for security compliance.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer -->
                <div class="shrink-0 border-t border-slate-100 bg-white px-8 py-4 flex items-center justify-end gap-5">
                    <button id="impersonation-modal-cancel-btn" type="button" class="text-xs font-bold text-slate-400 hover:text-slate-800 transition-colors uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-primary-600 px-8 text-[11px] font-black uppercase tracking-widest text-white shadow-[0_16px_35px_-18px_rgba(26,77,46,0.35)] transition hover:bg-primary-700 active:scale-95">
                        Grant Access
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function initializeAdminImpersonationModal() {
    const modal = document.getElementById('impersonation-modal');
    const backdrop = document.getElementById('impersonation-modal-backdrop');
    const dialog = document.getElementById('impersonation-modal-dialog');
    const input = document.getElementById('impersonation-user-search');

    window.AdminImpersonationModal = {
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

    document.getElementById('impersonation-modal-close-btn')?.addEventListener('click', () => AdminImpersonationModal.close());
    document.getElementById('impersonation-modal-cancel-btn')?.addEventListener('click', () => AdminImpersonationModal.close());
    backdrop.addEventListener('click', () => AdminImpersonationModal.close());
})();
</script>

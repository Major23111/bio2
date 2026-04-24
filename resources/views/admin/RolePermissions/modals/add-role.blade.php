<div id="addRoleModal" class="fixed inset-0 z-[9999] hidden" data-role-modal-root aria-hidden="true">
    <div class="absolute inset-0 bg-[#07162f]/55 opacity-0 backdrop-blur-sm transition-opacity duration-300" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div class="pointer-events-auto relative w-full max-w-lg translate-y-4 scale-95 opacity-0 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_32px_96px_rgba(15,23,42,0.18)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[92vh] overflow-y-auto role-modal-scroll" data-modal-dialog>
            <div class="flex items-start justify-between border-b border-slate-100 px-8 pb-6 pt-8">
                <div>
                    <h3 class="text-[19px] font-bold text-slate-900 tracking-tight leading-none mb-1.5 font-display">Add New Role</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">DEFINITION &amp; ACCESS PARAMETERS</p>
                </div>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" data-role-modal-close>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.role-permission.roles.store') }}" class="p-8 space-y-6">
                @csrf
                {{-- Role Name --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">ROLE NAME</label>
                    <input type="text" name="role_name" data-role-modal-autofocus placeholder="e.g. Security Auditor" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-4 focus:ring-primary-600/5" required>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">DESCRIPTION</label>
                    <textarea rows="4" name="role_description" placeholder="Describe the scope and limitations of this role within the IAM architecture..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[14px] font-medium text-slate-700 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-4 focus:ring-primary-600/5 resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-50">
                    <button type="button" class="h-11 px-6 text-[13px] font-bold text-slate-500 hover:text-slate-900 transition" data-role-modal-close>Discard</button>
                    <button type="submit" class="h-11 px-8 rounded-xl bg-primary-600 text-white text-[13px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95">Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

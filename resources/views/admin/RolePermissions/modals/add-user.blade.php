<div id="addUserModal" class="fixed inset-0 z-[9999] hidden" data-role-modal-root aria-hidden="true">
    <div class="absolute inset-0 bg-[#07162f]/55 opacity-0 backdrop-blur-sm transition-opacity duration-300" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div class="pointer-events-auto relative w-full max-w-xl translate-y-4 scale-95 opacity-0 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_32px_96px_rgba(15,23,42,0.18)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[92vh] overflow-y-auto role-modal-scroll" data-modal-dialog>
            <div class="flex items-start justify-between border-b border-slate-100 px-8 pb-6 pt-8">
                <div>
                    <h3 class="text-[19px] font-bold text-slate-900 tracking-tight leading-none mb-1.5 font-display">Add New User</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">IDENTITY &amp; ACCESS MANAGEMENT</p>
                </div>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" data-role-modal-close>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.role-permission.users.store') }}" class="p-8 space-y-5">
                @csrf
                {{-- Full Name --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">FULL NAME OF EMPLOYEE</label>
                    <input type="text" name="user_name" data-role-modal-autofocus placeholder="e.g. Jonathan Aris" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition" required>
                </div>

                {{-- Official Email --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">OFFICIAL EMAIL ADDRESS</label>
                    <div class="relative">
                        <input type="email" name="user_email" placeholder="j.aris@biogenix.com" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition" required>
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                </div>

                {{-- Phone Number + Employee ID --}}
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">PHONE NUMBER</label>
                        <input type="tel" name="user_phone" placeholder="+1 (555) 000-0000" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">EMPLOYEE ID</label>
                        <input type="text" name="employee_id" placeholder="BC-8892" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition" required>
                    </div>
                </div>

                {{-- Department --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">DEPARTMENT</label>
                    <div class="relative">
                        <select name="department_id" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition cursor-pointer appearance-none" required>
                            <option value="">Select Department</option>
                            @forelse ($departments as $department)
                                <option value="{{ $department->id }}" {{ (string) old('department_id') === (string) $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @empty
                                <option value="" disabled>No departments available</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                {{-- Select Role --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">SELECT ROLE</label>
                    <div class="relative">
                        <select name="role_id" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition cursor-pointer appearance-none" required>
                            <option value="">Select assigned permissions role</option>
                            @forelse ($roles as $role)
                                <option value="{{ $role->id }}" {{ (string) old('role_id') === (string) $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @empty
                                <option value="" disabled>No roles available</option>
                            @endforelse
                        </select>
                    </div>
                    <p class="mt-2 text-[11px] text-slate-400 font-medium">This will grant immediate access to the production environment based on role inheritance.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-50">
                    <button type="button" class="h-11 px-6 text-[13px] font-bold text-slate-500 hover:text-slate-900 transition" data-role-modal-close>Cancel</button>
                    <button type="submit" class="h-11 px-8 rounded-xl bg-primary-600 text-white text-[13px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

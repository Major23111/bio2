<div id="addOverrideModal" class="fixed inset-0 z-[9999] hidden" data-role-modal-root aria-hidden="true">
    <div class="absolute inset-0 bg-[#07162f]/55 opacity-0 backdrop-blur-sm transition-opacity duration-300" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div class="pointer-events-auto relative w-full max-w-2xl translate-y-4 scale-95 opacity-0 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_32px_96px_rgba(15,23,42,0.18)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[94vh] flex flex-col" data-modal-dialog>
            <div class="flex items-start justify-between border-b border-slate-100 px-6 pb-5 pt-6">
                <div>
                    <h3 class="text-[19px] font-bold text-slate-900 tracking-tight leading-none mb-1.5 font-display">Assign Role Override</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">GRANULAR CAPABILITY EXCEPTIONS</p>
                </div>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" data-role-modal-close>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.role-permission.overrides.store') }}" class="flex min-h-0 flex-1 flex-col font-sans">
                @csrf
                <div class="role-modal-scroll flex-1 p-5 space-y-5 overflow-y-auto">
                    <!-- Select Target End-User -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">1. SELECT TARGET END-USER</h4>
                            <span class="px-2.5 py-1 rounded-lg bg-primary-50 text-primary-600 text-[11px] font-extrabold uppercase">MODIFIED ACCESS</span>
                        </div>

                        <div class="relative">
                            <select name="override_user_id" data-role-modal-autofocus required class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-10 text-[12px] font-bold text-slate-800 outline-none focus:border-primary-600 appearance-none cursor-pointer transition">
                                <option value="">Select a user...</option>
                                @forelse ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @empty
                                    <option value="" disabled>No users available</option>
                                @endforelse
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Permission Selection -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">2. SELECT PERMISSIONS TO OVERRIDE</h4>
                            <span class="text-[11px] font-extrabold text-primary-600" data-override-selected-count>0 SELECTED</span>
                        </div>

                        <div class="relative mb-3">
                            <input type="text" placeholder="Search permissions..." class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[12px] font-bold text-slate-800 outline-none focus:border-primary-600 transition" data-permission-search>
                            <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <!-- Permissions List with Scrollbar -->
                        <div class="max-h-64 overflow-y-auto border border-slate-100 rounded-xl bg-slate-50/30 p-4 space-y-2" data-override-permission-grid>
                            @forelse ($permissions as $permission)
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-white p-2 rounded-lg transition group">
                                    <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600 transition">
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[13px] font-bold text-slate-700 group-hover:text-slate-900 transition block truncate">{{ $permission->name }}</span>
                                        <span class="text-[11px] text-slate-500 block truncate">{{ $permission->slug ?? $permission->description ?? '' }}</span>
                                    </div>
                                </label>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-[12px] text-slate-500">No permissions available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between p-5 border-t border-slate-50">
                    <button type="button" class="text-[12px] font-bold text-slate-500 hover:text-slate-700 transition" data-role-modal-close>Cancel</button>
                    <div class="flex gap-2">
                        <button type="submit" class="h-10 px-6 rounded-xl bg-primary-600 text-white text-[12px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95">Save Override</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle permission search filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('[data-permission-search]');
        const permissionGrid = document.querySelector('[data-override-permission-grid]');
        const selectedCount = document.querySelector('[data-override-selected-count]');

        if (searchInput && permissionGrid) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const labels = permissionGrid.querySelectorAll('label');
                
                labels.forEach(label => {
                    const text = label.textContent.toLowerCase();
                    label.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Update selected count
        if (permissionGrid && selectedCount) {
            permissionGrid.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox') {
                    const checked = permissionGrid.querySelectorAll('input[type="checkbox"]:checked').length;
                    selectedCount.textContent = `${checked} SELECTED`;
                }
            });
        }
    });
</script>

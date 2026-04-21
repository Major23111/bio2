@php
    $overridePermissions = [
        ['title' => 'Emergency Protocol Bypass', 'description' => 'Allows manual override of automated containment locks.', 'checked' => true],
        ['title' => 'Bulk Data Export', 'description' => 'Export high volume synthesis results to local storage.', 'checked' => true],
        ['title' => 'Role Registry Write', 'description' => 'Modify system level permission groups.', 'checked' => false],
        ['title' => 'API Key Generation', 'description' => 'Create unique identifiers for third party integrations.', 'checked' => true],
        ['title' => 'System Reboot', 'description' => 'Authority to cycle server nodes during failure.', 'checked' => false],
        ['title' => 'Audit Log Deletion', 'description' => 'Permit removal of historical trace data.', 'checked' => true],
    ];
@endphp

<div id="addOverrideModal" class="fixed inset-0 z-[9999] hidden" data-role-modal-root aria-hidden="true">
    <div class="absolute inset-0 bg-[#07162f]/55 opacity-0 backdrop-blur-sm transition-opacity duration-300" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div class="pointer-events-auto relative w-full max-w-[820px] translate-y-4 scale-95 opacity-0 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_32px_96px_rgba(15,23,42,0.18)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[94vh] flex flex-col" data-modal-dialog>
            <div class="flex items-start justify-between border-b border-slate-50 px-8 pb-6 pt-9">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[19px] font-bold text-slate-900 tracking-tight leading-none mb-1.5 font-display">Assign Role Override</h3>
                        <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">GRANULAR CAPABILITY EXCEPTIONS</p>
                    </div>
                </div>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" data-role-modal-close>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form class="flex min-h-0 flex-1 flex-col font-sans">
                <div class="role-modal-scroll flex-1 p-8 space-y-8 overflow-y-auto">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">1. SELECT TARGET END-USER</h4>
                            <span class="px-2.5 py-1 rounded-lg bg-primary-50 text-primary-600 text-[11px] font-extrabold uppercase">MODIFIED ACCESS</span>
                        </div>

                        <div class="relative">
                            <input type="text" data-role-modal-autofocus placeholder="Search by name, employee ID, or biometric tag..." value="Julian Vance" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 text-[14px] font-bold text-slate-800 outline-none focus:border-primary-600 transition">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 rounded-2xl bg-primary-600 text-white flex items-center justify-center text-xl font-bold font-display">JV</div>
                                <div>
                                    <h4 class="text-[16px] font-bold text-slate-900 leading-tight">Julian Vance</h4>
                                    <p class="text-[12px] text-slate-500 font-medium">Research Synthesis Associate • julian.v@biocobalt.io</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-slate-400 uppercase block mb-1">CURRENT CLEARANCE</span>
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-black tracking-tight uppercase">Scientific Lead</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">2. PERMISSION MATRIX OVERRIDES</h4>
                            <span class="text-[11px] font-extrabold text-primary-600" data-override-selected-count>4 ACTIVE OVERWRITES</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4" data-override-permission-grid>
                            @foreach ($overridePermissions as $permission)
                                @include('admin.RolePermissions.components.override-permission-card', $permission)
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl border border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <h4 class="text-[15px] font-bold text-slate-900 mb-1">Commit Policy Immediately</h4>
                            <p class="text-[12px] text-slate-500 font-medium leading-relaxed">Overrides take effect upon the next authentication session if toggled active.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-13 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-8 border-t border-slate-50">
                    <button type="button" class="text-[13px] font-bold text-rose-600 hover:text-rose-700 transition" data-role-modal-close>Discard Changes</button>
                    <div class="flex gap-3">
                        <button type="button" class="h-11 px-6 rounded-xl border border-slate-200 text-[13px] font-bold text-slate-600 hover:bg-slate-50 transition" data-role-modal-close>Cancel</button>
                        <button type="button" class="h-11 px-8 rounded-xl bg-primary-600 text-white text-[13px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95">Save Override Policy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

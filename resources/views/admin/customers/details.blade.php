@extends('admin.layout')

@section('title', 'Customer Details - Biogenix Admin')

@section('admin_content')

    <div class="mb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.customers') }}" class="ajax-link h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-primary-600 hover:border-primary-100 hover:bg-primary-50 transition shadow-sm group" title="Back to Customer Directory">
                <svg class="h-5 w-5 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                    <span>Customer Management</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-600">Customer Details</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Nova Scientific Group</h1>
                <p class="text-sm text-slate-500 mt-1">ID: #CUST-99021 &bull; Member since Oct 12, 2023</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
             <button onclick="window.AdminToast.show('All changes saved successfully', 'success')" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary-600/20 transition cursor-pointer">
                Save Changes
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Primary Information -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[var(--ui-shadow-soft)]">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Orders</p>
                    <p class="text-2xl font-black text-slate-900">248</p>
                    <p class="text-[12px] text-emerald-600 font-bold mt-1">+12 this month</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[var(--ui-shadow-soft)]">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Revenue Generated</p>
                    <p class="text-2xl font-black text-slate-900">₹45,280.00</p>
                    <p class="text-[12px] text-emerald-600 font-bold mt-1">LTV: ₹182.50/ord</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[var(--ui-shadow-soft)]">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Avg. Order Value</p>
                    <p class="text-2xl font-black text-slate-900">₹1,825.00</p>
                    <p class="text-[12px] text-slate-400 font-bold mt-1">Based on last 50</p>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-900">Customer Profile & Contact</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Full Name / Organization</label>
                            <p class="text-sm font-bold text-slate-900">Nova Scientific Group</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Address</label>
                            <p class="text-sm font-bold text-slate-900">contact@nova.com</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone Number</label>
                            <p class="text-sm font-bold text-slate-900">+91 98765 43210</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Primary Address</label>
                            <p class="text-sm font-bold text-slate-900 leading-relaxed">
                                Suite 402, Biotech Towers,<br>
                                Sector 12, Gomti Nagar,<br>
                                Lucknow, UP - 226010
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes Section -->
            <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-900">Internal Admin Notes</h2>
                </div>
                <div class="p-6">
                    <textarea rows="4" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium resize-none" placeholder="Add confidential notes about this customer account for internal review...">Premium B2B account. Interested in high-volume pathology kits. Expedited shipping preferred for Lucknow local deliveries.</textarea>
                    <p class="text-[11px] text-slate-400 mt-2 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        These notes are only visible to administrators.
                    </p>
                </div>
            </div>

        </div>

        <!-- Right Column: Settings & Configuration -->
        <div class="space-y-6">
            
            <!-- Account Status -->
            <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-900">Account Status</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button onclick="updateAccountStatus(this, 'Active')" class="w-full py-3 rounded-xl text-sm font-bold border-2 border-primary-600 bg-primary-50 text-primary-700 transition flex items-center justify-between px-4">
                            Active
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button onclick="updateAccountStatus(this, 'Suspended')" class="w-full py-3 rounded-xl text-sm font-bold border border-slate-100 bg-slate-50 text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-100 transition flex items-center justify-between px-4">
                            Suspended
                            <div class="h-5 w-5"></div>
                        </button>
                        <button onclick="updateAccountStatus(this, 'Inactive')" class="w-full py-3 rounded-xl text-sm font-bold border border-slate-100 bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition flex items-center justify-between px-4">
                            Inactive
                            <div class="h-5 w-5"></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Classification & Credit -->
            <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-900">Classification & Credit</h2>
                </div>
                <div class="p-6 space-y-6">
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Customer Category</label>
                        <select class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 font-bold text-slate-800 transition shadow-sm appearance-none">
                            <option value="B2B" selected>B2B (Wholesale)</option>
                            <option value="B2C">B2C (Retail)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Credit Limit (INR)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none font-bold text-slate-400">
                                ₹
                            </div>
                            <input type="number" value="25000" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-900 font-extrabold tracking-tight">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 italic">Controls the maximum outstanding balance allowed for post-paid orders.</p>
                    </div>

                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-rose-50/50 rounded-2xl border border-rose-100 p-6">
                <h3 class="text-sm font-bold text-rose-800 mb-1">Remove Account</h3>
                <p class="text-[12px] text-rose-600/80 mb-4">Once deleted, you will not be able to recover this customer record.</p>
                <button onclick="confirm('Are you sure you want to permanently delete this customer record?')" class="w-full py-2.5 rounded-xl border border-rose-200 bg-white text-rose-600 text-xs font-bold hover:bg-rose-600 hover:text-white transition cursor-pointer">Delete Customer Record</button>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
        window.updateAccountStatus = function(btn, status) {
            // Reset all buttons in the section
            const container = btn.parentElement;
            container.querySelectorAll('button').forEach(b => {
                b.className = "w-full py-3 rounded-xl text-sm font-bold border border-slate-100 bg-slate-50 text-slate-500 hover:bg-slate-100 transition flex items-center justify-between px-4";
                b.querySelector('div, svg').outerHTML = '<div class="h-5 w-5"></div>';
            });

            // Set active state
            if(status === 'Active') {
                btn.className = "w-full py-3 rounded-xl text-sm font-bold border-2 border-primary-600 bg-primary-50 text-primary-700 transition flex items-center justify-between px-4";
                btn.querySelector('div, h-5').outerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
            } else if(status === 'Suspended') {
                btn.className = "w-full py-3 rounded-xl text-sm font-bold border-2 border-rose-500 bg-rose-50 text-rose-700 transition flex items-center justify-between px-4";
                btn.querySelector('div, h-5').outerHTML = '<svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>';
            } else {
                 btn.className = "w-full py-3 rounded-xl text-sm font-bold border-2 border-slate-400 bg-slate-100 text-slate-700 transition flex items-center justify-between px-4";
                 btn.querySelector('div, h-5').outerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>';
            }
            
            if(window.AdminToast) window.AdminToast.show('Status changed to ' + status, 'info');
        }
    </script>
    @endpush

@endsection

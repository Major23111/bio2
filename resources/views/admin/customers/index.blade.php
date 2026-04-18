@extends('admin.layout')

@section('title', 'Customer Management - Biogenix Admin')

@section('admin_content')



    {{-- Page Header --}}
    <div class="mb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Customer Management</h1>
            <p class="text-sm text-slate-500 mt-1">Manage customer accounts, verifications, and access settings.</p>
        </div>
        {{-- Global Search + Add Customer --}}
        <div class="flex items-center gap-3">
            <div class="relative w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input id="global-customer-search" type="text" placeholder="Global search..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
            </div>
        </div>
    </div>

    {{-- ─── Pending Verifications Banner ─── --}}
    <div id="pending-verifications-section" class="bg-slate-100 border border-slate-200/60 rounded-2xl px-6 py-4 mb-5">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2.5">
                <svg class="h-5 w-5 text-secondary-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span class="text-sm font-bold text-amber-800">Pending User Verifications (3)</span>
            </div>
            <button class="text-[12px] font-bold text-secondary-700 hover:text-amber-900 transition cursor-pointer">View All Pending</button>
        </div>
        <div class="space-y-2.5" id="pending-list">

            {{-- Pending Item 1 --}}
            <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-slate-100 shadow-sm gap-4" data-pending-id="1">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-9 w-9 rounded-full bg-primary-600 text-white flex items-center justify-center text-[11px] font-black shrink-0">ML</div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-bold text-slate-900 truncate">MediLab Solutions</p>
                        <p class="text-[11px] text-slate-500 font-medium truncate">contact@medilab.co &bull; Applied for B2B</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button onclick="handleVerification(this, 'approve', 1)" class="px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-[12px] font-bold rounded-lg transition cursor-pointer">Approve</button>
                    <button onclick="handleVerification(this, 'reject', 1)" class="px-4 py-1.5 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 text-[12px] font-bold rounded-lg transition cursor-pointer">Reject</button>
                </div>
            </div>

            {{-- Pending Item 2 --}}
            <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-slate-100 shadow-sm gap-4" data-pending-id="2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-9 w-9 rounded-full bg-tertiary-600 text-white flex items-center justify-center text-[11px] font-black shrink-0">AP</div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-bold text-slate-900 truncate">Arthur P. Morgon</p>
                        <p class="text-[11px] text-slate-500 font-medium truncate">arthur.m@gmail.com &bull; Applied for Retail</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button onclick="handleVerification(this, 'approve', 2)" class="px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-[12px] font-bold rounded-lg transition cursor-pointer">Approve</button>
                    <button onclick="handleVerification(this, 'reject', 2)" class="px-4 py-1.5 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 text-[12px] font-bold rounded-lg transition cursor-pointer">Reject</button>
                </div>
            </div>

            {{-- Pending Item 3 --}}
            <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-slate-100 shadow-sm gap-4" data-pending-id="3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-9 w-9 rounded-full bg-primary-600 text-white flex items-center justify-center text-[11px] font-black shrink-0">BL</div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-bold text-slate-900 truncate">BioLink Diagnostics</p>
                        <p class="text-[11px] text-slate-500 font-medium truncate">info@biolink.in &bull; Applied for B2B</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button onclick="handleVerification(this, 'approve', 3)" class="px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-[12px] font-bold rounded-lg transition cursor-pointer">Approve</button>
                    <button onclick="handleVerification(this, 'reject', 3)" class="px-4 py-1.5 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 text-[12px] font-bold rounded-lg transition cursor-pointer">Reject</button>
                </div>
            </div>

        </div>
    </div>



    {{-- ─── Customer Directory ─── --}}
    <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">

        {{-- Table Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Customer Directory</h2>
                <p class="text-[13px] text-slate-500">Manage and filter your global customer database</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Category filter --}}
                <div class="relative">
                    <select id="category-filter" class="appearance-none bg-slate-50 border border-slate-200 text-[13px] font-semibold text-slate-700 rounded-lg px-3 py-2 pr-7 cursor-pointer outline-none hover:border-slate-300 transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">All Categories</option>
                        <option value="B2B">B2B</option>
                        <option value="B2C">B2C</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Customer Name</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Email Address</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Date Joined</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="customer-table-body">

                    @php
                    $customers = [
                        ['name' => 'Nova Scientific Group',  'email' => 'contact@nova.com',      'category' => 'B2B',    'status' => 'Active',    'date' => 'Oct 12, 2023', 'initials' => 'NS', 'color' => 'var(--color-primary-600)'],
                        ['name' => 'David Wilson',           'email' => 'david.w@provider.net',   'category' => 'B2C',    'status' => 'Active',    'date' => 'Nov 05, 2023', 'initials' => 'DW', 'color' => 'var(--color-secondary-700)'],
                        ['name' => 'Bio-Chem Logistics',    'email' => 'billing@biochem.log',    'category' => 'B2B',    'status' => 'Suspended', 'date' => 'Aug 22, 2023', 'initials' => 'BC', 'color' => 'var(--color-primary-600)'],
                        ['name' => 'Elena Rodriguez',       'email' => 'elena.rod@webmail.com',  'category' => 'B2C',    'status' => 'Active',    'date' => 'Dec 01, 2023', 'initials' => 'ER', 'color' => 'var(--color-tertiary-600)'],
                        ['name' => 'Omni BioSystems Ltd',  'email' => 'ops@omnibiosys.com',      'category' => 'B2B',    'status' => 'Active',    'date' => 'Jan 15, 2024', 'initials' => 'OB', 'color' => 'var(--color-secondary-700)'],
                        ['name' => 'Clara Mendez',          'email' => 'c.mendez@gmail.com',     'category' => 'B2C',    'status' => 'Inactive',  'date' => 'Feb 20, 2024', 'initials' => 'CM', 'color' => 'var(--color-tertiary-600)'],
                        ['name' => 'LabCore Sciences',     'email' => 'admin@labcore.io',        'category' => 'B2B',    'status' => 'Active',    'date' => 'Mar 03, 2024', 'initials' => 'LC', 'color' => 'var(--color-primary-600)'],
                        ['name' => 'Thomas Reinholt',      'email' => 't.reinholt@bionet.de',    'category' => 'B2C',    'status' => 'Active',    'date' => 'Mar 10, 2024', 'initials' => 'TR', 'color' => 'var(--color-tertiary-600)'],
                    ];
                    @endphp

                    @if (count($customers))
                        @foreach($customers as $c)
                    @php
                        $statusClasses = match($c['status']) {
                            'Active'    => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
                            'Suspended' => 'bg-rose-50 text-rose-700 border border-rose-200/60',
                            'Inactive'  => 'bg-slate-100 text-slate-600 border border-slate-200/60',
                            default     => 'bg-slate-100 text-slate-600 border border-slate-200/60',
                        };
                        $catClasses = match($c['category']) {
                            'B2B'    => 'bg-primary-50 text-primary-700 border border-primary-200/60',
                            'B2C'    => 'bg-primary-50 text-primary-700 border border-primary-200/60',
                            default  => 'bg-slate-100 text-slate-600 border border-slate-200/60',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group customer-row cursor-pointer" data-name="{{ strtolower($c['name']) }}" data-category="{{ $c['category'] }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full text-white flex items-center justify-center text-[10px] font-black shrink-0" style="background-color: {{ $c['color'] }}">{{ $c['initials'] }}</div>
                                <span class="text-[13px] font-bold text-slate-900">{{ $c['name'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[13px] text-slate-600 font-medium">{{ $c['email'] }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 {{ $catClasses }} text-[11px] font-bold rounded-full">{{ $c['category'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 {{ $statusClasses }} text-[11px] font-bold rounded-full">{{ $c['status'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-[13px] text-slate-500 font-medium">{{ $c['date'] }}</td>
                        <td class="px-6 py-4 text-right flex justify-end">
                            <a href="{{ route('admin.customers.details') }}" class="ajax-link inline-flex items-center justify-center shrink-0 h-7 w-7 rounded-full shadow-sm transition-all hover:-translate-y-px hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 text-white cursor-pointer bg-primary-700 hover:bg-primary-800" title="View Customer Details">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </td>
                    </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </div>
                                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">No Customers Found</h3>
                                    <p class="text-xs text-slate-400 mt-1">There are no customer records matching your current filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>

        {{-- View More & Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.customer-directory') }}" class="ajax-link text-[13px] font-bold text-primary-800 hover:underline flex items-center gap-1 cursor-pointer">
                    View More Records
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <p class="text-[12px] text-slate-400 mt-0.5">Showing 1 to 25 of 248 customers</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="flex font-semibold text-[13px]">
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-primary-600 text-white cursor-pointer">1</button>
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-white text-slate-600 hover:bg-slate-50 transition border border-transparent hover:border-slate-200 cursor-pointer">2</button>
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-white text-slate-600 hover:bg-slate-50 transition border border-transparent hover:border-slate-200 cursor-pointer">3</button>
                    <span class="h-9 w-9 flex items-center justify-center text-slate-400">…</span>
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-white text-slate-600 hover:bg-slate-50 transition border border-transparent hover:border-slate-200 cursor-pointer">31</button>
                </div>
                <button class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>



<style>
    @keyframes fade-in { from { opacity:0; transform:scale(0.96) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
    .animate-fade-in { animation: fade-in 0.2s ease-out forwards; }
</style>

@push('scripts')
<script>
(function () {
    // ─── Pending Verification Handlers ───
    window.handleVerification = function(btn, action, id) {
        document.getElementById('verification-modal-action').value = action;
        document.getElementById('verification-modal-id').value = id;
        
        const limitDiv = document.getElementById('verification-credit-limit-container');
        if (action === 'approve') {
            limitDiv.classList.remove('hidden');
            document.getElementById('verification-modal-title').textContent = 'Approve Customer';
            document.getElementById('verification-confirm-btn').textContent = 'Approve & Save';
            document.getElementById('verification-confirm-btn').className = 'flex-1 py-2 rounded-xl text-sm font-bold bg-primary-600 hover:bg-primary-700 text-white transition cursor-pointer shadow-md shadow-primary-600/20';
        } else {
            limitDiv.classList.add('hidden');
            document.getElementById('verification-modal-title').textContent = 'Reject Customer';
            document.getElementById('verification-confirm-btn').textContent = 'Reject Customer';
            document.getElementById('verification-confirm-btn').className = 'flex-1 py-2 rounded-xl text-sm font-bold bg-rose-600 hover:bg-rose-700 text-white transition cursor-pointer shadow-md shadow-rose-600/20';
        }
        
        document.getElementById('verification-modal').classList.remove('hidden');
    };

    window.confirmVerification = function() {
        const action = document.getElementById('verification-modal-action').value;
        const id = document.getElementById('verification-modal-id').value;
        
        const card = document.querySelector(`[data-pending-id="${id}"]`);
        if (!card) {
            document.getElementById('verification-modal').classList.add('hidden');
            return;
        }
        const label = action === 'approve' ? 'Approved' : 'Rejected';
        const colorClass = action === 'approve' ? 'text-primary-600' : 'text-rose-500';
        card.innerHTML = `<div class="flex items-center gap-2 py-1 px-1"><svg class="h-4 w-4 ${colorClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="${action === 'approve' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg><span class="text-[12px] font-bold ${colorClass}">${label}</span></div>`;
        
        setTimeout(() => card.style.height = '0', 1500);
        setTimeout(() => card.remove(), 1800);
        updatePendingCount();
        
        document.getElementById('verification-modal').classList.add('hidden');
        if(window.AdminToast) {
            window.AdminToast.show(action === 'approve' ? 'Customer application approved!' : 'Customer application rejected.', action === 'approve' ? 'success' : 'info');
        }
    };

    function updatePendingCount() {
        const remaining = document.querySelectorAll('[data-pending-id]').length - 1;
        const heading = document.querySelector('#pending-verifications-section .font-bold.text-amber-800');
        if (heading) heading.textContent = `Pending User Verifications (${Math.max(0, remaining)})`;
        if (remaining <= 0) {
            setTimeout(() => {
                const section = document.getElementById('pending-verifications-section');
                if (section) { section.style.opacity = '0'; section.style.transition = 'opacity 0.4s'; setTimeout(() => section.remove(), 400); }
            }, 2000);
        }
    }

    // ─── Category Filter ───
    document.getElementById('category-filter')?.addEventListener('change', function() {
        const val = this.value;
        document.querySelectorAll('.customer-row').forEach(row => {
            const match = !val || row.dataset.category === val;
            row.style.display = match ? '' : 'none';
        });
    });

    // ─── Global search filter ───
    document.getElementById('global-customer-search')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.customer-row').forEach(row => {
            row.style.display = row.dataset.name.includes(q) ? '' : 'none';
        });
    });


    
    // ─── Escape key to close modals ───
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { /* Modal closing removed */ }
    });
})();
</script>
@endpush

@endsection

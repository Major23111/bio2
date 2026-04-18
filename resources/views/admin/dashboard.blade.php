@extends('admin.layout')

@section('title', 'Admin Dashboard - Biogenix')

@section('admin_content')
@php
    $dashboardOrderLinks = [];
    foreach (($recentOrders ?? collect())->take(3) as $order) {
        $dashboardOrderLinks[] = route('admin.orders.view', ['orderId' => $order['id']]);
    }
    while (count($dashboardOrderLinks) < 3) {
        $dashboardOrderLinks[] = route('admin.orders');
    }
@endphp
            


            <!-- Welcome Header -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Dashboard Overview</h2>
                    <p class="text-sm text-[var(--ui-text-muted)] mt-1">Welcome back. Here's what's happening in your biogenic supply chain today.</p>
                </div>
                
                <!-- Quick Search -->
                <div class="relative w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search analytics or orders..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-9 pr-4 py-2.5 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-[var(--ui-text-muted)]">
                </div>
            </div>

            <!-- KPI Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Card 1 -->
                <a href="{{ route('admin.orders') }}" class="ajax-link bg-[var(--ui-surface)] rounded-2xl p-5 lg:p-6 shadow-[var(--ui-shadow-soft)] border border-[var(--ui-card-border)] flex flex-col justify-between h-[130px] lg:h-[140px] hover:shadow-[var(--ui-shadow-card)] transition-shadow cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-[var(--ui-surface-subtle)] text-primary-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 00-2-2m4-10a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2V9z"></path></svg>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-md mb-auto mt-0.5">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +12%
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-[12px] lg:text-[13px] font-semibold text-[var(--ui-text-muted)] mb-0.5">Total Orders</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-[var(--ui-text)] tracking-tight">1,284</h3>
                    </div>
                </a>

                <!-- Card 2 -->
                <a href="{{ route('admin.orders') }}" class="ajax-link bg-[var(--ui-surface)] rounded-2xl p-5 lg:p-6 shadow-[var(--ui-shadow-soft)] border border-[var(--ui-card-border)] flex flex-col justify-between h-[130px] lg:h-[140px] hover:shadow-[var(--ui-shadow-card)] transition-shadow cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-md mb-auto mt-0.5">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +5%
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-[12px] lg:text-[13px] font-semibold text-slate-500 mb-0.5">Today's Orders</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">142</h3>
                    </div>
                </a>

                <!-- Card 3 -->
                <a href="{{ route('admin.orders') }}" class="ajax-link bg-[var(--ui-surface)] rounded-2xl p-5 lg:p-6 shadow-[var(--ui-shadow-soft)] border border-[var(--ui-card-border)] flex flex-col justify-between h-[130px] lg:h-[140px] hover:shadow-[var(--ui-shadow-card)] transition-shadow cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-secondary-50 text-secondary-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2v1l2 2m-3 5h3m-3 4h3"></path></svg>
                        </div>
                        <div class="text-[11px] font-semibold text-slate-400 mb-auto mt-2">Current</div>
                    </div>
                    <div class="mt-2">
                        <p class="text-[12px] lg:text-[13px] font-semibold text-slate-500 mb-0.5">Pending Dispatch</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">48</h3>
                    </div>
                </a>

            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Revenue Snapshot (Full Width) -->
                <div class="bg-[var(--ui-surface)] rounded-2xl p-6 lg:p-7 shadow-[var(--ui-shadow-soft)] border border-[var(--ui-card-border)] h-auto sm:h-[400px] flex flex-col justify-between relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-[var(--ui-text)]">Revenue Snapshot</h3>
                            <p class="text-[11px] lg:text-xs font-semibold text-[var(--ui-text-muted)] mt-1">Global performance tracking</p>
                            <div class="mt-4 flex flex-wrap items-center gap-3 lg:gap-4">
                                <h2 class="text-3xl lg:text-4xl font-extrabold text-primary-800 tracking-tight">$248,590.00</h2>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-md h-fit">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    18.2%
                                </span>
                            </div>
                        </div>
                        <!-- Toggle -->
                        <div id="chartToggle" class="bg-[var(--ui-surface-subtle)] rounded-lg p-1 flex items-center shadow-inner self-start">
                            <button data-period="weekly" class="chart-toggle-btn px-3 lg:px-4 py-1.5 text-[11px] font-bold text-[var(--ui-text-muted)] rounded-md transition hover:text-[var(--ui-text)] cursor-pointer">Weekly</button>
                            <button data-period="monthly" class="chart-toggle-btn px-3 lg:px-4 py-1.5 text-[11px] font-bold text-primary-800 bg-[var(--ui-surface)] rounded-md shadow-sm cursor-pointer">Monthly</button>
                            <button data-period="yearly" class="chart-toggle-btn px-3 lg:px-4 py-1.5 text-[11px] font-bold text-[var(--ui-text-muted)] rounded-md transition hover:text-[var(--ui-text)] cursor-pointer">Yearly</button>
                        </div>
                    </div>

                    <!-- Fake Chart Area -->
                    <div id="chartBars" class="mt-8 flex-1 grid grid-cols-7 gap-2 lg:gap-4 items-end px-1 lg:px-2 pt-10 min-h-[150px]">
                        <div class="w-full bg-[var(--ui-surface-subtle)] rounded-t-sm h-[30%]"></div>
                        <div class="w-full bg-[var(--ui-surface-subtle)] rounded-t-sm h-[45%]"></div>
                        <div class="w-full bg-[var(--ui-surface-subtle)] rounded-t-sm h-[35%]"></div>
                        <div class="w-full bg-[var(--ui-surface-subtle)] rounded-t-sm h-[55%]"></div>
                        <div class="w-full bg-[var(--ui-surface-subtle)] rounded-t-sm h-[40%]"></div>
                        <div class="w-full bg-[var(--ui-surface-subtle)] rounded-t-sm h-[60%]"></div>
                        <div class="w-full bg-primary-600 rounded-t-sm h-[85%] shadow-[0_0_15px_rgba(9,27,63,0.3)]"></div>
                    </div>
                    
                    <!-- Chart Labels -->
                    <div class="grid grid-cols-7 gap-2 lg:gap-4 mt-4 px-1 lg:px-2 text-center text-[9px] lg:text-[10px] font-bold text-[var(--ui-text-muted)]">
                        <span>MON</span>
                        <span>TUE</span>
                        <span>WED</span>
                        <span>THU</span>
                        <span>FRI</span>
                        <span>SAT</span>
                        <span>SUN</span>
                    </div>
                </div>
            </div>

            <!-- Priority Orders Table -->
            <div class="bg-[var(--ui-surface)] rounded-2xl shadow-[var(--ui-shadow-soft)] border border-[var(--ui-card-border)] overflow-hidden mt-6">
                <div class="px-5 lg:px-7 py-5 lg:py-6 border-b border-[var(--ui-border)] flex items-center justify-between">
                    <h3 class="text-base font-bold text-[var(--ui-text)]">Recent Orders</h3>
                    <a href="{{ route('admin.orders') }}" class="ajax-link text-[12px] lg:text-[13px] font-bold text-primary-800 hover:underline underline-offset-2 cursor-pointer">View All Orders</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Order ID</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Client</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Biogenic Type</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Value</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--ui-border)] text-[12px] lg:text-[13px] font-semibold text-[var(--ui-text)]">
                            <tr class="hover:bg-[var(--ui-surface-subtle)]/50 transition-colors cursor-pointer" onclick="window.location.href='{{ $dashboardOrderLinks[0] }}'">
                                <td class="px-5 lg:px-7 py-4 lg:py-5">#BGX-9012</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-6 w-6 lg:h-7 lg:w-7 rounded bg-primary-50 text-primary-600 font-bold flex items-center justify-center text-[10px] lg:text-[11px]">M</div>
                                        MetroLabs Inc.
                                    </div>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-600 font-medium">Reagent Kit Alpha</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">$4,290</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200/60 text-[8px] lg:text-[9px] font-extrabold uppercase tracking-wider rounded">Dispatching</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-center">
                                    <button onclick="event.stopPropagation();AdminConfirm.show({title:'Cancel Order?',message:'This will cancel order #BGX-9012.',confirmText:'Cancel Order'}).then(r=>{if(r)AdminToast.show('Order cancelled','success')})" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition cursor-pointer" title="Cancel Order"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </td>
                            </tr>
                            
                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="window.location.href='{{ $dashboardOrderLinks[1] }}'">
                                <td class="px-5 lg:px-7 py-4 lg:py-5">#BGX-8994</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-6 w-6 lg:h-7 lg:w-7 rounded bg-primary-50 text-primary-600 font-bold flex items-center justify-center text-[10px] lg:text-[11px]">G</div>
                                        GenTech Solutions
                                    </div>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-600 font-medium">Synthetic Enzyme B2</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">$12,800</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2 py-1 bg-primary-50 text-primary-700 border border-primary-200/60 text-[8px] lg:text-[9px] font-extrabold uppercase tracking-wider rounded">In Transit</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-center">
                                    <button onclick="event.stopPropagation();AdminConfirm.show({title:'Cancel Order?',message:'This will cancel order #BGX-8994.',confirmText:'Cancel Order'}).then(r=>{if(r)AdminToast.show('Order cancelled','success')})" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition cursor-pointer" title="Cancel Order"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="window.location.href='{{ $dashboardOrderLinks[2] }}'">
                                <td class="px-5 lg:px-7 py-4 lg:py-5">#BGX-8851</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-6 w-6 lg:h-7 lg:w-7 rounded bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-[10px] lg:text-[11px]">U</div>
                                        University Hospital
                                    </div>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-600 font-medium">Rapid Test Sets</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">$840</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[8px] lg:text-[9px] font-extrabold uppercase tracking-wider rounded">Delivered</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-center">
                                    <button onclick="event.stopPropagation();AdminConfirm.show({title:'Cancel Order?',message:'This will cancel order #BGX-8851.',confirmText:'Cancel Order'}).then(r=>{if(r)AdminToast.show('Order cancelled','success')})" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition cursor-pointer" title="Cancel Order"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

@endsection

@push('scripts')
<script>
// ─── Revenue Toggle ───
const chartData = {
    weekly: [30, 45, 35, 55, 40, 60, 85],
    monthly: [50, 65, 40, 75, 55, 80, 90],
    yearly: [70, 55, 80, 60, 75, 85, 95]
};

document.querySelectorAll('.chart-toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.chart-toggle-btn').forEach(b => {
            b.classList.remove('text-primary-800', 'bg-[var(--ui-surface)]', 'shadow-sm');
            b.classList.add('text-[var(--ui-text-muted)]');
        });
        btn.classList.remove('text-[var(--ui-text-muted)]');
        btn.classList.add('text-primary-800', 'bg-[var(--ui-surface)]', 'shadow-sm');

        const bars = document.querySelectorAll('#chartBars > div');
        const data = chartData[btn.dataset.period];
        bars.forEach((bar, i) => {
            bar.style.transition = 'height 0.4s ease';
            bar.style.height = data[i] + '%';
        });
    });
});
</script>
@endpush

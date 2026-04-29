@extends('admin.layout')

@section('title', 'Order Management - Biogenix Admin')

@section('admin_content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Order Management</h1>
            <p class="text-sm text-slate-500 mt-1">Review and track medical supply chain operations across all regions.</p>
        </div>
        <button id="exportCsvBtn" onclick="exportOrdersCSV()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition shadow-sm shrink-0 cursor-pointer">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Export CSV
        </button>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden flex flex-col relative">

        <!-- Filter Bar -->
        <div class="px-5 lg:px-6 py-4 border-b border-slate-100 flex flex-row items-center justify-between gap-4">

            <!-- Search -->
            <div class="relative w-72 lg:w-96 shrink-0">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="orderSearch" placeholder="Search Order ID, Client, or SKU..." value="{{ request()->query('search') }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium" onkeypress="if(event.key==='Enter') navigateWithSearch()">
            </div>

            <!-- Status Pills -->
            <div id="statusPills" class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-0.5">
                @php
                    $currentStatus = request()->query('status', 'all');
                    $statuses = ['all' => 'All Orders', 'Pending' => 'Pending', 'Processing' => 'Processing', 'Dispatched' => 'Dispatched', 'Delivered' => 'Delivered', 'Cancelled' => 'Cancelled'];
                @endphp
                @foreach($statuses as $statusValue => $statusLabel)
                    <a href="{{ route('admin.orders', array_merge(request()->query(), ['status' => $statusValue])) }}" class="status-pill ajax-link inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold {{ $currentStatus === $statusValue ? 'bg-primary-600 text-white' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100' }} transition cursor-pointer">
                        {{ $statusLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Table -->
        <div class="admin-table-wrapper">
            <table id="ordersTable" class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest cursor-pointer hover:text-slate-600 transition">
                            <a href="{{ route('admin.orders', array_merge(request()->query(), ['sort' => 'id'])) }}" class="ajax-link">Order ID <span class="sort-icon">&#8597;</span></a>
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest cursor-pointer hover:text-slate-600 transition">
                            <a href="{{ route('admin.orders', array_merge(request()->query(), ['sort' => 'customer'])) }}" class="ajax-link">Customer Name <span class="sort-icon">&#8597;</span></a>
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest cursor-pointer hover:text-slate-600 transition">
                            <a href="{{ route('admin.orders', array_merge(request()->query(), ['sort' => 'date'])) }}" class="ajax-link">Date <span class="sort-icon">&#8597;</span></a>
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest cursor-pointer hover:text-slate-600 transition">
                            <a href="{{ route('admin.orders', array_merge(request()->query(), ['sort' => 'amount'])) }}" class="ajax-link">Total Amount <span class="sort-icon">&#8597;</span></a>
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Payment Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Fulfillment</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        @php
                            $paymentBadgeClass = 'bg-slate-50 text-slate-600 border border-slate-200/60';

                            if ($order['paymentStatusLabel'] === 'Paid') {
                                $paymentBadgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200/60';
                            }

                            if ($order['paymentStatusLabel'] === 'Pending') {
                                $paymentBadgeClass = 'bg-amber-50 text-amber-700 border border-amber-200/60';
                            }

                            if ($order['paymentStatusLabel'] === 'Refunded') {
                                $paymentBadgeClass = 'bg-primary-50 text-primary-700 border border-primary-200/60';
                            }

                            $fulfillmentBadgeClass = 'bg-slate-50 text-slate-600 border border-slate-200/60';

                            if ($order['fulfillmentStatusLabel'] === 'Processing') {
                                $fulfillmentBadgeClass = 'bg-primary-50 text-primary-700 border border-primary-200/60';
                            }

                            if ($order['fulfillmentStatusLabel'] === 'Dispatched') {
                                $fulfillmentBadgeClass = 'bg-primary-50 text-primary-700 border border-primary-200/60';
                            }

                            if ($order['fulfillmentStatusLabel'] === 'Delivered') {
                                $fulfillmentBadgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200/60';
                            }

                            if ($order['fulfillmentStatusLabel'] === 'Cancelled') {
                                $fulfillmentBadgeClass = 'bg-rose-50 text-rose-700 border border-rose-200/60';
                            }
                        @endphp

                        <tr class="hover:bg-slate-50/50 transition-colors group cursor-pointer relative" onclick="this.querySelector('.row-ajax-link').click()">
                            <td class="px-5 lg:px-6 py-4">
                                <a href="{{ route('admin.orders.view', ['orderId' => $order['id']]) }}" class="ajax-link row-ajax-link hidden"></a>
                                <span class="text-[13px] font-bold text-slate-900">#{{ $order['orderNumber'] }}</span>
                            </td>
                            <td class="px-5 lg:px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-900">{{ $order['customerName'] }}</span>
                                    <span class="text-[12px] font-medium text-slate-400">{{ $order['customerSummary'] }}</span>
                                </div>
                            </td>
                            <td class="px-5 lg:px-6 py-4">
                                <span class="text-[13px] font-semibold text-slate-600">{{ $order['createdDateText'] }}</span>
                            </td>
                            <td class="px-5 lg:px-6 py-4">
                                <span class="text-[13px] font-bold text-slate-900">{{ $order['totalAmountText'] }}</span>
                            </td>
                            <td class="px-5 lg:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 {{ $paymentBadgeClass }} text-[11px] font-bold rounded-md">{{ $order['paymentStatusLabel'] }}</span>
                            </td>
                            <td class="px-5 lg:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 {{ $fulfillmentBadgeClass }} text-[11px] font-bold rounded-full">{{ $order['fulfillmentStatusLabel'] }}</span>
                            </td>
                            <td class="px-5 lg:px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-ui.action-icon type="edit" onclick="event.stopPropagation(); this.closest('tr').querySelector('.row-ajax-link').click()">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </x-ui.action-icon>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-order-row">
                            <td colspan="7" class="px-5 lg:px-6 py-10 text-center text-sm font-medium text-slate-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 lg:px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p id="orderCount" class="text-[13px] text-slate-500 font-medium">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} results
            </p>

            @php
                $currentPage = $orders->currentPage();
                $lastPage = $orders->lastPage();
                $startPage = $currentPage - 1;
                $endPage = $currentPage + 1;

                if ($startPage < 1) {
                    $startPage = 1;
                }

                if ($endPage > $lastPage) {
                    $endPage = $lastPage;
                }
            @endphp

            <div class="flex items-center gap-2">
                @if ($orders->previousPageUrl())
                    <a href="{{ $orders->previousPageUrl() }}" class="ajax-link h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                @else
                    <span class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-300 bg-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </span>
                @endif

                <div class="flex font-semibold text-[13px]">
                    @for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++)
                        @if ($pageNumber === $currentPage)
                            <span class="h-9 w-9 flex items-center justify-center rounded bg-primary-600 text-white">{{ $pageNumber }}</span>
                        @else
                            <a href="{{ $orders->url($pageNumber) }}" class="ajax-link h-9 w-9 flex items-center justify-center rounded bg-white text-slate-600 hover:bg-slate-50 transition border border-transparent hover:border-slate-200 cursor-pointer">{{ $pageNumber }}</a>
                        @endif
                    @endfor
                </div>

                @if ($orders->nextPageUrl())
                    <a href="{{ $orders->nextPageUrl() }}" class="ajax-link h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @else
                    <span class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-300 bg-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </span>
                @endif
            </div>
        </div>

    </div>

</div>

<script>
// Navigate with search parameters
function navigateWithSearch() {
    const searchValue = document.getElementById('orderSearch').value;
    const currentStatus = '{{ request()->query("status", "all") }}';
    const url = '{{ route("admin.orders") }}?status=' + currentStatus + '&search=' + encodeURIComponent(searchValue);
    if (window.loadPage) {
        window.loadPage(url);
    } else {
        window.location.href = url;
    }
}

// Export CSV with current filters
function exportOrdersCSV() {
    const currentQuery = window.location.search;
    window.location.href = '{{ route("admin.orders.export") }}' + currentQuery;
}
</script>

@endsection

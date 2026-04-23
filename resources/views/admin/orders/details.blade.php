@extends('admin.layout')

@section('title', 'Order #' . ($order['orderNumber'] ?? 'Details') . ' - Biogenix Admin')

@section('admin_content')
<form id="orderDetailsForm" method="POST" action="{{ route('admin.orders.update', ['orderId' => $order['id']]) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-2">
            <div>
                <nav class="flex text-[13px] text-slate-500 font-medium mb-3">
                    <a href="{{ route('admin.orders') }}" class="ajax-link hover:text-slate-900 transition flex items-center gap-1.5 cursor-pointer">
                        Orders
                    </a>
                    <span class="mx-2 text-slate-300">/</span>
                    <span class="text-slate-900 font-semibold">#{{ $order['orderNumber'] }}</span>
                </nav>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.orders') }}" class="ajax-link h-10 w-10 flex items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition shadow-sm cursor-pointer">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Order #{{ $order['orderNumber'] }}</h1>
                            <span id="headerStatusBadge" class="inline-flex items-center px-2.5 py-1 bg-primary-50 text-primary-600 text-[11px] font-bold rounded-full uppercase tracking-wider">{{ $order['selectedStageLabel'] }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="text-[13px] text-slate-500 font-medium mt-1">
                                Placed on {{ $order['placedDateText'] }}
                                @if ($order['placedTimeText'] !== '')
                                    &bull; {{ $order['placedTimeText'] }}
                                @endif
                            </p>
                            <span class="text-slate-300">|</span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Payment Method:</span>
                                <span class="text-[13px] font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded-md border border-primary-200/50">{{ $order['paymentMethodLabel'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 self-start sm:self-end mt-2 sm:mt-0">
                <!-- <button type="button" onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition shadow-sm cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Print Invoice
                </button> -->
                <button id="saveChangesBtn" type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-primary-600 text-white hover:bg-primary-700 transition shadow-sm cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    Save Changes
                </button>
            </div>
        </div>

        <!-- Order Status Stepper -->
        <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 p-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-y-8 gap-x-2 relative">
                <div id="step-node-1" class="flex-1 flex flex-col items-center text-center group">
                    <div class="step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300">1</div>
                    <div class="mt-3">
                        <h4 class="step-label text-[13px] font-bold text-slate-400 transition-colors duration-300">Order Received</h4>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 whitespace-nowrap">All orders start here</p>
                    </div>
                </div>

                <div class="hidden md:block text-slate-200 text-xl font-light">&#9654;</div>

                <div id="step-node-2" class="flex-1 flex flex-col items-center text-center group">
                    <div class="step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300">2</div>
                    <div class="mt-3">
                        <h4 class="step-label text-[13px] font-bold text-slate-400 transition-colors duration-300">Payment Received</h4>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 whitespace-nowrap">Prepaid orders only</p>
                    </div>
                </div>

                <div class="hidden md:block text-slate-200 text-xl font-light">&#9654;</div>

                <div id="step-node-3" class="flex-1 flex flex-col items-center text-center group">
                    <div class="step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300">3</div>
                    <div class="mt-3">
                        <h4 class="step-label text-[13px] font-bold text-slate-400 transition-colors duration-300">Processing</h4>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 whitespace-nowrap">Both order types</p>
                    </div>
                </div>

                <div class="hidden md:block text-slate-200 text-xl font-light">&#9654;</div>

                <div id="step-node-4" class="flex-1 flex flex-col items-center text-center group">
                    <div class="step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300">4</div>
                    <div class="mt-3">
                        <h4 class="step-label text-[13px] font-bold text-slate-400 transition-colors duration-300">Dispatched</h4>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 whitespace-nowrap">Both order types</p>
                    </div>
                </div>

                <div class="hidden md:block text-slate-200 text-xl font-light">&#9654;</div>

                <div id="step-node-5" class="flex-1 flex flex-col items-center text-center group">
                    <div class="step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300">5</div>
                    <div class="mt-3">
                        <h4 class="step-label text-[13px] font-bold text-slate-400 transition-colors duration-300">Delivered</h4>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 whitespace-nowrap">Both order types</p>
                    </div>
                </div>

                <div class="hidden md:block text-slate-200 text-xl font-light">&#9654;</div>

                <div id="step-node-6" class="flex-1 flex flex-col items-center text-center group">
                    <div class="step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300">6</div>
                    <div class="mt-3">
                        <h4 class="step-label text-[13px] font-bold text-slate-400 transition-colors duration-300">Payment Received</h4>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 whitespace-nowrap">COD orders only</p>
                    </div>
                </div>

                <div class="hidden md:block text-slate-200 text-xl font-light">&#9654;</div>

                <div id="step-node-7" class="flex-1 flex flex-col items-center text-center group">
                    <div class="step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div class="mt-3">
                        <h4 class="step-label text-[13px] font-bold text-slate-400 transition-colors duration-300">Cancelled</h4>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5 whitespace-nowrap">Order Cancelled</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6 mt-6">

            <!-- Left Column -->
            <div class="space-y-6">

                <!-- Order Summary -->
                <div class="bg-white rounded-[20px] shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden flex flex-col">
                    <div class="px-5 lg:px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2.5">
                            <svg class="h-5 w-5 text-primary-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <h2 class="text-[15px] font-extrabold text-slate-900">Order Summary</h2>
                        </div>
                        <span class="text-[12px] font-bold text-slate-500 bg-slate-50 px-2.5 py-1 rounded-full border border-slate-100">
                            {{ $order['itemCount'] }} {{ $order['itemCount'] === 1 ? 'Item' : 'Items' }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-5 lg:px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Item Name</th>
                                    <th class="px-5 lg:px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">SKU</th>
                                    <th class="px-5 lg:px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Quantity</th>
                                    <th class="px-5 lg:px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Price</th>
                                    <th class="px-5 lg:px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 border-b border-slate-100 bg-white">
                                @forelse ($order['items'] as $savedItem)
                                    <tr class="hover:bg-slate-50/30 transition-colors cursor-pointer">
                                        <td class="px-5 lg:px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0">
                                                    <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                                </div>
                                                <span class="text-[14px] font-bold text-slate-900">{{ $savedItem['itemName'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 lg:px-6 py-4 text-[13px] font-semibold text-slate-500">{{ $savedItem['sku'] }}</td>
                                        <td class="px-5 lg:px-6 py-4 text-[13px] font-bold text-slate-900 text-center">{{ $savedItem['quantity'] }}</td>
                                        <td class="px-5 lg:px-6 py-4 text-[13px] font-semibold text-slate-600 text-right">{{ $savedItem['unitPriceText'] }}</td>
                                        <td class="px-5 lg:px-6 py-4 text-[14px] font-extrabold text-slate-900 text-right">{{ $savedItem['lineTotalText'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 lg:px-6 py-10 text-center text-sm font-medium text-slate-500">Order items are not available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="px-5 lg:px-6 py-5 bg-white flex flex-col items-end gap-3 z-10 relative">
                        <div class="w-full max-w-[280px]">
                            <div class="flex items-center justify-between py-1.5 w-full">
                                <span class="text-[13px] font-bold text-slate-500">Subtotal</span>
                                <span class="text-[14px] font-extrabold text-slate-900">{{ $order['subtotalAmountText'] }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 w-full">
                                <span class="text-[13px] font-bold text-slate-500">Shipping</span>
                                <span class="text-[14px] font-extrabold text-slate-900">{{ $order['shippingAmountText'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 lg:px-6 py-5 bg-slate-50 border-t border-slate-100 flex items-center justify-end z-10 relative">
                        <div class="flex items-center justify-between w-full max-w-[280px]">
                            <span class="text-[16px] font-extrabold text-slate-900">Grand Total</span>
                            <span class="text-[18px] font-extrabold text-primary-800">{{ $order['totalAmountText'] }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-6">

                <!-- Manage Status -->
                <div class="bg-white rounded-[20px] shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-visible">
                    <div class="px-5 lg:px-6 py-4 border-b border-slate-100">
                        <h2 class="text-[15px] font-extrabold text-slate-900">Manage Status</h2>
                    </div>
                    <div class="p-5 lg:p-6 space-y-5">

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Order Status</label>
                            <div class="relative">
                                <select id="orderStatus" name="order_stage" data-payment-flow="{{ $order['paymentFlowType'] }}" class="w-full appearance-none bg-white border border-slate-200 text-sm rounded-xl px-4 py-3 outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600 font-semibold text-slate-800 transition shadow-sm hover:border-slate-300">
                                    <option value="Order Received" {{ $order['selectedStageLabel'] === 'Order Received' ? 'selected' : '' }}>Order Received</option>
                                    <option value="Payment Received (Prepaid)" {{ $order['selectedStageLabel'] === 'Payment Received (Prepaid)' ? 'selected' : '' }}>Payment Received (Prepaid)</option>
                                    <option value="Processing" {{ $order['selectedStageLabel'] === 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Dispatched" {{ $order['selectedStageLabel'] === 'Dispatched' ? 'selected' : '' }}>Dispatched</option>
                                    <option value="Delivered" {{ $order['selectedStageLabel'] === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="Payment Received (COD)" {{ $order['selectedStageLabel'] === 'Payment Received (COD)' ? 'selected' : '' }}>Payment Received (COD)</option>
                                    <option value="Cancelled" {{ $order['selectedStageLabel'] === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Tracking Code</label>
                            <div class="relative flex gap-2">
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                    </div>
                                    <input id="trackingInput" name="tracking_number" type="text" value="{{ $order['trackingNumber'] }}" placeholder="Enter tracking number (e.g. 1Z9...)" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-10 pr-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                                </div>
                                <button type="button" onclick="const trackingValue=document.getElementById('trackingInput').value;if(trackingValue){navigator.clipboard.writeText(trackingValue);AdminToast.show('Tracking code copied!','success')}else{AdminToast.show('Enter a tracking code first','info')}" class="h-[46px] w-[46px] flex-shrink-0 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:bg-slate-100 hover:text-primary-800 transition flex items-center justify-center cursor-pointer" title="Copy tracking code">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Tracking URL</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                </div>
                                <input id="trackingUrlInput" name="tracking_url" type="url" value="{{ $order['trackingUrl'] }}" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-10 pr-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-[20px] shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden relative">
                    <div class="px-5 lg:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-[15px] font-extrabold text-slate-900">Customer Info</h2>
                        <a href="#" onclick="event.preventDefault();document.getElementById('customerEditModal').classList.remove('hidden')" class="text-[13px] font-bold text-primary-800 hover:underline cursor-pointer">Edit</a>
                    </div>
                    <div class="p-5 lg:p-6 space-y-5">

                        <div class="flex items-center gap-3.5">
                            <div class="h-11 w-11 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-[14px] font-bold text-slate-900">{{ $order['customerName'] }}</h3>
                                <p class="text-[12.5px] font-medium text-slate-500 mt-0.5">{{ $order['companyName'] }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-2">
                            <div class="flex gap-3 items-start">
                                <svg class="h-4.5 w-4.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Email</p>
                                    <a href="{{ $order['customerEmailValue'] !== '' ? 'mailto:' . $order['customerEmailValue'] : '#' }}" class="text-[13px] font-semibold text-slate-800 hover:text-primary-800 transition">{{ $order['customerEmail'] }}</a>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <svg class="h-4.5 w-4.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Phone</p>
                                    <a href="{{ $order['customerPhoneValue'] !== '' ? 'tel:' . $order['customerPhoneValue'] : '#' }}" class="text-[13px] font-semibold text-slate-800 hover:text-primary-800 transition">{{ $order['customerPhone'] }}</a>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <svg class="h-4.5 w-4.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Shipping Address</p>
                                    <p class="text-[13px] font-semibold text-slate-800 leading-[1.6]">
                                        @foreach ($order['shippingAddressLines'] as $shippingLine)
                                            {{ $shippingLine }}@if (! $loop->last)<br>@endif
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Customer Edit Modal -->
    <div id="customerEditModal" class="hidden fixed inset-0 z-[1000] flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm cursor-pointer" onclick="this.parentElement.classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900">Edit Customer Info</h3>
                <button type="button" onclick="this.closest('#customerEditModal').classList.add('hidden')" class="h-8 w-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-700 transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Full Name</label>
                    <input name="customer_name" type="text" value="{{ $order['customerName'] }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Email</label>
                    <input name="customer_email" type="email" value="{{ $order['customerEmailValue'] }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Phone</label>
                    <input name="customer_phone" type="tel" value="{{ $order['customerPhoneValue'] }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Shipping Address</label>
                    <textarea name="shipping_address_text" rows="3" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium resize-none">{{ $order['shippingAddressText'] }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="button" onclick="this.closest('#customerEditModal').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl text-[13px] font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">Cancel</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-[13px] font-bold text-white bg-primary-600 hover:bg-primary-700 transition cursor-pointer">Save Changes</button>
            </div>
        </div>
    </div>
</form>

<script>
(() => {
    // Show page messages after redirect.
    @if (session('success'))
        if (window.AdminToast) {
            window.AdminToast.show(@json(session('success')), 'success');
        }
    @endif

    @if (session('error'))
        if (window.AdminToast) {
            window.AdminToast.show(@json(session('error')), 'error');
        }
    @endif

    // Start button loading on form submit.
    const orderDetailsForm = document.getElementById('orderDetailsForm');
    const saveChangesBtn = document.getElementById('saveChangesBtn');

    if (orderDetailsForm && saveChangesBtn) {
        orderDetailsForm.addEventListener('submit', () => {
            AdminBtnLoading.start(saveChangesBtn);
        });
    }

    // Refresh the visual stepper from the selected stage.
    window.updateStepper = () => {
        const orderStatusSelect = document.getElementById('orderStatus');

        if (!orderStatusSelect) {
            return;
        }

        const selectedStatus = orderStatusSelect.value;
        const savedPaymentFlow = orderStatusSelect.dataset.paymentFlow || 'prepaid';
        const isCodOrder = savedPaymentFlow === 'cod' || selectedStatus === 'Payment Received (COD)';
        const isPrepaidOrder = !isCodOrder;
        const stepList = [
            { id: 1, label: 'Order Received' },
            { id: 2, label: 'Payment Received (Prepaid)', showStep: isPrepaidOrder },
            { id: 3, label: 'Processing' },
            { id: 4, label: 'Dispatched' },
            { id: 5, label: 'Delivered' },
            { id: 6, label: 'Payment Received (COD)', showStep: isCodOrder },
            { id: 7, label: 'Cancelled' },
        ];

        let currentStepIndex = stepList.findIndex((savedStep) => savedStep.label === selectedStatus);

        if (currentStepIndex < 0) {
            currentStepIndex = 0;
        }

        stepList.forEach((savedStep, savedIndex) => {
            const stepNode = document.getElementById(`step-node-${savedStep.id}`);

            if (!stepNode) {
                return;
            }

            const stepIcon = stepNode.querySelector('.step-icon');
            const stepLabel = stepNode.querySelector('.step-label');
            const showStep = Object.hasOwn(savedStep, 'showStep') ? savedStep.showStep : true;

            stepNode.style.opacity = showStep ? '1' : '0.3';
            stepNode.style.filter = showStep ? 'none' : 'grayscale(1)';

            if (selectedStatus === 'Cancelled') {
                if (savedStep.id === 7) {
                    stepIcon.className = 'step-icon h-10 w-10 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-sm shadow-xl shadow-rose-600/30 z-10 border-4 border-white ring-1 ring-rose-100 transition-all duration-300 scale-110';
                    stepIcon.innerHTML = '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';
                    stepLabel.className = 'step-label text-[13px] font-extrabold text-rose-600 transition-colors duration-300';
                }

                if (savedStep.id !== 7) {
                    stepIcon.className = 'step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300';
                    stepIcon.innerHTML = savedStep.id;
                    stepLabel.className = 'step-label text-[13px] font-bold text-slate-400 transition-colors duration-300';
                }

                return;
            }

            if (savedStep.id === 7) {
                stepIcon.className = 'step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300';
                stepIcon.innerHTML = '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';
                stepLabel.className = 'step-label text-[13px] font-bold text-slate-400 transition-colors duration-300';

                return;
            }

            if (savedIndex < currentStepIndex) {
                stepIcon.className = 'step-icon h-10 w-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-emerald-100 transition-all duration-300';
                stepIcon.innerHTML = '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>';
                stepLabel.className = 'step-label text-[13px] font-bold text-emerald-600 transition-colors duration-300';

                return;
            }

            if (savedIndex === currentStepIndex) {
                stepIcon.className = 'step-icon h-10 w-10 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold text-sm shadow-xl shadow-primary-600/30 z-10 border-4 border-white ring-1 ring-primary-100 transition-all duration-300 scale-110';
                stepIcon.innerHTML = savedStep.id;
                stepLabel.className = 'step-label text-[13px] font-extrabold text-primary-800 transition-colors duration-300';

                return;
            }

            stepIcon.className = 'step-icon h-10 w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm z-10 border-4 border-white ring-1 ring-slate-100 transition-all duration-300';
            stepIcon.innerHTML = savedStep.id;
            stepLabel.className = 'step-label text-[13px] font-bold text-slate-400 transition-colors duration-300';
        });

        const headerStatusBadge = document.getElementById('headerStatusBadge');

        if (headerStatusBadge) {
            headerStatusBadge.textContent = selectedStatus;

            if (selectedStatus === 'Cancelled') {
                headerStatusBadge.className = 'inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-600 text-[11px] font-bold rounded-full uppercase tracking-wider';
            }

            if (selectedStatus !== 'Cancelled') {
                headerStatusBadge.className = 'inline-flex items-center px-2.5 py-1 bg-primary-50 text-primary-600 text-[11px] font-bold rounded-full uppercase tracking-wider';
            }
        }
    };

    // Rebuild the stepper when the stage changes.
    const orderStatusSelect = document.getElementById('orderStatus');

    if (orderStatusSelect) {
        orderStatusSelect.addEventListener('change', window.updateStepper);
    }

    window.updateStepper();
})();
</script>

@endsection

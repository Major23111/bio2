@extends('admin.layout')

@section('title', 'Customer Details - Biogenix Admin')

@section('admin_content')

    @php
        $customerName = request('name', 'Nova Scientific Group');
        $customerEmail = request('email', 'contact@nova.com');
        $customerCategory = strtoupper((string) request('category', 'B2B'));
        $customerStatus = request('status', 'Active');
        $customerDate = request('date', 'Oct 12, 2023');
        $customerInitials = request('initials', 'NS');
        $isB2bCustomer = $customerCategory === 'B2B';
        $creditLimit = (string) request('credit_limit', $isB2bCustomer ? '25000' : '');
        $creditDays = (string) request('credit_days', $isB2bCustomer ? '30' : '7');
        $unlimitedCredit = filter_var(request('unlimited_credit', false), FILTER_VALIDATE_BOOL);
        $customerPhone = request('phone', $isB2bCustomer ? '+91 98765 43210' : '+91 91234 56789');
        $addressLines = $isB2bCustomer
            ? ['Suite 402, Biotech Towers,', 'Sector 12, Gomti Nagar,', 'Lucknow, UP - 226010']
            : ['22/18 Green Residency,', 'Aliganj,', 'Lucknow, UP - 226024'];
        $customerNotes = $isB2bCustomer
            ? 'Premium B2B account. Interested in high-volume pathology kits. Expedited shipping preferred for Lucknow local deliveries.'
            : 'Retail customer with repeat orders. Prefers prepaid orders and standard delivery timelines.';
        $customerId = $isB2bCustomer ? '#CUST-99021' : '#CUST-11284';
    @endphp

    <div class="mb-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.customers') }}" class="ajax-link group flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-primary-100 hover:bg-primary-50 hover:text-primary-600" title="Back to Customer Directory">
                <svg class="h-5 w-5 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <div class="mb-1 flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                    <span>Customer Management</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-600">Customer Details</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $customerName }}</h1>
                <p class="mt-1 text-sm text-slate-500">ID: {{ $customerId }} &bull; Member since {{ $customerDate }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.AdminToast ? window.AdminToast.show('All changes saved successfully', 'success') : alert('All changes saved successfully')" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700 cursor-pointer">
                Save Changes
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[var(--ui-shadow-soft)]">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Orders</p>
                    <p class="text-2xl font-black text-slate-900">{{ $isB2bCustomer ? '248' : '34' }}</p>
                    <p class="mt-1 text-[12px] font-bold text-emerald-600">{{ $isB2bCustomer ? '+12 this month' : '+3 this month' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[var(--ui-shadow-soft)]">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Revenue Generated</p>
                    <p class="text-2xl font-black text-slate-900">{{ $isB2bCustomer ? 'Rs. 45,280.00' : 'Rs. 8,420.00' }}</p>
                    <p class="mt-1 text-[12px] font-bold text-emerald-600">{{ $isB2bCustomer ? 'LTV: Rs. 182.50/ord' : 'LTV: Rs. 247.65/ord' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[var(--ui-shadow-soft)]">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Avg. Order Value</p>
                    <p class="text-2xl font-black text-slate-900">{{ $isB2bCustomer ? 'Rs. 1,825.00' : 'Rs. 247.00' }}</p>
                    <p class="mt-1 text-[12px] font-bold text-slate-400">Based on last 50</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-extrabold text-slate-900">Customer Profile & Contact</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">Full Name / Organization</label>
                            <p class="text-sm font-bold text-slate-900">{{ $customerName }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">Email Address</label>
                            <p class="text-sm font-bold text-slate-900">{{ $customerEmail }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">Phone Number</label>
                            <p class="text-sm font-bold text-slate-900">{{ $customerPhone }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">Primary Address</label>
                            <p class="text-sm font-bold leading-relaxed text-slate-900">
                                @foreach($addressLines as $line)
                                    {{ $line }}<br>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-extrabold text-slate-900">Internal Admin Notes</h2>
                </div>
                <div class="p-6">
                    <textarea rows="4" class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600" placeholder="Add confidential notes about this customer account for internal review...">{{ $customerNotes }}</textarea>
                    <p class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        These notes are only visible to administrators.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-extrabold text-slate-900">Account Status</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3" id="customer-status-buttons" data-initial-status="{{ $customerStatus }}">
                        @foreach(['Active', 'Suspended', 'Inactive'] as $statusOption)
                            <button type="button" onclick="updateAccountStatus(this, '{{ $statusOption }}')" data-status-option="{{ $statusOption }}" class="flex w-full items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100">
                                <span>{{ $statusOption }}</span>
                                <div class="h-5 w-5"></div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-extrabold text-slate-900">Classification & Credit</h2>
                </div>
                <div class="space-y-6 p-6">
                    <div>
                        <label for="customer-category-select" class="mb-2.5 block text-[11px] font-bold uppercase tracking-widest text-slate-500">Customer Category</label>
                        <select id="customer-category-select" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition shadow-sm focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            <option value="B2B" @selected($customerCategory === 'B2B')>B2B (Wholesale)</option>
                            <option value="B2C" @selected($customerCategory === 'B2C')>B2C (Retail)</option>
                        </select>
                    </div>

                    <div id="customer-credit-settings" class="@if(!$isB2bCustomer) hidden @endif space-y-5">
                        <div>
                            <label for="customer-credit-limit" class="mb-2.5 block text-[11px] font-bold uppercase tracking-widest text-slate-500">Credit Limit (INR)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 font-bold text-slate-400">Rs.</div>
                                <input id="customer-credit-limit" type="number" value="{{ $creditLimit }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm font-extrabold tracking-tight text-slate-900 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            </div>
                            <p class="mt-2 text-[10px] italic text-slate-400">Controls the maximum outstanding balance allowed for post-paid orders.</p>
                        </div>

                        <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 cursor-pointer">
                            <input id="customer-unlimited-credit" type="checkbox" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600" @checked($unlimitedCredit)>
                            <div>
                                <span class="block text-sm font-bold text-slate-900">Unlimited Credit Limit</span>
                                <span class="mt-0.5 block text-xs font-medium text-slate-500">Only the credit period in days remains required when this is enabled.</span>
                            </div>
                        </label>

                        <div>
                            <label for="customer-credit-days" class="mb-2.5 block text-[11px] font-bold uppercase tracking-widest text-slate-500">Number Of Days</label>
                            <select id="customer-credit-days" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                                <option value="7" @selected($creditDays === '7')>7 Days</option>
                                <option value="15" @selected($creditDays === '15')>15 Days</option>
                                <option value="30" @selected($creditDays === '30')>30 Days</option>
                                <option value="45" @selected($creditDays === '45')>45 Days</option>
                                <option value="60" @selected($creditDays === '60')>60 Days</option>
                                <option value="90" @selected($creditDays === '90')>90 Days</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-rose-100 bg-rose-50/50 p-6">
                <h3 class="mb-1 text-sm font-bold text-rose-800">Remove Account</h3>
                <p class="mb-4 text-[12px] text-rose-600/80">Once deleted, you will not be able to recover this customer record.</p>
                <button type="button" onclick="confirm('Are you sure you want to permanently delete this customer record?')" class="w-full rounded-xl border border-rose-200 bg-white py-2.5 text-xs font-bold text-rose-600 transition hover:bg-rose-600 hover:text-white cursor-pointer">Delete Customer Record</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const statusContainer = document.getElementById('customer-status-buttons');
            const categorySelect = document.getElementById('customer-category-select');
            const creditSettings = document.getElementById('customer-credit-settings');
            const creditLimitInput = document.getElementById('customer-credit-limit');
            const unlimitedCreditCheckbox = document.getElementById('customer-unlimited-credit');

            function showToast(message, type) {
                if (window.AdminToast) {
                    window.AdminToast.show(message, type);
                    return;
                }

                alert(message);
            }

            function iconMarkup(type) {
                if (type === 'active') {
                    return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
                }

                if (type === 'suspended') {
                    return '<svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>';
                }

                return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>';
            }

            function applyStatusButtonState(button, status) {
                const iconSlot = button.querySelector('div, svg');

                if (status === 'Active') {
                    button.className = 'flex w-full items-center justify-between rounded-xl border-2 border-primary-600 bg-primary-50 px-4 py-3 text-sm font-bold text-primary-700 transition';
                    if (iconSlot) iconSlot.outerHTML = iconMarkup('active');
                    return;
                }

                if (status === 'Suspended') {
                    button.className = 'flex w-full items-center justify-between rounded-xl border-2 border-rose-500 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700 transition';
                    if (iconSlot) iconSlot.outerHTML = iconMarkup('suspended');
                    return;
                }

                button.className = 'flex w-full items-center justify-between rounded-xl border-2 border-slate-400 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-700 transition';
                if (iconSlot) iconSlot.outerHTML = iconMarkup('inactive');
            }

            window.updateAccountStatus = function (button, status) {
                if (!statusContainer) return;

                statusContainer.querySelectorAll('[data-status-option]').forEach(function (statusButton) {
                    statusButton.className = 'flex w-full items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100';
                    const iconElement = statusButton.querySelector('div, svg');
                    if (iconElement) {
                        iconElement.outerHTML = '<div class="h-5 w-5"></div>';
                    }
                });

                applyStatusButtonState(button, status);
                showToast('Status changed to ' + status, 'info');
            };

            function syncCreditVisibility() {
                const isB2b = categorySelect && categorySelect.value === 'B2B';
                if (!creditSettings) return;

                creditSettings.classList.toggle('hidden', !isB2b);
                if (!isB2b) return;

                syncUnlimitedCreditState();
            }

            function syncUnlimitedCreditState() {
                if (!creditLimitInput || !unlimitedCreditCheckbox) return;

                const unlimitedEnabled = unlimitedCreditCheckbox.checked;
                creditLimitInput.disabled = unlimitedEnabled;

                if (unlimitedEnabled) {
                    creditLimitInput.value = '';
                }
            }

            categorySelect?.addEventListener('change', function () {
                syncCreditVisibility();
                showToast(categorySelect.value === 'B2B' ? 'B2B credit settings enabled.' : 'B2C customer selected. Credit limit hidden.', 'info');
            });

            unlimitedCreditCheckbox?.addEventListener('change', syncUnlimitedCreditState);

            if (statusContainer) {
                const initialStatus = statusContainer.dataset.initialStatus || 'Active';
                const initialButton = statusContainer.querySelector('[data-status-option="' + initialStatus + '"]');
                if (initialButton) {
                    applyStatusButtonState(initialButton, initialStatus);
                }
            }

            syncCreditVisibility();
        })();
    </script>
    @endpush

@endsection

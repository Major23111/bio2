@extends('admin.layout')

@section('title', 'Customer Management - Biogenix Admin')

@section('admin_content')

    @php
        $pendingApplicants = [
            ['id' => 1, 'name' => 'MediLab Solutions', 'email' => 'contact@medilab.co', 'category' => 'B2B', 'applicationLabel' => 'Applied for B2B', 'initials' => 'ML', 'color' => 'bg-primary-600'],
            ['id' => 2, 'name' => 'Arthur P. Morgon', 'email' => 'arthur.m@gmail.com', 'category' => 'B2C', 'applicationLabel' => 'Applied for Retail', 'initials' => 'AP', 'color' => 'bg-tertiary-600'],
            ['id' => 3, 'name' => 'BioLink Diagnostics', 'email' => 'info@biolink.in', 'category' => 'B2B', 'applicationLabel' => 'Applied for B2B', 'initials' => 'BL', 'color' => 'bg-primary-600'],
        ];

        $customers = [
            ['name' => 'Nova Scientific Group', 'email' => 'contact@nova.com', 'category' => 'B2B', 'status' => 'Active', 'date' => 'Oct 12, 2023', 'initials' => 'NS', 'color' => 'var(--color-primary-600)', 'creditLimit' => '25000', 'creditDays' => '30', 'unlimitedCredit' => false],
            ['name' => 'David Wilson', 'email' => 'david.w@provider.net', 'category' => 'B2C', 'status' => 'Active', 'date' => 'Nov 05, 2023', 'initials' => 'DW', 'color' => 'var(--color-secondary-700)', 'creditLimit' => '', 'creditDays' => '7', 'unlimitedCredit' => false],
            ['name' => 'Bio-Chem Logistics', 'email' => 'billing@biochem.log', 'category' => 'B2B', 'status' => 'Suspended', 'date' => 'Aug 22, 2023', 'initials' => 'BC', 'color' => 'var(--color-primary-600)', 'creditLimit' => '50000', 'creditDays' => '45', 'unlimitedCredit' => false],
            ['name' => 'Elena Rodriguez', 'email' => 'elena.rod@webmail.com', 'category' => 'B2C', 'status' => 'Active', 'date' => 'Dec 01, 2023', 'initials' => 'ER', 'color' => 'var(--color-tertiary-600)', 'creditLimit' => '', 'creditDays' => '7', 'unlimitedCredit' => false],
            ['name' => 'Omni BioSystems Ltd', 'email' => 'ops@omnibiosys.com', 'category' => 'B2B', 'status' => 'Active', 'date' => 'Jan 15, 2024', 'initials' => 'OB', 'color' => 'var(--color-secondary-700)', 'creditLimit' => '100000', 'creditDays' => '60', 'unlimitedCredit' => true],
            ['name' => 'Clara Mendez', 'email' => 'c.mendez@gmail.com', 'category' => 'B2C', 'status' => 'Inactive', 'date' => 'Feb 20, 2024', 'initials' => 'CM', 'color' => 'var(--color-tertiary-600)', 'creditLimit' => '', 'creditDays' => '7', 'unlimitedCredit' => false],
            ['name' => 'LabCore Sciences', 'email' => 'admin@labcore.io', 'category' => 'B2B', 'status' => 'Active', 'date' => 'Mar 03, 2024', 'initials' => 'LC', 'color' => 'var(--color-primary-600)', 'creditLimit' => '75000', 'creditDays' => '90', 'unlimitedCredit' => false],
            ['name' => 'Thomas Reinholt', 'email' => 't.reinholt@bionet.de', 'category' => 'B2C', 'status' => 'Active', 'date' => 'Mar 10, 2024', 'initials' => 'TR', 'color' => 'var(--color-tertiary-600)', 'creditLimit' => '', 'creditDays' => '7', 'unlimitedCredit' => false],
        ];

        $b2bCustomers = array_values(array_filter($customers, static fn($customer) => $customer['category'] === 'B2B'));
    @endphp

    <div class="mb-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Customer Management</h1>
            <p class="mt-1 text-sm text-slate-500">Manage customer accounts, verifications, and access settings.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative w-64">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input id="global-customer-search" type="text" placeholder="Global search..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
            </div>
        </div>
    </div>

    <div id="pending-verifications-section" class="mb-5 rounded-2xl border border-slate-200/60 bg-slate-100 px-6 py-4">
        <div class="mb-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="h-5 w-5 shrink-0 text-secondary-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
                <span id="pending-count-label" class="text-sm font-bold text-amber-800">Pending User Verifications
                    ({{ count($pendingApplicants) }})</span>
            </div>
            <button type="button"
                class="text-[12px] font-bold text-secondary-700 transition hover:text-amber-900 cursor-pointer">View All
                Pending</button>
        </div>

        <div id="pending-list" class="space-y-2.5">
            @foreach($pendingApplicants as $applicant)
                <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-100 bg-white px-4 py-3 shadow-sm"
                    data-pending-id="{{ $applicant['id'] }}" data-applicant-name="{{ $applicant['name'] }}"
                    data-application-category="{{ $applicant['category'] }}">
                    <div class="flex min-w-0 items-center gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $applicant['color'] }} text-[11px] font-black text-white">
                            {{ $applicant['initials'] }}</div>
                        <div class="min-w-0">
                            <p class="truncate text-[13px] font-bold text-slate-900">{{ $applicant['name'] }}</p>
                            <p class="truncate text-[11px] font-medium text-slate-500">{{ $applicant['email'] }} &bull;
                                {{ $applicant['applicationLabel'] }}</p>
                        </div>
                    </div>
                    <div class="pending-action-group flex shrink-0 items-center gap-2">
                        <button type="button" onclick="handleVerification(this, 'approve', {{ $applicant['id'] }})"
                            class="rounded-lg bg-primary-600 px-4 py-1.5 text-[12px] font-bold text-white transition hover:bg-primary-700 cursor-pointer">Approve</button>
                        <button type="button" onclick="handleVerification(this, 'reject', {{ $applicant['id'] }})"
                            class="rounded-lg border border-rose-200 bg-white px-4 py-1.5 text-[12px] font-bold text-rose-600 transition hover:bg-rose-50 cursor-pointer">Reject</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-[var(--ui-shadow-soft)]">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Select Specific B2B Client</h2>
                <p class="mt-1 text-[13px] text-slate-500">Use the type-ahead dropdown to jump directly to a B2B customer
                    profile.</p>
            </div>
            <div class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Quick Access</div>
        </div>

        <div id="b2b-client-typeahead" class="relative mt-4">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input id="b2b-client-search" type="text" autocomplete="off"
                    placeholder="Search B2B company by name or business email..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
            </div>

            <input id="b2b-client-selected-url" type="hidden" value="">

            <div id="b2b-client-suggestions"
                class="absolute inset-x-0 top-full z-20 mt-2 hidden max-h-72 overflow-y-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                @foreach($b2bCustomers as $customer)
                    @php
                        $detailsUrl = route('admin.customers.details', [
                            'name' => $customer['name'],
                            'email' => $customer['email'],
                            'category' => $customer['category'],
                            'status' => $customer['status'],
                            'date' => $customer['date'],
                            'initials' => $customer['initials'],
                            'credit_limit' => $customer['creditLimit'],
                            'credit_days' => $customer['creditDays'],
                            'unlimited_credit' => $customer['unlimitedCredit'] ? 1 : 0,
                        ]);
                    @endphp
                    <button type="button"
                        class="b2b-client-option flex w-full items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-left transition hover:bg-slate-50 cursor-pointer"
                        data-name="{{ strtolower($customer['name']) }}" data-email="{{ strtolower($customer['email']) }}"
                        data-url="{{ $detailsUrl }}">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900">{{ $customer['name'] }}</p>
                            <p class="text-xs font-medium text-slate-500">{{ $customer['email'] }}</p>
                        </div>
                        <span
                            class="inline-flex shrink-0 items-center rounded-full border border-primary-200/60 bg-primary-50 px-2.5 py-1 text-[10px] font-bold text-primary-700">B2B</span>
                    </button>
                @endforeach

                <div id="b2b-client-empty-state" class="hidden px-3 py-4 text-sm font-medium text-slate-400">No matching B2B
                    clients found.</div>
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button id="view-selected-b2b-client" type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700 cursor-pointer disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-500 disabled:shadow-none"
                disabled>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View Customer Details
            </button>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
        <div class="flex flex-col justify-between gap-3 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Customer Directory</h2>
                <p class="text-[13px] text-slate-500">Manage and filter your global customer database</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <select id="category-filter"
                        class="appearance-none rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 pr-7 text-[13px] font-semibold text-slate-700 outline-none transition hover:border-slate-300 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 cursor-pointer">
                        <option value="">All Categories</option>
                        <option value="B2B">B2B</option>
                        <option value="B2C">B2C</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse whitespace-nowrap text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-white">
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400">Customer Name
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400">Email Address
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400">Category</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400">Date Joined
                        </th>
                        <th class="px-6 py-4 text-right text-[11px] font-bold uppercase tracking-widest text-slate-400">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="customer-table-body" class="divide-y divide-slate-100">
                    @if (count($customers))
                        @foreach($customers as $customer)
                            @php
                                $statusClasses = match ($customer['status']) {
                                    'Active' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
                                    'Suspended' => 'bg-rose-50 text-rose-700 border border-rose-200/60',
                                    'Inactive' => 'bg-slate-100 text-slate-600 border border-slate-200/60',
                                    default => 'bg-slate-100 text-slate-600 border border-slate-200/60',
                                };

                                $categoryClasses = match ($customer['category']) {
                                    'B2B' => 'bg-primary-50 text-primary-700 border border-primary-200/60',
                                    'B2C' => 'bg-primary-50 text-primary-700 border border-primary-200/60',
                                    default => 'bg-slate-100 text-slate-600 border border-slate-200/60',
                                };

                                $detailsUrl = route('admin.customers.details', [
                                    'name' => $customer['name'],
                                    'email' => $customer['email'],
                                    'category' => $customer['category'],
                                    'status' => $customer['status'],
                                    'date' => $customer['date'],
                                    'initials' => $customer['initials'],
                                    'credit_limit' => $customer['creditLimit'],
                                    'credit_days' => $customer['creditDays'],
                                    'unlimited_credit' => $customer['unlimitedCredit'] ? 1 : 0,
                                ]);
                            @endphp
                            <tr class="customer-row cursor-pointer transition-colors hover:bg-slate-50/50"
                                data-name="{{ strtolower($customer['name']) }}" data-email="{{ strtolower($customer['email']) }}"
                                data-category="{{ $customer['category'] }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[10px] font-black text-white"
                                            style="background-color: {{ $customer['color'] }}">{{ $customer['initials'] }}</div>
                                        <span class="text-[13px] font-bold text-slate-900">{{ $customer['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[13px] font-medium text-slate-600">{{ $customer['email'] }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold {{ $categoryClasses }}">{{ $customer['category'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusClasses }}">{{ $customer['status'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-[13px] font-medium text-slate-500">{{ $customer['date'] }}</td>
                                <td class="flex justify-end px-6 py-4 text-right">
                                    <a href="{{ $detailsUrl }}"
                                        class="ajax-link inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary-700 text-white shadow-sm transition-all hover:-translate-y-px hover:bg-primary-800 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 cursor-pointer"
                                        title="View Customer Details">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50">
                                        <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-extrabold uppercase tracking-widest text-slate-900">No Customers
                                        Found</h3>
                                    <p class="mt-1 text-xs text-slate-400">There are no customer records matching your current
                                        filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-100 px-6 py-4 sm:flex-row">
            <div>
                <a href="{{ route('admin.customer-directory') }}"
                    class="ajax-link flex items-center gap-1 text-[13px] font-bold text-primary-800 hover:underline cursor-pointer">
                    View More Records
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <p class="mt-0.5 text-[12px] text-slate-400">Showing 1 to 25 of 248 customers</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button"
                    class="flex h-9 w-9 items-center justify-center rounded border border-slate-200 bg-white text-slate-400 transition hover:bg-slate-50 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="flex text-[13px] font-semibold">
                    <button type="button"
                        class="flex h-9 w-9 items-center justify-center rounded bg-primary-600 text-white cursor-pointer">1</button>
                    <button type="button"
                        class="flex h-9 w-9 items-center justify-center rounded border border-transparent bg-white text-slate-600 transition hover:border-slate-200 hover:bg-slate-50 cursor-pointer">2</button>
                    <button type="button"
                        class="flex h-9 w-9 items-center justify-center rounded border border-transparent bg-white text-slate-600 transition hover:border-slate-200 hover:bg-slate-50 cursor-pointer">3</button>
                    <span class="flex h-9 w-9 items-center justify-center text-slate-400">...</span>
                    <button type="button"
                        class="flex h-9 w-9 items-center justify-center rounded border border-transparent bg-white text-slate-600 transition hover:border-slate-200 hover:bg-slate-50 cursor-pointer">31</button>
                </div>
                <button type="button"
                    class="flex h-9 w-9 items-center justify-center rounded border border-slate-200 bg-white text-slate-400 transition hover:bg-slate-50 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="verification-modal" class="fixed inset-0 z-[1000] hidden items-center justify-center" role="dialog"
        aria-modal="true" aria-labelledby="verification-modal-title">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm" onclick="closeVerificationModal()"></div>
        <div class="relative z-10 w-full max-w-lg px-4 py-6">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl animate-fade-in">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <h3 id="verification-modal-title" class="text-lg font-extrabold text-slate-900">Approve Customer
                        </h3>
                        <p id="verification-modal-summary" class="mt-1 text-sm text-slate-500">Review this customer
                            application before updating the verification status.</p>
                    </div>
                    <button type="button" onclick="closeVerificationModal()"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 cursor-pointer"
                        aria-label="Close verification modal">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <input id="verification-modal-action" type="hidden" value="">
                <input id="verification-modal-id" type="hidden" value="">
                <input id="verification-modal-category" type="hidden" value="">

                <div id="verification-credit-limit-container"
                    class="hidden space-y-4 rounded-2xl border border-primary-100 bg-primary-50/40 p-4">
                    <div>
                        <label for="verification-credit-limit"
                            class="mb-2 block text-[11px] font-bold uppercase tracking-widest text-slate-500">Credit
                            Limit</label>
                        <select id="verification-credit-limit"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                            <option value="">Select credit limit</option>
                            <option value="10000">Rs. 10,000</option>
                            <option value="25000">Rs. 25,000</option>
                            <option value="50000">Rs. 50,000</option>
                            <option value="100000">Rs. 1,00,000</option>
                            <option value="250000">Rs. 2,50,000</option>
                        </select>
                    </div>

                    <label
                        class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 cursor-pointer">
                        <input id="verification-unlimited-credit" type="checkbox"
                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
                        <div>
                            <span class="block text-sm font-bold text-slate-900">Unlimited Credit Limit</span>
                            <span class="mt-0.5 block text-xs font-medium text-slate-500">When enabled, only the number of
                                days remains required.</span>
                        </div>
                    </label>

                    <div>
                        <label for="verification-credit-days"
                            class="mb-2 block text-[11px] font-bold uppercase tracking-widest text-slate-500">Number Of
                            Days</label>
                        <select id="verification-credit-days"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                            <option value="">Select number of days</option>
                            <option value="7">7 Days</option>
                            <option value="15">15 Days</option>
                            <option value="30">30 Days</option>
                            <option value="45">45 Days</option>
                            <option value="60">60 Days</option>
                            <option value="90">90 Days</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeVerificationModal()"
                        class="flex-1 rounded-xl border border-slate-200 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50 cursor-pointer">Cancel</button>
                    <button id="verification-confirm-btn" type="button" onclick="confirmVerification()"
                        class="flex-1 rounded-xl bg-primary-600 py-2.5 text-sm font-bold text-white transition hover:bg-primary-700 cursor-pointer shadow-md shadow-primary-600/20">Approve
                        &amp; Save</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(8px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.2s ease-out forwards;
        }
    </style>

    <script>
        (function () {
            const modal = document.getElementById('verification-modal');
            const modalActionInput = document.getElementById('verification-modal-action');
            const modalIdInput = document.getElementById('verification-modal-id');
            const modalCategoryInput = document.getElementById('verification-modal-category');
            const modalTitle = document.getElementById('verification-modal-title');
            const modalSummary = document.getElementById('verification-modal-summary');
            const confirmBtn = document.getElementById('verification-confirm-btn');
            const creditContainer = document.getElementById('verification-credit-limit-container');
            const creditLimitSelect = document.getElementById('verification-credit-limit');
            const creditDaysSelect = document.getElementById('verification-credit-days');
            const unlimitedCreditCheckbox = document.getElementById('verification-unlimited-credit');
            const pendingList = document.getElementById('pending-list');
            const pendingCountLabel = document.getElementById('pending-count-label');
            const pendingSection = document.getElementById('pending-verifications-section');
            const categoryFilter = document.getElementById('category-filter');
            const globalSearchInput = document.getElementById('global-customer-search');
            const b2bSearchInput = document.getElementById('b2b-client-search');
            const b2bSuggestions = document.getElementById('b2b-client-suggestions');
            const b2bSelectedUrlInput = document.getElementById('b2b-client-selected-url');
            const viewSelectedB2bClientBtn = document.getElementById('view-selected-b2b-client');
            const b2bEmptyState = document.getElementById('b2b-client-empty-state');
            const b2bOptions = Array.from(document.querySelectorAll('.b2b-client-option'));

            function showToast(message, type) {
                if (window.AdminToast) {
                    window.AdminToast.show(message, type);
                    return;
                }

                alert(message);
            }

            function setBodyScrollLocked(locked) {
                document.body.style.overflow = locked ? 'hidden' : '';
            }

            function updateUnlimitedCreditState() {
                if (!creditLimitSelect || !creditDaysSelect || !unlimitedCreditCheckbox) return;

                const creditFieldsVisible = !creditContainer.classList.contains('hidden');
                const unlimitedEnabled = unlimitedCreditCheckbox.checked;

                creditLimitSelect.disabled = unlimitedEnabled || !creditFieldsVisible;
                creditLimitSelect.required = creditFieldsVisible && !unlimitedEnabled;
                creditDaysSelect.required = creditFieldsVisible;

                if (unlimitedEnabled) {
                    creditLimitSelect.value = '';
                }
            }

            function resetVerificationForm() {
                if (creditLimitSelect) creditLimitSelect.value = '';
                if (creditDaysSelect) creditDaysSelect.value = '';
                if (unlimitedCreditCheckbox) unlimitedCreditCheckbox.checked = false;
                updateUnlimitedCreditState();
            }

            function getPendingCardById(id) {
                return document.querySelector('[data-pending-id="' + id + '"]');
            }

            function getRemainingPendingCount() {
                if (!pendingList) return 0;
                return pendingList.querySelectorAll('[data-pending-id]:not([data-removing="true"])').length;
            }

            function updatePendingCount() {
                const remaining = getRemainingPendingCount();

                if (pendingCountLabel) {
                    pendingCountLabel.textContent = 'Pending User Verifications (' + remaining + ')';
                }

                if (remaining > 0 || !pendingSection) return;

                setTimeout(function () {
                    pendingSection.style.opacity = '0';
                    pendingSection.style.transition = 'opacity 0.35s ease';
                    setTimeout(function () {
                        pendingSection.remove();
                    }, 350);
                }, 1200);
            }

            window.closeVerificationModal = function () {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                setBodyScrollLocked(false);
                resetVerificationForm();
            };

            window.handleVerification = function (btn, action, id) {
                const card = btn ? btn.closest('[data-pending-id]') : getPendingCardById(id);
                if (!card || !modal) return;

                const applicantName = card.dataset.applicantName || 'Customer';
                const applicationCategory = card.dataset.applicationCategory || 'B2C';
                const isApproval = action === 'approve';
                const showB2bCreditFields = isApproval && applicationCategory === 'B2B';

                if (modalActionInput) modalActionInput.value = action;
                if (modalIdInput) modalIdInput.value = String(id);
                if (modalCategoryInput) modalCategoryInput.value = applicationCategory;

                creditContainer.classList.toggle('hidden', !showB2bCreditFields);
                resetVerificationForm();

                if (isApproval) {
                    modalTitle.textContent = 'Approve ' + applicantName;
                    modalSummary.textContent = applicantName + ' applied as a ' + applicationCategory + ' customer. Review the approval details below before continuing.';
                    confirmBtn.textContent = 'Approve & Save';
                    confirmBtn.className = 'flex-1 rounded-xl bg-primary-600 py-2.5 text-sm font-bold text-white transition hover:bg-primary-700 cursor-pointer shadow-md shadow-primary-600/20';
                } else {
                    modalTitle.textContent = 'Reject ' + applicantName;
                    modalSummary.textContent = 'Confirm rejection for ' + applicantName + '. This action removes the request from the pending approval queue.';
                    confirmBtn.textContent = 'Reject Customer';
                    confirmBtn.className = 'flex-1 rounded-xl bg-rose-600 py-2.5 text-sm font-bold text-white transition hover:bg-rose-700 cursor-pointer shadow-md shadow-rose-600/20';
                }

                updateUnlimitedCreditState();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setBodyScrollLocked(true);
            };

            window.confirmVerification = function () {
                const action = modalActionInput ? modalActionInput.value : '';
                const id = modalIdInput ? modalIdInput.value : '';
                const applicationCategory = modalCategoryInput ? modalCategoryInput.value : 'B2C';
                const isApproval = action === 'approve';
                const isB2bApproval = isApproval && applicationCategory === 'B2B';

                if (isB2bApproval) {
                    if (!unlimitedCreditCheckbox.checked && !creditLimitSelect.value) {
                        showToast('Select a credit limit or enable Unlimited Credit Limit first.', 'info');
                        creditLimitSelect.focus();
                        return;
                    }

                    if (!creditDaysSelect.value) {
                        showToast('Select the number of days for the B2B credit period.', 'info');
                        creditDaysSelect.focus();
                        return;
                    }
                }

                const card = getPendingCardById(id);
                if (!card) {
                    closeVerificationModal();
                    return;
                }

                const label = isApproval ? 'Approved' : 'Rejected';
                const colorClass = isApproval ? 'text-primary-600' : 'text-rose-500';
                const metaText = isB2bApproval
                    ? (unlimitedCreditCheckbox.checked
                        ? 'Unlimited credit limit for ' + creditDaysSelect.value + ' days'
                        : 'Credit limit Rs. ' + Number(creditLimitSelect.value).toLocaleString('en-IN') + ' for ' + creditDaysSelect.value + ' days')
                    : '';

                card.dataset.removing = 'true';
                card.innerHTML = '<div class="flex items-start justify-between gap-4"><div><div class="flex items-center gap-2 py-1"><svg class="h-4 w-4 ' + colorClass + '" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="' + (isApproval ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12') + '"/></svg><span class="text-[12px] font-bold ' + colorClass + '">' + label + '</span></div>' + (metaText ? '<p class="pl-6 text-[11px] font-medium text-slate-500">' + metaText + '</p>' : '') + '</div></div>';

                closeVerificationModal();
                updatePendingCount();

                setTimeout(function () {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-6px)';
                    card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                }, 900);

                setTimeout(function () {
                    card.remove();
                }, 1200);

                showToast(isApproval ? 'Customer application approved.' : 'Customer application rejected.', isApproval ? 'success' : 'info');
            };

            unlimitedCreditCheckbox?.addEventListener('change', updateUnlimitedCreditState);

            function applyCustomerFilters() {
                const categoryValue = categoryFilter ? categoryFilter.value : '';
                const searchValue = globalSearchInput ? globalSearchInput.value.trim().toLowerCase() : '';

                document.querySelectorAll('.customer-row').forEach(function (row) {
                    const rowMatchesCategory = !categoryValue || row.dataset.category === categoryValue;
                    const rowMatchesSearch = !searchValue
                        || (row.dataset.name || '').includes(searchValue)
                        || (row.dataset.email || '').includes(searchValue);

                    row.style.display = rowMatchesCategory && rowMatchesSearch ? '' : 'none';
                });
            }

            categoryFilter?.addEventListener('change', applyCustomerFilters);
            globalSearchInput?.addEventListener('input', applyCustomerFilters);

            function syncSelectedB2bClient(url) {
                if (b2bSelectedUrlInput) b2bSelectedUrlInput.value = url || '';
                if (viewSelectedB2bClientBtn) viewSelectedB2bClientBtn.disabled = !url;
            }

            function filterB2bSuggestions() {
                if (!b2bSuggestions || !b2bSearchInput) return;

                const query = b2bSearchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                b2bOptions.forEach(function (option) {
                    const matches = query === '' || option.dataset.name.includes(query) || option.dataset.email.includes(query);
                    option.classList.toggle('hidden', !matches);
                    if (matches) visibleCount += 1;
                });

                b2bEmptyState?.classList.toggle('hidden', visibleCount !== 0);

                if (visibleCount > 0 || query !== '') {
                    b2bSuggestions.classList.remove('hidden');
                } else {
                    b2bSuggestions.classList.add('hidden');
                }
            }

            b2bSearchInput?.addEventListener('focus', function () {
                filterB2bSuggestions();
            });

            b2bSearchInput?.addEventListener('input', function () {
                syncSelectedB2bClient('');
                filterB2bSuggestions();
            });

            b2bOptions.forEach(function (option) {
                option.addEventListener('click', function () {
                    if (!b2bSearchInput) return;

                    const nameElement = option.querySelector('p');
                    b2bSearchInput.value = nameElement ? nameElement.textContent : '';
                    syncSelectedB2bClient(option.dataset.url || '');
                    b2bSuggestions?.classList.add('hidden');
                });
            });

            viewSelectedB2bClientBtn?.addEventListener('click', function () {
                const url = b2bSelectedUrlInput ? b2bSelectedUrlInput.value : '';
                if (!url) return;

                window.location.href = url;
            });

            document.addEventListener('click', function (event) {
                if (b2bSearchInput && b2bSuggestions && !event.target.closest('#b2b-client-typeahead')) {
                    b2bSuggestions.classList.add('hidden');
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeVerificationModal();
                    b2bSuggestions?.classList.add('hidden');
                }

                if (event.key === 'Enter' && event.target === b2bSearchInput && viewSelectedB2bClientBtn && !viewSelectedB2bClientBtn.disabled) {
                    event.preventDefault();
                    viewSelectedB2bClientBtn.click();
                }
            });
        })();
    </script>

@endsection
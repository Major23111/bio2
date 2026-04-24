@extends('admin.layout')

@section('title', 'Delivery & Logistics - Biogenix Admin')

@section('admin_content')
    @php
        $adminUser = auth()->user();
        $adminName = $adminUser?->name ?: 'Admin User';
        $adminRole = $adminUser?->user_type ? strtoupper(str_replace('_', ' ', $adminUser->user_type)) : 'SUPER ADMINISTRATOR';
        $adminInitials = collect(explode(' ', trim($adminName)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        $rateCards = [
            [
                'badge' => 'Regional',
                'badge_classes' => 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100',
                'title' => 'PAN India (Excluding Lucknow)',
                'description' => 'Define the standard flat-rate delivery fee applicable across all operational Indian states and union territories, with the specific exclusion of the Lucknow city limits.',
                'label' => 'Base Delivery Rate (INR)',
                'field' => 'pan_india_rate',
                'value' => '150.00',
                'helper' => 'This rate will be applied automatically at checkout for all non-local zip codes.',
                'icon' => 'regional',
            ],
            [
                'badge' => 'Hyperlocal',
                'badge_classes' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
                'title' => 'Lucknow Local Delivery',
                'description' => 'Specific logistical pricing for last-mile delivery within the Lucknow municipal boundaries. This rate overrides the standard PAN India configuration.',
                'label' => 'Local Delivery Rate (INR)',
                'field' => 'lucknow_rate',
                'value' => '40.00',
                'helper' => 'Zip code validation (226xxx) is required for this rate activation.',
                'icon' => 'local',
            ],
        ];
    @endphp

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- Page Header (matches Dashboard pattern) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Delivery & Logistics</h2>
                <p class="text-sm text-[var(--ui-text-muted)] mt-1 font-medium">Configure global and local shipping parameters for distribution nodes.</p>
            </div>
            </div>
        </div>

        <!-- Shipping Rate Configuration Card -->
        <section class="overflow-hidden rounded-2xl border border-[var(--ui-border)] bg-[var(--ui-surface)] shadow-[var(--ui-shadow-soft)]">
            <div class="flex flex-col md:flex-row md:items-center justify-between p-6 border-b border-[var(--ui-border)] gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--ui-surface-subtle)] text-primary-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[11px] font-black text-[var(--ui-text-muted)] uppercase tracking-widest">Shipping Rate Configuration</h2>
                        <p class="text-[13px] font-medium text-[var(--ui-text-muted)] mt-0.5">Manage the default regional and local delivery fees.</p>
                    </div>
                </div>
            </div>

            <form id="delivery-config-form" class="p-6">
                <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                    @foreach ($rateCards as $card)
                        <article
                            class="group relative overflow-hidden rounded-2xl border border-[var(--ui-border)] bg-[var(--ui-surface)] p-6 shadow-sm transition duration-300 hover:shadow-[var(--ui-shadow-card)] hover:-translate-y-0.5 sm:p-7">
                            <div
                                class="pointer-events-none absolute right-5 top-5 flex h-16 w-16 items-center justify-center rounded-full bg-[var(--ui-surface-subtle)] text-[var(--ui-text-muted)]/20 transition duration-300 group-hover:scale-105">
                                @if ($card['icon'] === 'regional')
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.7">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 12h18M12 3c2.7 2.4 4 5.4 4 9s-1.3 6.6-4 9c-2.7-2.4-4-5.4-4-9s1.3-6.6 4-9z" />
                                    </svg>
                                @else
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 21s-6-4.35-6-10a6 6 0 1112 0c0 5.65-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2.5"></circle>
                                    </svg>
                                @endif
                            </div>

                            <div class="relative z-10 flex h-full flex-col">
                                <div class="mb-5">
                                    <span
                                        class="inline-flex items-center rounded-md px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] {{ $card['badge_classes'] }}">
                                        {{ $card['badge'] }}
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    <h3 class="max-w-[18rem] text-xl font-extrabold leading-tight tracking-tight text-[var(--ui-text)]">
                                        {{ $card['title'] }}
                                    </h3>
                                    <p class="max-w-[28rem] text-[13px] leading-6 text-[var(--ui-text-muted)]">
                                        {{ $card['description'] }}
                                    </p>
                                </div>

                                <div class="mt-6 space-y-2.5 pt-2">
                                    <label for="{{ $card['field'] }}"
                                        class="block text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">
                                        {{ $card['label'] }}
                                    </label>
                                    <div class="relative rounded-xl border border-[var(--ui-border)] bg-[var(--ui-input-bg)]">
                                        <span
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-base font-extrabold text-[var(--ui-text-muted)]">
                                            &#8377;
                                        </span>
                                        <input id="{{ $card['field'] }}" name="{{ $card['field'] }}" type="number"
                                            step="0.01" min="0" inputmode="decimal" value="{{ $card['value'] }}"
                                            class="delivery-rate-input h-12 w-full rounded-xl bg-transparent pl-10 pr-4 text-lg font-extrabold tracking-tight text-[var(--ui-text)] outline-none [appearance:textfield] focus:border-primary-600 focus:ring-1 focus:ring-primary-600/20 transition"
                                            data-default-value="{{ $card['value'] }}">
                                    </div>
                                    <p class="text-[11px] italic leading-5 text-[var(--ui-text-muted)]">{{ $card['helper'] }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Sticky Save Bar -->
                <div class="sticky bottom-4 z-20 mt-6 rounded-2xl border border-[var(--ui-border)] bg-[var(--ui-surface)]/95 p-4 shadow-[var(--ui-shadow-soft)] backdrop-blur sm:p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[var(--ui-surface-subtle)] text-[var(--ui-text-muted)]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[var(--ui-text)]">Delivery rate status</p>
                                <p id="delivery-config-status" class="text-[13px] font-medium text-[var(--ui-text-muted)]"
                                    role="status">
                                    Unsaved changes in delivery configuration.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">

                            <button id="delivery-save-btn" type="button"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-primary-600 bg-primary-600 px-6 text-[11px] font-black uppercase tracking-widest text-white shadow-lg shadow-primary-600/20 transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none active:scale-95">
                                <span>Save Changes</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <script>
        (function initializeDeliveryLogisticsPage() {
            const form = document.getElementById('delivery-config-form');
            if (!form || form.dataset.initialized === 'true') {
                return;
            }

            form.dataset.initialized = 'true';

            const inputs = Array.from(form.querySelectorAll('.delivery-rate-input'));
            const status = document.getElementById('delivery-config-status');
            const saveButton = document.getElementById('delivery-save-btn');

            const normalizeValue = function (value) {
                const numeric = Number.parseFloat(value);
                return Number.isFinite(numeric) ? numeric.toFixed(2) : '';
            };

            const syncState = function () {
                const hasChanges = inputs.some(function (input) {
                    return normalizeValue(input.value) !== normalizeValue(input.dataset.defaultValue);
                });

                saveButton.disabled = !hasChanges;
                status.textContent = hasChanges
                    ? 'Unsaved changes in delivery configuration.'
                    : 'All delivery rates are up to date.';
            };

            inputs.forEach(function (input) {
                input.addEventListener('input', syncState);
                input.addEventListener('blur', function () {
                    const normalized = normalizeValue(input.value);
                    input.value = normalized || input.dataset.defaultValue;
                    syncState();
                });
            });

            saveButton.addEventListener('click', function () {
                inputs.forEach(function (input) {
                    input.dataset.defaultValue = normalizeValue(input.value) || input.dataset.defaultValue;
                    input.value = input.dataset.defaultValue;
                });

                syncState();

                if (window.AdminToast && typeof window.AdminToast.show === 'function') {
                    window.AdminToast.show('Delivery configuration saved successfully.', 'success');
                }
            });

            syncState();
        })();
    </script>
@endsection

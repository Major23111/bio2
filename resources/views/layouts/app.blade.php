<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="@yield('meta_description', 'Biogenix Healthcare Solutions - Precision diagnostics, innovative life science research tools, and medical instruments for laboratories and healthcare professionals.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Biogenix')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('upload/icons/logo.jpg') }}?v=20260309">
    <link rel="shortcut icon" href="{{ asset('upload/icons/logo.jpg') }}?v=20260309">
    <link rel="apple-touch-icon" href="{{ asset('upload/icons/logo.jpg') }}?v=20260309">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body
    class="min-h-screen bg-[var(--ui-page-bg-gradient)] font-sans text-slate-800 antialiased">
    @php($suppressShellAlerts = request()->routeIs('login', 'forgot.password', 'signup', 'b2b.signup'))
    @php($isMinimalCustomerWorkspace = trim($__env->yieldContent('customer_minimal')) === 'minimal')
    @php($loaderLogoPath = public_path('upload/icons/biogenix3D.png'))
    @php($loaderLogoSrc = file_exists($loaderLogoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($loaderLogoPath)) : asset('upload/icons/logo.jpg'))
    @php($supportWidgetCategoryOptions = app(\App\Services\SupportTicket\SupportTicketService::class)->availableCategorySlugs())
    @php($supportWidgetDefaultPriority = \App\Services\SupportTicket\SupportTicketService::PRIORITIES[1] ?? 'medium')
    @php($supportWidgetShouldOpen = session('support_ticket_widget_open') || old('support_ticket_form_source') === 'layout_widget')
    @php($supportWidgetIsAuthenticated = auth()->check())

    <div id="toastContainer"
        class="pointer-events-none fixed right-4 top-5 z-[120] flex w-[min(calc(100vw-2rem),24rem)] flex-col gap-3 sm:right-6 sm:top-6">
    </div>

    @include('loader.loader')

    <style>
        html {
            scrollbar-gutter: stable both-edges;
            scroll-behavior: smooth;
        }

        /* Premium Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--ui-page-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--ui-border);
            border-radius: 5px;
            border: 2px solid var(--ui-page-bg);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary-500);
        }

        html.biogenix-loading,
        body.biogenix-loading {
            overflow: hidden;
        }

        #globalPageLoader {
            contain: layout paint style;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        @keyframes loader {
            0% {
                transform: translateX(-110%) scaleX(0.9);
            }

            50% {
                transform: translateX(100%) scaleX(1);
            }

            100% {
                transform: translateX(220%) scaleX(0.9);
            }
        }

        .loader-bar {
            animation: loader 1.6s ease-in-out infinite;
            will-change: transform;
        }
    </style>

    <div id="pageWrapper" class="flex min-h-screen flex-col">
        @include('partials.header')
        @include('partials.cart-sidebar')

        <main class="flex-1 transition-[padding] duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]">
            @unless ($suppressShellAlerts)
                @if ($errors->any())
                    <div class="mx-auto w-full max-w-7xl space-y-4 px-4 sm:px-6 lg:px-8 xl:px-10 py-6 mb-2">
                        @if ($errors->any())
                            <x-alert type="error">
                                <strong>Validation failed:</strong>
                                <ul class="mt-2 list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </x-alert>
                        @endif
                    </div>
                @endif
            @endunless

            <div class="w-full">
                @yield('content')
            </div>
        </main>

        @unless ($isMinimalCustomerWorkspace)
            @include('partials.footer')
        @endunless
    </div>

    <div class="fixed bottom-10 right-6 z-50 flex flex-col items-end gap-4">
        {{-- Back-to-Top Button --}}
        <button id="backToTopBtn" type="button" aria-label="Back to top"
            class="inline-flex h-12 w-12 translate-y-2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 opacity-0 shadow-lg ring-1 ring-slate-900/5 pointer-events-none transition-all duration-300 hover:-translate-y-1 hover:bg-primary-600 hover:text-white hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/30">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>

        @auth
            {{-- Support Ticket Toggle Button --}}
            <button onclick="toggleSupportForm()"
                class="group flex h-14 w-14 items-center justify-center rounded-full bg-primary-600 text-white shadow-xl shadow-primary-600/30 transition-all hover:scale-105 hover:bg-primary-600"
                aria-label="Open support ticket">
                <svg class="h-7 w-7 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.477 2 2 5.582 2 10c0 2.476 1.343 4.675 3.444 6.136.213 1.393-.454 3.125-.5 3.245a.5.5 0 00.643.64c.12-.046 1.85-.712 3.244-1.127A9.852 9.852 0 0012 18c5.523 0 10-3.582 10-8s-4.477-8-10-8zm-3 9a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
                </svg>
            </button>
        @endauth
    </div>

    {{-- Floating Support Form (Dedicated Compact Position) --}}
    {{-- Floating Support Form (Dedicated Compact Position) --}}
    <style>
        #supportTicketForm {
            position: fixed !important;
            right: 1.5rem !important;
            bottom: 6rem !important;
            width: 380px !important;
            max-width: calc(100vw - 2.5rem) !important;
            height: auto !important;
            max-height: min(85vh, 620px) !important;
            border-radius: 2.25rem !important;
            background: var(--ui-surface) !important;
            border: 1px solid var(--ui-border) !important;
            box-shadow: 0 48px 120px rgba(15, 23, 42, 0.3) !important;
            overflow: hidden !important;
            z-index: 99999 !important;
            display: none !important;
            /* Initial state, toggled by JS */
            flex-direction: column !important;
        }

        #supportTicketForm.flex {
            display: flex !important;
        }

        @media (max-width: 640px) {
            #supportTicketForm {
                right: 1.25rem !important;
                bottom: 5rem !important;
                width: calc(100vw - 2.5rem) !important;
            }
        }
    </style>
    @auth
        <div id="supportTicketForm"
            class="hidden flex-col bg-white border border-slate-200 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1),0_0_20px_-5px_rgba(26,77,46,0.05)] z-50 fixed bottom-24 right-6 w-full max-w-[380px] sm:max-h-[85vh] max-h-[85vh] rounded-[2.25rem] overflow-hidden"
            aria-labelledby="supportFormTitle" role="dialog" aria-modal="true">
            <div class="flex items-center justify-between bg-primary-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-md ring-1 ring-white/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 13v4m-2-2h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold tracking-tight text-white">Support Workspace</h3>
                        <p class="mt-0.5 text-[10px] font-medium text-blue-100 opacity-80">Response: &lt; 2 hours</p>
                    </div>
                </div>
                <button onclick="toggleSupportForm()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-blue-100 transition-colors hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div
                class="flex-1 overflow-y-auto bg-white p-5 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent overscroll-contain">
                <h4 class="mb-1 text-xl font-bold tracking-tight text-slate-900">Raise a Ticket</h4>
                <p class="mb-4 text-[13px] leading-relaxed text-slate-500">Submit your request and our technical experts
                    will assist you immediately.</p>

                @if ($supportWidgetIsAuthenticated)
                    <form action="{{ route('support-tickets.store') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col h-full min-h-0">
                        @csrf
                        <div class="flex-1 space-y-4 pb-6 min-h-0">
                            <input type="hidden" name="support_ticket_form_source" value="layout_widget">
                            <input type="hidden" name="priority" value="{{ $supportWidgetDefaultPriority }}">

                            @if ($supportWidgetShouldOpen && $errors->any())
                                <div class="mb-4 rounded-xl bg-red-50 p-4 text-xs text-red-600">
                                    <ul class="list-disc pl-4 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div>
                                <label for="supportSubject"
                                    class="mb-1 block text-[13px] font-semibold text-slate-800">Subject</label>
                                <input type="text" id="supportSubject" name="subject" value="{{ old('subject') }}"
                                    class="w-full rounded-md border border-slate-200 px-3 py-2 text-[13px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600"
                                    placeholder="Briefly describe the issue" required>
                            </div>

                            <div>
                                <label for="supportCategory"
                                    class="mb-1 block text-[13px] font-semibold text-slate-800">Category</label>
                                <div class="relative" id="customSupportCategory">
                                    <button type="button" onclick="toggleSupportCategoryList(event)"
                                        class="flex h-10 w-full items-center justify-between rounded-md border border-slate-200 bg-white px-3 text-[13px] text-slate-900 outline-none transition focus:border-primary-600 focus:ring-1 focus:ring-primary-600"
                                        id="supportCategoryTrigger">
                                        <span id="selectedCategoryText">Select a category</span>
                                        <svg class="pointer-events-none h-4 w-4 text-slate-400 transition-transform duration-200"
                                            id="supportCategoryChevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <input type="hidden" name="category_slug" id="supportCategoryInput"
                                        value="{{ old('category_slug') }}" required>

                                    <div id="supportCategoryList"
                                        class="absolute left-0 right-0 top-full z-[1000] mt-1 hidden max-h-48 overflow-y-auto rounded-xl border border-slate-200 bg-white py-1.5 shadow-xl">
                                        @forelse($supportWidgetCategoryOptions as $slug)
                                            <button type="button"
                                                onclick="selectSupportCategory('{{ $slug }}', '{{ ucwords(str_replace('-', ' ', $slug)) }}')"
                                                class="flex w-full items-center px-4 py-2 text-left text-[13px] text-slate-700 transition hover:bg-primary-50 hover:text-primary-700">
                                                {{ ucwords(str_replace('-', ' ', $slug)) }}
                                            </button>
                                        @empty
                                            <div class="px-4 py-2 text-[12px] text-slate-400 italic">No categories available</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="supportDescription"
                                    class="mb-1 block text-[13px] font-semibold text-slate-800">Message</label>
                                <textarea id="supportDescription" name="description" rows="3"
                                    class="w-full rounded-md border border-slate-200 px-3 py-2 text-[13px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600"
                                    placeholder="Provide detailed information about your request..."
                                    required>{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label class="mb-1 block text-[13px] font-semibold text-slate-800">Attachments</label>
                                <label
                                    class="flex cursor-pointer flex-col items-center justify-center rounded-md border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-center transition-colors hover:bg-slate-50">
                                    <svg class="mb-1.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="text-[10px] font-medium text-slate-500">Click to upload supporting files</span>
                                    <input type="file" class="hidden" name="attachments[]" multiple>
                                </label>
                            </div>
                        </div>

                        <div
                            class="sticky bottom-0 -mx-5 -mb-5 flex items-center justify-between border-t border-slate-100 bg-white px-5 pb-[6px] pt-3">
                            <span
                                class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Secure
                            </span>
                            <button type="submit"
                                class="inline-flex h-9 items-center justify-center rounded-md bg-primary-600 px-6 text-[13px] font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-primary-700">
                                Submit Ticket
                            </button>
                        </div>
                    </form>
                @else
                    <div class="space-y-4">
                        {{-- This keeps guest users on a clear path because support tickets belong to a signed-in account. --}}
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5 text-center">
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h5 class="mb-2 text-sm font-bold text-slate-900">Authentication Required</h5>
                            <p class="text-[13px] leading-relaxed text-slate-600">
                                Please sign in to your Biogenix account to raise a support ticket and track operational
                                coordination.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3">
                            <a href="{{ route('login') }}"
                                class="inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-6 text-[13px] font-bold text-white shadow-lg shadow-primary-600/20 transition hover:-translate-y-0.5 hover:bg-primary-700">
                                Sign In to Raise Ticket
                            </a>
                            <a href="{{ route('contact') }}"
                                class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-[13px] font-bold text-slate-700 transition hover:bg-slate-50">
                                Visit Help Center
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endauth

    <script>
        (function () {
            var btn = document.getElementById('backToTopBtn');
            if (!btn) return;

            var visible = false;

            function syncBtn() {
                var shouldShow = window.scrollY > 300;
                if (shouldShow === visible) return;
                visible = shouldShow;
                btn.classList.toggle('opacity-0', !visible);
                btn.classList.toggle('pointer-events-none', !visible);
                btn.classList.toggle('translate-y-2', !visible);
                btn.classList.toggle('opacity-100', visible);
                btn.classList.toggle('pointer-events-auto', visible);
                btn.classList.toggle('translate-y-0', visible);
            }

            window.addEventListener('scroll', syncBtn, { passive: true });

            btn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }());
    </script>

    <script>
        (function () {
            const loaderElement = document.getElementById('globalPageLoader');
            const rootElement = document.documentElement;
            const bodyElement = document.body;
            let loaderStartedAt = Date.now();
            let loaderFallbackTimer = null;

            const showLoader = function () {
                if (!loaderElement) {
                    return;
                }

                // Business step: show the global loader immediately before a real page change starts.
                loaderStartedAt = Date.now();
                rootElement.classList.add('biogenix-loading');
                bodyElement.classList.add('biogenix-loading');
                loaderElement.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                loaderElement.classList.add('opacity-100', 'visible');
            };

            const hideLoader = function () {
                if (!loaderElement) {
                    return;
                }

                // Business step: keep the loader visible for a very short minimum time so it fades out smoothly instead of flashing.
                const visibleFor = Date.now() - loaderStartedAt;
                const remainingDelay = Math.max(0, 260 - visibleFor);

                window.clearTimeout(loaderFallbackTimer);
                window.setTimeout(function () {
                    rootElement.classList.remove('biogenix-loading');
                    bodyElement.classList.remove('biogenix-loading');
                    loaderElement.classList.remove('opacity-100', 'visible');
                    loaderElement.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                }, remainingDelay);
            };

            const showLoaderForNavigation = function () {
                showLoader();

                // Business step: close the loader automatically if the page does not actually navigate, such as blocked downloads or interrupted submits.
                window.clearTimeout(loaderFallbackTimer);
                loaderFallbackTimer = window.setTimeout(function () {
                    hideLoader();
                }, 10000);
            };

            const isSelfNavigationForm = function (form) {
                if (!(form instanceof HTMLFormElement)) {
                    return false;
                }

                return !form.target || form.target === '_self';
            };

            const canSubmitWithoutBrowserValidationError = function (form) {
                if (!(form instanceof HTMLFormElement)) {
                    return false;
                }

                if (form.noValidate || typeof form.checkValidity !== 'function') {
                    return true;
                }

                return form.checkValidity();
            };

            // Business step: remove the startup loader as soon as the current page is fully ready.
            window.addEventListener('load', hideLoader, { once: true });
            window.addEventListener('pageshow', hideLoader);

            document.addEventListener('click', function (event) {
                if (event.defaultPrevented) {
                    return;
                }

                const link = event.target.closest('a[href]');
                if (!link) {
                    return;
                }

                if (link.hasAttribute('download') || link.target === '_blank') {
                    return;
                }

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                    return;
                }

                const targetUrl = new URL(link.href, window.location.origin);
                if (targetUrl.origin !== window.location.origin) {
                    return;
                }

                // Business step: show the loader for normal same-site navigation links.
                showLoaderForNavigation();
            });

            document.addEventListener('submit', function (event) {
                if (event.defaultPrevented) {
                    return;
                }

                const form = event.target;
                if (!isSelfNavigationForm(form) || !canSubmitWithoutBrowserValidationError(form)) {
                    return;
                }

                // Business step: show the loader only for valid form submits that keep the user in the same browser tab.
                showLoaderForNavigation();
            });

            document.addEventListener('invalid', function () {
                // Business step: close the loader immediately when browser validation stops the submit on the same page.
                hideLoader();
            }, true);

            window.BiogenixPageLoader = {
                show: showLoaderForNavigation,
                hide: hideLoader,
            };
        })();

        function dismissToast(toast) {
            if (!toast) return;
            toast.classList.add('translate-y-2', 'opacity-0');
            window.setTimeout(function () {
                toast.remove();
            }, 300);
        }

        function toggleSupportForm() {
            const form = document.getElementById('supportTicketForm');
            if (!form) return;

            const isOpening = !form.classList.contains('flex');

            if (isOpening) {
                // Initial show using flex class for !important display property
                form.classList.add('flex');

                // Allow a tiny delay for the opacity transition to kick in
                window.requestAnimationFrame(() => {
                    window.requestAnimationFrame(() => {
                        form.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4', 'scale-95');
                        form.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0', 'scale-100');
                    });
                });

                // Business step: Handle clicks outside the form to close it
                const closeOnOutsideClick = (e) => {
                    const toggleBtn = document.querySelector('[onclick="toggleSupportForm()"]');
                    if (!form.contains(e.target) && !toggleBtn.contains(e.target)) {
                        toggleSupportForm();
                        document.removeEventListener('click', closeOnOutsideClick);
                    }
                };

                // Delay adding the listener to avoid the current click event triggering it immediately
                window.setTimeout(() => {
                    document.addEventListener('click', closeOnOutsideClick);
                }, 100);

            } else {
                form.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0', 'scale-100');
                form.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4', 'scale-95');

                window.setTimeout(() => {
                    // Check if it's still intended to be closed
                    if (form.classList.contains('opacity-0')) {
                        form.classList.remove('flex');
                    }
                }, 300);
            }
        }

        /* --- Custom Category Dropdown Logic --- */
        function toggleSupportCategoryList(e) {
            if (e) e.stopPropagation();
            const list = document.getElementById('supportCategoryList');
            const chevron = document.getElementById('supportCategoryChevron');
            if (!list) return;

            const isHidden = list.classList.contains('hidden');
            if (isHidden) {
                list.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                list.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        function selectSupportCategory(slug, label) {
            const input = document.getElementById('supportCategoryInput');
            const text = document.getElementById('selectedCategoryText');
            const trigger = document.getElementById('supportCategoryTrigger');

            if (input) input.value = slug;
            if (text) text.innerText = label;
            if (trigger) {
                trigger.classList.add('border-primary-600', 'ring-1', 'ring-primary-600');
            }

            const list = document.getElementById('supportCategoryList');
            const chevron = document.getElementById('supportCategoryChevron');
            if (list) list.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }

        document.addEventListener('mousedown', function (e) {
            const form = document.getElementById('supportTicketForm');
            const list = document.getElementById('supportCategoryList');
            const chevron = document.getElementById('supportCategoryChevron');

            if (!form || form.classList.contains('hidden')) return;

            // Close custom dropdown when clicking outside of it
            const categoryContainer = document.getElementById('customSupportCategory');
            if (categoryContainer && !categoryContainer.contains(e.target)) {
                if (list && !list.classList.contains('hidden')) {
                    list.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }

            const isToggleBtn = e.target.closest('button[onclick="toggleSupportForm()"]');
            if (isToggleBtn) return;

            if (!form.contains(e.target) && !categoryContainer?.contains(e.target)) {
                // Check if it's already shown
                if (form.classList.contains('flex') && !form.classList.contains('opacity-0')) {
                    toggleSupportForm();
                }
            }
        });

        window.BiogenixToast = {
            show: function (message, type, duration) {
                type = type || 'info';
                duration = duration || 5000;

                var container = document.getElementById('toastContainer');
                if (!container) return;

                var iconMap = {
                    success: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    error: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    warning: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    info: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                };
                var toneMap = {
                    success: ['border-primary-200', 'bg-primary-50/95', 'text-primary-600'],
                    error: ['border-rose-200', 'bg-rose-50/95', 'text-rose-700'],
                    warning: ['border-amber-200', 'bg-secondary-50/95', 'text-secondary-700'],
                    info: ['border-sky-200', 'bg-primary-50/95', 'text-primary-600']
                };

                var toast = document.createElement('div');
                toast.className = 'pointer-events-auto flex items-start gap-3 rounded-2xl border px-4 py-3 shadow-lg backdrop-blur transition duration-300';
                (toneMap[type] || toneMap.info).forEach(function (className) {
                    toast.classList.add(className);
                });

                var icon = document.createElement('div');
                icon.innerHTML = iconMap[type] || iconMap.info;

                var text = document.createElement('p');
                text.className = 'min-w-0 flex-1 text-sm font-medium leading-6';
                text.textContent = message;

                var closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-current/70 transition hover:bg-black/5 hover:text-current';
                closeBtn.setAttribute('aria-label', 'Dismiss notification');
                closeBtn.innerHTML = '&times;';
                closeBtn.addEventListener('click', function () {
                    dismissToast(toast);
                });

                toast.appendChild(icon.firstElementChild || document.createElement('span'));
                toast.appendChild(text);
                toast.appendChild(closeBtn);
                container.appendChild(toast);

                window.setTimeout(function () {
                    dismissToast(toast);
                }, duration);
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            @if ($supportWidgetShouldOpen)
                // This reopens the widget after validation or submit issues so the user can finish the same task.
                toggleSupportForm();
            @endif
            @if (session('success'))
                window.BiogenixToast.show(@json(session('success')), 'success');
            @endif
            @if (session('error'))
                window.BiogenixToast.show(@json(session('error')), 'error');
            @endif
            @if (session('status'))
                window.BiogenixToast.show(@json(session('status')), 'info');
            @endif
        });
    </script>

    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Global Modal Logic
        (function () {
            let modalHideTimers = {};

            window.toggleModal = function (id, show) {
                const modal = document.getElementById(id);
                const content = document.getElementById(id + '-content') || modal?.querySelector('.relative.flex.flex-col');
                if (!modal) return;

                if (show) {
                    window.clearTimeout(modalHideTimers[id]);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                    window.requestAnimationFrame(() => {
                        modal.classList.remove('opacity-0');
                        if (content) {
                            content.classList.remove('scale-85');
                            content.classList.add('scale-90');
                        }
                    });
                } else {
                    modal.classList.add('opacity-0');
                    if (content) {
                        content.classList.remove('scale-90');
                        content.classList.add('scale-85');
                    }

                    modalHideTimers[id] = window.setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        document.body.style.overflow = '';
                    }, 300);
                }
            };

            // Delegated Close Listener
            document.addEventListener('click', function (e) {
                const closeBtn = e.target.closest('[data-modal-close], .modal-close');
                if (closeBtn) {
                    const modalId = closeBtn.getAttribute('data-modal-close') || closeBtn.closest('[role="dialog"]')?.id;
                    if (modalId) {
                        window.toggleModal(modalId, false);
                    }
                }
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
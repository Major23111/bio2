@php
    $currentRoute = request()->route()?->getName();
    $authUser = auth()->user();
    $guestProfileType = request('user_type', request('portal', 'b2c')) === 'b2b' ? 'b2b' : 'b2c';
    $profileHref = $authUser
        ? route('customer.profile.preview', ['user_type' => $authUser->user_type === 'b2b' ? 'b2b' : 'b2c'])
        : route('customer.profile.preview', ['user_type' => $guestProfileType]);
    $accountTypeLabel = $authUser
        ? strtoupper($authUser->user_type === 'b2b' ? 'B2B Account' : 'Customer Account')
        : strtoupper($guestProfileType === 'b2b' ? 'B2B Preview' : 'Customer Preview');
    $navItems = [
        ['label' => 'Home', 'route' => 'home', 'href' => route('home')],
        ['label' => 'Products & Solutions', 'route' => 'products.index', 'href' => route('products.index')],
        ['label' => 'Generate Quotation', 'route' => 'quotation.create', 'href' => route('quotation.create')],
        ['label' => 'Request PI', 'route' => 'pi-quotation.generate', 'href' => route('pi-quotation.generate')],
        ['label' => 'Book Meeting', 'route' => 'book-meeting', 'href' => route('book-meeting')],
        ['label' => 'Meet our Team', 'route' => 'meet-team', 'href' => route('meet-team')],
        ['label' => 'About Us', 'route' => 'about', 'href' => route('about')],
        ['label' => 'Contact Us', 'route' => 'contact', 'href' => route('contact')],
    ];

    // Check if the user is an admin to customize the UI
    $isAdmin = $authUser && (in_array($authUser->user_type, ['admin', 'delegated_admin'], true));
    $brandHref = $isAdmin ? route('admin.dashboard') : route('home');

    // If admin, clear nav items or filter out storefront-heavy links if desired
    // For now, following request to remove the entire menu bar
    if ($isAdmin) {
        $navItems = [];
    }

    $mobileQuickActions = [
        ['label' => 'My Profile', 'href' => $profileHref, 'icon' => 'profile'],
        ['label' => 'View Cart', 'href' => '#', 'icon' => 'cart', 'onclick' => 'openCartSidebar'],
        ['label' => 'Generate Quote', 'href' => route('quotation.create'), 'icon' => 'quote'],
        ['label' => 'Support', 'href' => route('contact'), 'icon' => 'support'],
    ];

    if ($isAdmin) {
        // Remove View Cart from mobile quick actions for admins
        $mobileQuickActions = array_values(array_filter($mobileQuickActions, fn($a) => $a['label'] !== 'View Cart'));
    }
@endphp

<header class="glass-header sticky top-0 z-[100]">
    <style>
        /* Fluid navbar sizing that scales with viewport */
        .header-nav-link {
            padding: clamp(0.35rem, 0.45vw, 0.5rem) clamp(0.4rem, 0.65vw, 0.75rem);
            font-size: clamp(0.68rem, 0.72vw, 0.875rem);
        }

        #headerMainNav {
            gap: clamp(0rem, 0.18vw, 0.25rem);
        }

        @media (min-width: 1280px) and (max-width: 1535px) {
            #headerDesktopActions {
                gap: 0.5rem;
            }

            .header-nav-link {
                padding: 0.4rem 0.4rem;
                font-size: 0.74rem;
            }

            .header-auth-button,
            .header-cart-button,
            .header-profile-button {
                height: 2.5rem;
                min-height: 2.5rem;
            }

            .header-auth-button {
                padding-inline: 1rem;
                font-size: 0.8125rem;
            }

            .header-cart-button {
                gap: 0.45rem;
                padding-inline: 0.8rem;
            }

            .header-cart-label {
                font-size: 0.8125rem;
            }

            .header-profile-button {
                width: 2.5rem;
            }
        }
    </style>
    <div
        class="relative mx-auto flex min-h-[64px] w-full max-w-none items-center gap-4 px-4 py-1 sm:px-6 sm:py-1.5 xl:grid xl:grid-cols-[auto_minmax(0,1fr)_auto] xl:items-center xl:gap-4 xl:px-6 2xl:gap-6 2xl:px-10">
        <a href="{{ $brandHref }}" class="shrink-0 xl:col-start-1">
            <img src="{{ asset('upload/icons/biogenixlogo6.PNG') }}" alt="Biogenix Logo" width="120" height="64"
                decoding="async" class="h-12 w-auto xl:h-14 2xl:h-16">
        </a>

        @unless ($isAdmin)
            {{-- Mobile hamburger --}}
            <button
                class="ml-auto inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[var(--ui-border)] bg-[var(--ui-surface)] text-slate-600 shadow-sm transition hover:bg-[var(--ui-surface-subtle)] xl:hidden"
                data-menu-toggle aria-label="Open navigation menu" aria-expanded="false" aria-controls="mobileMenuDrawer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        @endunless

        {{-- Desktop nav --}}
        <nav id="headerMainNav" class="hidden h-full items-stretch justify-self-center xl:col-start-2 xl:flex xl:min-w-0"
            aria-label="Main Navigation">
            @foreach ($navItems as $nav)
                @if($nav['label'] === 'Products & Solutions')
                    <div class="group flex items-center h-full xl:py-5 2xl:py-6" id="megaMenuWrapper">
                        <a href="{{ $nav['href'] }}"
                            class="header-nav-link relative whitespace-nowrap rounded-lg font-semibold no-underline transition {{ $currentRoute === $nav['route'] ? 'bg-primary-50 text-primary-700 shadow-sm hover:text-primary-700' : 'text-[var(--ui-text-muted)] hover:bg-[var(--ui-surface-subtle)] hover:text-[var(--ui-text)]' }}">
                            {{ $nav['label'] }}
                        </a>
                        
                        <!-- Mega Menu Container -->
                        <div class="fixed left-0 right-0 top-[64px] 2xl:top-[72px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[110]" id="megaMenuDropdown">
                            <div class="mx-auto w-full max-w-[1400px] bg-white shadow-[0_24px_80px_rgba(26,77,46,0.15)] border-t border-slate-100 flex min-h-[450px]">
                                <!-- Left Sidebar (Level 1) -->
                                <div class="w-[220px] bg-white border-r border-slate-100 py-6" id="mm-level1-container">
                                    <button class="mm-level1-btn w-full text-left px-6 py-3 font-bold text-sm text-[var(--ui-text)] hover:bg-slate-50 transition-colors" data-target="solutions">Solutions</button>
                                    <button class="mm-level1-btn w-full text-left px-6 py-3 font-bold text-sm text-[var(--ui-text)] hover:bg-slate-50 transition-colors" data-target="products">Products</button>
                                </div>
                                
                                <!-- Middle Column (Level 2) -->
                                <div class="w-[300px] bg-white border-r border-slate-100 py-6 relative" id="mm-level2-container">
                                    <!-- Populated by JS -->
                                </div>
                                
                                <!-- Right Column (Level 3) -->
                                <div class="flex-1 bg-slate-50" id="mm-level3-container">
                                    <!-- Populated by JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center h-full xl:py-5 2xl:py-6">
                        <a href="{{ $nav['href'] }}"
                            class="header-nav-link relative whitespace-nowrap rounded-lg font-semibold no-underline transition {{ $currentRoute === $nav['route'] ? 'bg-primary-50 text-primary-700 shadow-sm hover:text-primary-700' : 'text-[var(--ui-text-muted)] hover:bg-[var(--ui-surface-subtle)] hover:text-[var(--ui-text)]' }}">
                            {{ $nav['label'] }}
                        </a>
                    </div>
                @endif
            @endforeach
        </nav>

        {{-- Desktop auth & cart --}}
        <div id="headerDesktopActions"
            class="ml-auto hidden items-center gap-2 xl:col-start-3 xl:flex xl:justify-self-end">
            @auth
                <span
                    class="hidden max-w-[12rem] truncate text-sm text-slate-600 2xl:inline-block 2xl:max-w-[14rem]">{{ auth()->user()->name }}
                    ({{ strtoupper(auth()->user()->user_type) }})</span>

                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}"
                        class="header-auth-button inline-flex h-10 items-center justify-center rounded-xl border border-primary-600 bg-primary-600 px-4 text-[13px] font-semibold text-white shadow-sm transition hover:bg-primary-700 2xl:h-11 2xl:px-5 2xl:text-sm">Dashboard</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" id="logoutBtn"
                        class="header-auth-button hover-lift inline-flex h-10 cursor-pointer items-center justify-center rounded-xl border border-[var(--ui-border)] bg-[var(--ui-surface)] px-4 text-[13px] font-semibold text-[var(--ui-text)] shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/20 2xl:h-11 2xl:px-5 2xl:text-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" id="loginBtn"
                    class="header-auth-button inline-flex h-10 items-center justify-center rounded-xl border border-primary-600 bg-primary-600 px-4 text-[13px] font-semibold text-white shadow-sm transition hover:bg-primary-700 2xl:h-11 2xl:px-5 2xl:text-sm">Login</a>
                <a href="{{ route('signup') }}" id="signupBtn"
                    class="header-auth-button inline-flex h-10 items-center justify-center rounded-xl border border-[var(--ui-border)] bg-[var(--ui-surface)] px-4 text-[13px] font-semibold text-[var(--ui-text)] shadow-sm transition hover:bg-[var(--ui-surface-subtle)] hover:border-[var(--ui-text-muted)] 2xl:h-11 2xl:px-5 2xl:text-sm">Sign
                    Up</a>
            @endauth

            @if(!$isAdmin)
                <button type="button" onclick="if(typeof openCartSidebar==='function')openCartSidebar()"
                    class="header-cart-button inline-flex h-10 items-center gap-2 rounded-xl border border-[var(--ui-border)] bg-[var(--ui-surface)] px-3 text-[var(--ui-text)] shadow-sm transition hover:-translate-y-0.5 hover:bg-[var(--ui-surface-subtle)] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/20 cursor-pointer 2xl:h-11 2xl:gap-2.5 2xl:px-3.5"
                    aria-label="View cart">
                    <span class="relative inline-flex h-7 w-7 items-center justify-center text-inherit">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <circle cx="9" cy="21" r="1.4"></circle>
                            <circle cx="17" cy="21" r="1.4"></circle>
                            <path d="M5 6h2l1.4 8h12.1l1.5-6H8"></path>
                        </svg>
                        <span data-cart-count
                            class="absolute -right-2 -top-1 hidden h-5 min-w-5 items-center justify-center rounded-full bg-rose-600 px-1.5 text-xs font-bold leading-none text-white shadow-sm"
                            aria-live="polite" aria-atomic="true">0</span>
                    </span>
                    <span class="header-cart-label text-[13px] font-bold leading-none text-inherit 2xl:text-sm">Cart</span>
                </button>
            @endif

            @unless ($isAdmin)
                {{-- Profile icon --}}
                <a href="{{ $profileHref }}"
                    class="header-profile-button hover-lift inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[var(--ui-border)] bg-[var(--ui-surface)] text-[var(--ui-text)] no-underline shadow-sm transition hover:bg-[var(--ui-surface-subtle)] hover:shadow-md hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/20 2xl:h-11 2xl:w-11"
                    aria-label="{{ $authUser ? 'Open account profile' : 'Open account preview' }}" title="Profile">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M5.5 21a7.5 7.5 0 0113 0"></path>
                    </svg>
                </a>
            @endunless
        </div>
    </div>
</header>

@unless ($isAdmin)
    <div id="mobileMenuOverlay" class="fixed inset-0 z-[70] hidden xl:hidden" aria-hidden="true">
        <button id="mobileMenuBackdrop" type="button"
            class="absolute inset-0 bg-primary-950/45 opacity-0 backdrop-blur-sm transition duration-300"
            aria-label="Close mobile menu"></button>

        <aside id="mobileMenuDrawer"
            class="absolute inset-y-0 right-0 flex w-[min(92vw,25rem)] max-w-full translate-x-full flex-col bg-[var(--ui-surface)] shadow-[0_24px_80px_rgba(26,77,46,0.15)] transition duration-300 ease-out"
            role="dialog" aria-modal="true" aria-labelledby="mobileMenuTitle">
            <div class="flex items-center justify-between border-b border-[var(--ui-border)] px-5 py-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('upload/icons/biogenixlogo6.PNG') }}" alt="Biogenix Logo" width="40" height="40"
                        decoding="async" class="h-10 w-10 rounded-2xl object-cover">
                    <div>
                        <p id="mobileMenuTitle" class="text-base font-semibold tracking-tight text-[var(--ui-text)]">
                            Biogenix Menu</p>
                        <p class="text-xs font-medium text-[var(--ui-text-muted)]">Mobile navigation</p>
                    </div>
                </div>

                <button id="mobileMenuClose" type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-[var(--ui-border)] bg-[var(--ui-surface)] text-[var(--ui-text-muted)] shadow-sm transition hover:bg-[var(--ui-surface-subtle)]"
                    aria-label="Close mobile menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        <div class="flex-1 space-y-4 overflow-y-auto px-4 py-4">
            <section
                class="rounded-[28px] border border-primary-100 bg-[linear-gradient(145deg,#f0faf4_0%,#d1f0dd_100%)] p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <span
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-primary-700 shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M5.5 21a7.5 7.5 0 0113 0"></path>
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-base font-semibold text-[var(--ui-text)]">
                            {{ $authUser?->name ?? 'Welcome to Biogenix' }}
                        </p>
                        <p class="mt-1 truncate text-sm text-[var(--ui-text-muted)]">
                            {{ $authUser?->email ?? 'Browse products, quotes, and support from one place.' }}
                        </p>
                        <span
                            class="mt-3 inline-flex rounded-full bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-primary-700">
                            {{ $accountTypeLabel }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    @auth
                        <a href="{{ $profileHref }}"
                            class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-950 px-4 text-sm font-semibold text-white no-underline transition hover:bg-primary-700 hover:text-white">
                            Open Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="hover-lift inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700 hover:shadow-md">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex h-11 items-center justify-center rounded-2xl bg-primary-600 px-4 text-sm font-semibold text-white no-underline transition hover:bg-primary-700 hover:text-white">
                            Login
                        </a>
                        <a href="{{ route('signup') }}"
                            class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </section>

            <section class="rounded-[26px] border border-[var(--ui-border)] bg-[var(--ui-surface)] p-3 shadow-sm">
                <div class="mb-3 flex items-center justify-between px-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--ui-text-muted)]">Navigate
                    </p>
                    <span class="text-xs font-medium text-[var(--ui-text-muted)]">{{ count($navItems) }} links</span>
                </div>

                <div class="space-y-2">
                    @foreach ($navItems as $nav)
                        <a href="{{ $nav['href'] }}"
                            class="flex items-center justify-between rounded-2xl px-3 py-3 text-sm font-semibold no-underline transition {{ $currentRoute === $nav['route'] ? 'bg-primary-50 text-primary-700 hover:text-primary-700' : 'bg-[var(--ui-surface-subtle)] text-[var(--ui-text)] hover:bg-[var(--ui-border)] hover:text-[var(--ui-text)]' }}">
                            <span>{{ $nav['label'] }}</span>
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="m9 6 6 6-6 6"></path>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[26px] border border-[var(--ui-border)] bg-[var(--ui-surface)] p-3 shadow-sm">
                <div class="mb-3 flex items-center justify-between px-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--ui-text-muted)]">Quick
                        Actions</p>
                    <span class="text-xs font-medium text-[var(--ui-text-muted)]">Fast access</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ($mobileQuickActions as $action)
                        @if (isset($action['onclick']))
                            <button type="button"
                                onclick="if(typeof openCartSidebar==='function'){openCartSidebar();}var cm=document.getElementById('mobileMenuClose');if(cm){cm.click();}"
                                class="rounded-[22px] border border-slate-200 bg-slate-50 p-3 text-left transition hover:border-primary-200 hover:bg-primary-50 hover:text-slate-900 cursor-pointer">
                        @else
                                <a href="{{ $action['href'] }}"
                                    class="rounded-[22px] border border-[var(--ui-border)] bg-[var(--ui-surface-subtle)] p-3 text-left no-underline transition hover:border-primary-200 hover:bg-primary-50 hover:text-[var(--ui-text)]">
                            @endif
                                <span
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-primary-700 shadow-sm">
                                    @if ($action['icon'] === 'profile')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.9">
                                            <circle cx="12" cy="8" r="4"></circle>
                                            <path d="M5.5 21a7.5 7.5 0 0113 0"></path>
                                        </svg>
                                    @elseif ($action['icon'] === 'cart')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.9">
                                            <circle cx="9" cy="20" r="1.4"></circle>
                                            <circle cx="17" cy="20" r="1.4"></circle>
                                            <path d="M5 6h2l1.4 8h12.1l1.5-6H8"></path>
                                        </svg>
                                    @elseif ($action['icon'] === 'quote')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.9">
                                            <path d="M3 5h18"></path>
                                            <path d="M7 3v4"></path>
                                            <path d="M17 3v4"></path>
                                            <rect x="4" y="7" width="16" height="13" rx="2"></rect>
                                            <path d="M8 11h8"></path>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.9">
                                            <path d="M8 10h8"></path>
                                            <path d="M8 14h5"></path>
                                            <path
                                                d="M12 3c4.97 0 9 3.58 9 8 0 1.95-.78 3.74-2.07 5.16L20 21l-5.04-1.68A10.5 10.5 0 0 1 12 20c-4.97 0-9-3.58-9-8s4.03-9 9-9Z">
                                            </path>
                                        </svg>
                                    @endif
                                </span>
                                <span
                                    class="mt-3 block text-sm font-semibold text-[var(--ui-text)]">{{ $action['label'] }}</span>
                                @if (isset($action['onclick']))
                                    </button>
                                @else
                            </a>
                        @endif
                    @endforeach
                </div>
            </section>

            <section
                class="rounded-[26px] border border-[var(--ui-border)] bg-[var(--ui-surface-subtle)] p-4 shadow-sm">
                <p class="text-sm font-semibold text-[var(--ui-text)]">Need procurement help?</p>
                <p class="mt-2 text-sm leading-6 text-[var(--ui-text-muted)]">Talk to our team about availability,
                    delivery windows, or commercial quotations.</p>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="tel:+91180024643649"
                        class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-100 hover:text-slate-900">
                        Call Support
                    </a>
                    <a href="mailto:support@biogenix.local"
                        class="inline-flex h-11 items-center justify-center rounded-2xl bg-primary-600 px-4 text-sm font-semibold text-white no-underline transition hover:bg-primary-700 hover:text-white">
                        Email Support
                    </a>
                </div>
            </section>
        </div>
        </aside>
    </div>
@endunless

<script>
    window.CartStore = (function () {
        const shopperIsLoggedIn = @json(auth()->check());
        const accountCartShowUrl = @json(url('/cart/data'));
        const accountCartStoreUrl = @json(url('/cart/items'));
        const accountCartUpdateUrl = @json(url('/cart/items/__CART_ITEM__'));
        const accountCartDeleteUrl = @json(url('/cart/items/__CART_ITEM__'));
        const guestCartShowUrl = @json(url('/guest-cart/data'));
        const guestCartStoreUrl = @json(url('/guest-cart/items'));
        const guestCartUpdateUrl = @json(url('/guest-cart/items/__CART_ITEM__'));
        const guestCartDeleteUrl = @json(url('/guest-cart/items/__CART_ITEM__'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const listeners = [];
        let currentCartItems = [];
        let cartIsLoaded = false;
        let activeCartRequest = null;

        const normalizeQuantity = function (item) {
            const raw = item && (item.quantity ?? item.qty ?? item.count ?? 1);
            const parsed = Number(raw);
            return Number.isFinite(parsed) && parsed > 0 ? parsed : 1;
        };

        const getItems = function () {
            return currentCartItems.slice();
        };

        const notify = function (items = getItems()) {
            listeners.forEach(function (listener) {
                listener(items);
            });
        };

        const buildCurrentCartItem = function (item) {
            return {
                cartItemId: String(item.id || ''),
                productId: Number(item.product_id || 0),
                variantId: item.product_variant_id == null ? null : Number(item.product_variant_id),
                quantity: normalizeQuantity(item),
                unitPrice: Number(item.unit_price || 0),
                unitTaxAmount: Number(item.unit_tax_amount || 0),
                unitPriceAfterGst: Number(item.unit_price_after_gst || 0),
                taxAmount: Number(item.tax_amount || 0),
                lineSubtotal: Number(item.line_subtotal || 0),
                lineTotal: Number(item.line_total || 0),
                discountAmount: Number(item.discount_amount || 0),
                currency: String(item.currency || 'INR'),
                priceType: item.price_type == null ? null : String(item.price_type),
                minOrderQuantity: Number(item.min_order_quantity || 1),
                maxOrderQuantity: item.max_order_quantity == null ? null : Number(item.max_order_quantity),
                lotSize: Number(item.lot_size || 1),
                name: String(item.product_name || 'Product'),
                model: String(item.sku || ''),
                image: String(item.image_url || ''),
            };
        };

        const applyCurrentCart = function (cart) {
            // Step 1: keep one shared in-memory cart so every storefront screen reads the same backend state.
            currentCartItems = Array.isArray(cart && cart.items) ? cart.items.map(buildCurrentCartItem) : [];
            cartIsLoaded = true;

            // Step 2: notify the active screens so the badge, sidebar, cart, and checkout stay in sync.
            notify(currentCartItems);

            return currentCartItems;
        };

        const buildRequestOptions = function (method, body) {
            // Step 1: send every cart request as JSON to the shared backend cart API.
            const options = {
                method: method,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
            };

            // Step 2: include a JSON body only when the current cart action needs one.
            if (body !== undefined) {
                options.headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(body);
            }

            return options;
        };

        const requestCartEndpoint = async function (url, method, body) {
            // Step 1: call the backend cart endpoint and expect the standard cart controller JSON response.
            const response = await fetch(url, buildRequestOptions(method, body));
            const contentType = String(response.headers.get('content-type') || '');

            // Step 2: treat redirects and non-JSON replies as a login/session issue for the storefront.
            if (response.redirected || !contentType.includes('application/json')) {
                return { ok: false, type: 'auth', message: 'Please login again to continue.' };
            }

            // Step 3: read the payload once so callers can use either the updated cart or backend message.
            const data = await response.json().catch(function () {
                return null;
            });

            // Step 4: return a simple failure shape that every screen can understand.
            if (!response.ok) {
                return {
                    ok: false,
                    type: 'error',
                    message: data && data.message ? data.message : 'Unable to update cart right now.',
                    errors: data && data.errors ? data.errors : {},
                };
            }

            // Step 5: return the successful backend payload for the shared cart store to apply.
            return { ok: true, data: data };
        };

        const currentCartShowUrl = shopperIsLoggedIn ? accountCartShowUrl : guestCartShowUrl;
        const currentCartStoreUrl = shopperIsLoggedIn ? accountCartStoreUrl : guestCartStoreUrl;
        const currentCartUpdateUrl = shopperIsLoggedIn ? accountCartUpdateUrl : guestCartUpdateUrl;
        const currentCartDeleteUrl = shopperIsLoggedIn ? accountCartDeleteUrl : guestCartDeleteUrl;

        const ensureCartLoaded = function () {
            // Step 1: reuse the cart seed prepared by the controller when the current page already has it.
            if (!cartIsLoaded && Object.prototype.hasOwnProperty.call(window, '__BIOGENIX_PAGE_CART__')) {
                applyCurrentCart(window.__BIOGENIX_PAGE_CART__);
                return Promise.resolve({ ok: true, items: getItems() });
            }

            // Step 2: reuse the loaded cart so we do not make duplicate requests on every render.
            if (cartIsLoaded) {
                return Promise.resolve({ ok: true, items: getItems() });
            }

            // Step 3: reuse the in-flight request so multiple screens can wait for the same backend response.
            if (activeCartRequest) {
                return activeCartRequest;
            }

            // Step 4: load the current cart and cache it in memory for pages without a controller seed.
            activeCartRequest = requestCartEndpoint(currentCartShowUrl, 'GET').then(function (result) {
                if (result.ok) {
                    applyCurrentCart(result.data && result.data.cart ? result.data.cart : null);
                }

                return Object.assign({}, result, { items: getItems() });
            }).finally(function () {
                activeCartRequest = null;
            });

            return activeCartRequest;
        };

        const findCurrentCartItem = function (productId, variantId) {
            return currentCartItems.find(function (item) {
                return item.productId === productId && item.variantId === variantId;
            }) || null;
        };

        const getCount = function () {
            return getItems().reduce(function (sum, item) {
                return sum + normalizeQuantity(item);
            }, 0);
        };

        const addItem = function (newItem) {
            // Step 1: save the requested line into the active cart endpoint for the current shopper type.
            return requestCartEndpoint(currentCartStoreUrl, 'POST', {
                product_id: Number(newItem.productId || 0),
                product_variant_id: newItem.variantId == null ? null : Number(newItem.variantId),
                quantity: normalizeQuantity(newItem),
            }).then(function (result) {
                // Step 2: refresh the shared in-memory cart from the backend response after a successful add.
                if (result.ok) {
                    applyCurrentCart(result.data && result.data.cart ? result.data.cart : null);
                }

                return Object.assign({}, result, { items: getItems() });
            });
        };

        const updateQuantity = function (productId, variantId, quantity) {
            // Step 1: make sure the current cart is available before updating one line.
            return ensureCartLoaded().then(function (loadResult) {
                if (!loadResult.ok) {
                    return loadResult;
                }

                // Step 2: find the saved cart row that belongs to the selected product and variant.
                const target = findCurrentCartItem(productId, variantId);
                if (!target) {
                    return { ok: true, items: getItems() };
                }

                // Step 3: send the replacement quantity to the backend so validation stays server-driven.
                return requestCartEndpoint(
                    currentCartUpdateUrl.replace('__CART_ITEM__', String(target.cartItemId)),
                    'PATCH',
                    { quantity: Math.max(1, Number(quantity || 1)) }
                ).then(function (result) {
                    // Step 4: apply the refreshed backend cart so every open cart view stays accurate.
                    if (result.ok) {
                        applyCurrentCart(result.data && result.data.cart ? result.data.cart : null);
                    }

                    return Object.assign({}, result, { items: getItems() });
                });
            });
        };

        const removeItem = function (productId, variantId) {
            // Step 1: make sure the current cart is available before removing one line.
            return ensureCartLoaded().then(function (loadResult) {
                if (!loadResult.ok) {
                    return loadResult;
                }

                // Step 2: find the saved backend cart row that belongs to the selected product and variant.
                const target = findCurrentCartItem(productId, variantId);
                if (!target) {
                    return { ok: true, items: getItems() };
                }

                // Step 3: remove the selected line from the backend cart.
                return requestCartEndpoint(
                    currentCartDeleteUrl.replace('__CART_ITEM__', String(target.cartItemId)),
                    'DELETE'
                ).then(function (result) {
                    // Step 4: apply the refreshed backend cart so every open cart view stays in sync.
                    if (result.ok) {
                        applyCurrentCart(result.data && result.data.cart ? result.data.cart : null);
                    }

                    return Object.assign({}, result, { items: getItems() });
                });
            });
        };

        const clear = function () {
            return { ok: true, items: getItems() };
        };

        return {
            getItems: getItems,
            getCount: getCount,
            addItem: addItem,
            updateQuantity: updateQuantity,
            removeItem: removeItem,
            clear: clear,
            refresh: ensureCartLoaded,
            subscribe: function (listener) {
                // Step 1: register the listener so shared cart UI blocks can react to future cart updates.
                listeners.push(listener);
                listener(getItems());

                // Step 2: load the current cart after subscribe so the page gets the latest backend state.
                ensureCartLoaded();
            },
        };
    }());

    document.addEventListener('DOMContentLoaded', function () {
        // --- Mega Menu Logic ---
        const mmData = {
            solutions: {
                categories: [
                    {
                        id: "sol-hospitalwide",
                        label: "Hospitalwide Solution",
                        href: "/solutions/hospitalwide-solution",
                        subcategories: [
                            { label: "For ER", href: "/solutions/hospitalwide-solution?segment=er" },
                            { label: "For ICU", href: "/solutions/hospitalwide-solution?segment=icu" },
                            { label: "For CCU", href: "/solutions/hospitalwide-solution?segment=ccu" }
                        ]
                    },
                    {
                        id: "sol-emergency",
                        label: "Emergency Care",
                        href: "/solutions/emergency-care",
                        subcategories: [
                            { label: "Response Systems", href: "/solutions/emergency-care?segment=response" },
                            { label: "Care Workflows", href: "/solutions/emergency-care?segment=workflow" }
                        ]
                    },
                    {
                        id: "sol-critical",
                        label: "Critical Care",
                        href: "/solutions/critical-care",
                        subcategories: [
                            { label: "ICU Programs", href: "/solutions/critical-care?segment=icu" },
                            { label: "Monitoring Solutions", href: "/solutions/critical-care?segment=monitoring" }
                        ]
                    },
                    {
                        id: "sol-perioperative",
                        label: "Perioperative Care",
                        href: "/solutions/perioperative-care",
                        subcategories: [
                            { label: "OT Readiness", href: "/solutions/perioperative-care?segment=ot-readiness" },
                            { label: "Procedure Support", href: "/solutions/perioperative-care?segment=procedure-support" }
                        ]
                    },
                    {
                        id: "sol-mis",
                        label: "Minimally Invasive Surgery",
                        href: "/solutions/minimally-invasive-surgery",
                        subcategories: [
                            { label: "Procedure Suites", href: "/solutions/minimally-invasive-surgery?segment=suites" },
                            { label: "Device Portfolio", href: "/solutions/minimally-invasive-surgery?segment=devices" }
                        ]
                    },
                    {
                        id: "sol-lab",
                        label: "Laboratory Diagnostics",
                        href: "/solutions/laboratory-diagnostics",
                        subcategories: [
                            { label: "Small-volume Laboratories", href: "/solutions/laboratory-diagnostics?segment=small" },
                            { label: "Mid-volume Laboratories", href: "/solutions/laboratory-diagnostics?segment=mid" },
                            { label: "High-volume Laboratories", href: "/solutions/laboratory-diagnostics?segment=high" }
                        ]
                    },
                    {
                        id: "sol-cyber",
                        label: "Cybersecurity",
                        href: "/solutions/cybersecurity",
                        subcategories: [
                            { label: "Endpoint Security", href: "/solutions/cybersecurity?segment=endpoint" },
                            { label: "Network Protection", href: "/solutions/cybersecurity?segment=network" }
                        ]
                    }
                ]
            },
            products: {
                categories: [
                    { id: "prod-biochemistry", label: "Biochemistry", href: "/product-categories/biochemistry", subcategories: [] },
                    { id: "prod-blood-culture-bottle", label: "Blood Culture Bottle", href: "/product-categories/blood-culture-bottle", subcategories: [] },
                    { id: "prod-elisa-kits", label: "Elisa Kits", href: "/product-categories/elisa-kits", subcategories: [] },
                    { id: "prod-haematology", label: "Haematology", href: "/product-categories/haematology", subcategories: [] },
                    { id: "prod-instrument", label: "Instrument", href: "/product-categories/instrument", subcategories: [] },
                    { id: "prod-poct", label: "POCT", href: "/product-categories/poct", subcategories: [] },
                    { id: "prod-rapid", label: "Rapid", href: "/product-categories/rapid", subcategories: [] },
                    { id: "prod-serology", label: "Serology", href: "/product-categories/serology", subcategories: [] },
                    { id: "prod-urinalysis", label: "Urinalysis", href: "/product-categories/urinalysis", subcategories: [] },
                    { id: "prod-special-chemistry", label: "Special Chemistry", href: "/product-categories/special-chemistry", subcategories: [] },
                    { id: "prod-clia", label: "CLIA", href: "/product-categories/clia", subcategories: [] },
                    { id: "prod-veterinary", label: "Veterinary", href: "/product-categories/veterinary", subcategories: [] },
                    { id: "prod-molecular", label: "Molecular", href: "/product-categories/molecular", subcategories: [] },
                    { id: "prod-microbiology", label: "Microbiology", href: "/product-categories/microbiology", subcategories: [] },
                    { id: "prod-ivd-instruments", label: "IVD Instruments", href: "/product-categories/ivd-instruments", subcategories: [] }
                ]
            }
        };

        const level1Btns = document.querySelectorAll('.mm-level1-btn');
        const level2Container = document.getElementById('mm-level2-container');
        const level3Container = document.getElementById('mm-level3-container');

        let activeL1 = null;
        let activeL2 = null;

        function renderLevel2(targetId) {
            if (!mmData[targetId]) return;
            activeL1 = targetId;

            // Update level 1 active state
            level1Btns.forEach(btn => {
                if (btn.dataset.target === targetId) {
                    btn.classList.add('bg-primary-600', 'text-white');
                    btn.classList.remove('text-[var(--ui-text)]', 'hover:bg-slate-50');
                } else {
                    btn.classList.remove('bg-primary-600', 'text-white');
                    btn.classList.add('text-[var(--ui-text)]', 'hover:bg-slate-50');
                }
            });

            const categories = mmData[targetId].categories;
            let html = '<div class="flex flex-col w-full px-4 h-full overflow-y-auto">';
            categories.forEach(cat => {
                const hasArrow = cat.subcategories && cat.subcategories.length > 0;
                html += `
                    <button class="mm-level2-btn w-full text-left px-4 py-3 flex items-center justify-between text-[13px] font-semibold text-slate-600 hover:text-primary-700 transition-colors border-b border-transparent hover:border-slate-100" data-id="${cat.id}" data-href="${cat.href || '#'}">
                        <span>${cat.label}</span>
                        ${hasArrow ? `<svg class="w-3 h-3 text-slate-400 group-hover:text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>` : ''}
                    </button>
                `;
            });
            html += '</div>';
            level2Container.innerHTML = html;

            const l2Btns = level2Container.querySelectorAll('.mm-level2-btn');
            l2Btns.forEach(btn => {
                btn.addEventListener('mouseenter', () => {
                    renderLevel3(targetId, btn.dataset.id);
                });
                btn.addEventListener('click', () => {
                    if (btn.dataset.href) {
                        window.location.href = btn.dataset.href;
                    }
                });
            });

            // Auto-select first item
            if (categories.length > 0) {
                renderLevel3(targetId, categories[0].id);
            } else {
                level3Container.innerHTML = '';
            }
        }

        function renderLevel3(l1Id, l2Id) {
            const cat = mmData[l1Id].categories.find(c => c.id === l2Id);
            if (!cat) return;
            activeL2 = l2Id;

            // Update level 2 active state
            const l2Btns = level2Container.querySelectorAll('.mm-level2-btn');
            l2Btns.forEach(btn => {
                const arrow = btn.querySelector('svg');
                if (btn.dataset.id === l2Id) {
                    btn.classList.add('text-primary-700');
                    btn.classList.remove('text-slate-600');
                    if(arrow) {
                        arrow.classList.remove('text-slate-400');
                        arrow.classList.add('text-primary-700');
                    }
                } else {
                    btn.classList.remove('text-primary-700');
                    btn.classList.add('text-slate-600');
                    if(arrow) {
                        arrow.classList.add('text-slate-400');
                        arrow.classList.remove('text-primary-700');
                    }
                }
            });

            let html = '<div class="w-full p-8 flex flex-col gap-2">';
            if (cat.subcategories && cat.subcategories.length > 0) {
                cat.subcategories.forEach(sub => {
                    const href = sub.href || cat.href || '#';
                    const label = sub.label || '';
                    html += `
                        <a href="${href}" class="px-2 py-2 text-[13px] font-medium text-slate-600 hover:text-primary-700 transition-colors border-b border-slate-100 last:border-0">${label}</a>
                    `;
                });
            } else {
                html += `<div class="text-sm text-slate-500">Browse category overview.</div>`;
            }
            html += '</div>';

            level3Container.innerHTML = html;
        }

        level1Btns.forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                renderLevel2(btn.dataset.target);
            });
        });

        // Initialize default
        if(level1Btns.length > 0) {
            renderLevel2('solutions');
        }

        // ─── Cart badge sync ───
        const cartCountBadges = Array.from(document.querySelectorAll('[data-cart-count]'));
        if (window.CartStore && cartCountBadges.length) {
            const syncCartBadge = function (items) {
                const total = items.reduce(function (sum, item) {
                    const raw = item && (item.quantity ?? item.qty ?? item.count ?? 1);
                    const parsed = Number(raw);
                    return sum + (Number.isFinite(parsed) && parsed > 0 ? parsed : 1);
                }, 0);
                cartCountBadges.forEach(function (cartCount) {
                    cartCount.textContent = total > 99 ? '99+' : String(total);
                    cartCount.classList.toggle('hidden', total <= 0);
                    cartCount.classList.toggle('inline-flex', total > 0);
                    cartCount.setAttribute('aria-label', total + ' items in cart');
                });
            };

            window.CartStore.subscribe(syncCartBadge);
            window.addEventListener('pageshow', function () {
                if (window.CartStore && typeof window.CartStore.refresh === 'function') {
                    window.CartStore.refresh();
                }

                syncCartBadge(window.CartStore.getItems());
            });
        }

        // ─── Improved mobile menu toggle ───
        const menuToggle = document.querySelector('[data-menu-toggle]');
        const menuOverlay = document.getElementById('mobileMenuOverlay');
        const menuDrawer = document.getElementById('mobileMenuDrawer');
        const menuBackdrop = document.getElementById('mobileMenuBackdrop');
        const menuClose = document.getElementById('mobileMenuClose');
        if (menuToggle && menuOverlay && menuDrawer && menuBackdrop && menuClose) {
            const mobileQuery = window.matchMedia('(max-width: 1279px)');

            const closeMenu = function () {
                menuBackdrop.classList.remove('opacity-100');
                menuBackdrop.classList.add('opacity-0');
                menuDrawer.classList.remove('translate-x-0');
                menuDrawer.classList.add('translate-x-full');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.documentElement.classList.remove('overflow-hidden');
                document.body.classList.remove('overflow-hidden');
                window.setTimeout(function () {
                    if (!menuDrawer.classList.contains('translate-x-0')) {
                        menuOverlay.classList.add('hidden');
                    }
                }, 300);
            };

            const openMenu = function () {
                if (!mobileQuery.matches) {
                    return;
                }

                menuOverlay.classList.remove('hidden');
                menuToggle.setAttribute('aria-expanded', 'true');
                document.documentElement.classList.add('overflow-hidden');
                document.body.classList.add('overflow-hidden');
                window.requestAnimationFrame(function () {
                    menuBackdrop.classList.remove('opacity-0');
                    menuBackdrop.classList.add('opacity-100');
                    menuDrawer.classList.remove('translate-x-full');
                    menuDrawer.classList.add('translate-x-0');
                });
            };

            menuToggle.addEventListener('click', function () {
                if (menuOverlay.classList.contains('hidden')) {
                    openMenu();
                } else {
                    closeMenu();
                }
            });

            menuBackdrop.addEventListener('click', closeMenu);
            menuClose.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !menuOverlay.classList.contains('hidden')) {
                    closeMenu();
                }
            });

            menuDrawer.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (mobileQuery.matches) {
                        closeMenu();
                    }
                });
            });

            const handleViewportChange = function () {
                if (!mobileQuery.matches) {
                    closeMenu();
                }
            };

            if (typeof mobileQuery.addEventListener === 'function') {
                mobileQuery.addEventListener('change', handleViewportChange);
            } else if (typeof mobileQuery.addListener === 'function') {
                mobileQuery.addListener(handleViewportChange);
            }

            menuBackdrop.classList.add('opacity-0');
            menuDrawer.classList.add('translate-x-full');
        }
    });
</script>

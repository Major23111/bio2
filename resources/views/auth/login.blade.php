@php
    $successMessage = session('success') ?: session('status');
    $errorMessage = session('error') ?: ($errors->first('email') ?: $errors->first('password'));
    $seededAdminEmail = strtolower((string) env('BIOGENIX_ADMIN_EMAIL', 'admin@biogenix.local'));
    $kickerClass = 'inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-primary-700';
    $fieldClass = 'grid gap-2';
    $labelClass = 'text-sm font-semibold text-slate-700';
    $inputBaseClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-900 transition placeholder:text-slate-400 focus:border-primary-600 focus:outline-none focus:ring-4 focus:ring-primary-600/10';
    $inputWithIconClass = $inputBaseClass . ' px-11';
    $linkClass = 'font-semibold text-primary-700 no-underline transition hover:text-primary-600';
    $submitClass = 'inline-flex h-11 w-full items-center justify-center rounded-xl bg-primary-600 text-sm font-semibold text-white shadow-[0_16px_35px_-18px_rgba(26,77,46,0.35)] transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-70';
@endphp

@extends('layouts.app')

@section('title', 'Login - Biogenix')

@section('content')
<div class="mx-auto w-full max-w-4xl py-4 px-4 sm:px-6 md:py-8 lg:px-8">
    <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_28px_70px_-32px_rgba(15,23,42,0.35)]">
        <div class="grid min-h-[30rem] grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(340px,420px)]">
            <aside class="relative hidden overflow-hidden lg:flex lg:items-end lg:p-9">
                <img
                    src="{{ asset('upload/corousel/home2.jpg') }}"
                    alt="Biogenix laboratory"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                    decoding="async"
                >
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(13,43,25,0.24)_0%,rgba(13,43,25,0.72)_46%,#1A4D2E_100%)]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_62%_18%,rgba(255,255,255,0.22),transparent_28%)]"></div>
                <div class="absolute left-7 top-5 h-4 w-72 rounded-full bg-white/75 shadow-lg"></div>
                <div class="absolute left-[3.2rem] top-0 h-6 w-px bg-white/25"></div>
                <div class="absolute left-[18.8rem] top-0 h-6 w-px bg-white/25"></div>

                <div class="relative z-10 max-w-sm">
                    <div class="flex items-center gap-3">
                        <svg class="h-8 w-8 shrink-0 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M7 3h10"></path>
                            <path d="M10 3v3"></path>
                            <path d="M14 3v3"></path>
                            <path d="M7 6h10l-1 2.5v4.5a5 5 0 0 1-4 4.9V21"></path>
                            <path d="M17 6l1 2.5"></path>
                            <path d="M7 6 6 8.5"></path>
                            <path d="M8.5 13h7"></path>
                            <path d="M9 21h6"></path>
                            <path d="M6 21h12"></path>
                        </svg>
                        <p class="text-4xl font-bold tracking-tight text-secondary-600">Biogenix</p>
                    </div>

                    <p class="mt-6 text-lg font-semibold leading-9 text-secondary-600">
                        Enter your Biogenix workspace to access product catalogs, pricing, quotations, and real-time order management in one unified system.
                    </p>
                </div>
            </aside>

            <div class="flex items-center bg-[linear-gradient(180deg,rgba(255,255,255,1)_0%,rgba(252,253,255,1)_100%)] px-5 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-sm">
                    <div>
                        <p class="{{ $kickerClass }}">Secure Access</p>
                        <h1 class="mt-2 text-2xl font-bold leading-tight tracking-tight text-slate-950 md:text-3xl">Sign in to your account</h1>
                        <p class="mt-2 text-xs leading-6 text-slate-500 md:text-sm">
                            Access your Biogenix dashboard to explore products, generate quotations, track orders, and manage procurement workflows seamlessly.
                        </p>
                    </div>

                    {{-- AJAX error alert (hidden by default) --}}
                    <div id="loginAjaxError" class="mt-5 hidden flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium leading-6 text-rose-700" role="alert">
                        <svg class="mt-0.5 h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 8v5"></path>
                            <path d="M12 16h.01"></path>
                        </svg>
                        <span id="loginAjaxErrorText"></span>
                    </div>

                    @if ($errorMessage)
                        <div class="mt-8 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm font-medium leading-6 text-rose-700" role="alert">
                            <svg class="mt-0.5 h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 8v5"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                            <span>{{ $errorMessage }}</span>
                        </div>
                    @elseif ($successMessage)
                        <div class="mt-8 rounded-2xl border border-primary-200 bg-primary-50 px-5 py-5" role="status">
                            <div class="flex items-center gap-3">
                                <svg class="h-6 w-6 shrink-0 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-base font-bold text-primary-700">Registration Submitted Successfully!</p>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-primary-600">Your registration has been received and is being reviewed by our team. Once approved, you'll unlock access to product catalogs, quotations, and a streamlined procurement experience. We'll notify you shortly with your account status.</p>
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="mt-5 grid gap-4" novalidate>
                        @csrf

                        <div class="{{ $fieldClass }}" data-field-group>
                            <label for="loginEmail" class="{{ $labelClass }}">Email Address</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input
                                    type="email"
                                    name="email"
                                    id="loginEmail"
                                    class="{{ $inputWithIconClass }} @error('email') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                                    placeholder="researcher@biogenix.com"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                >
                            </div>
                            @error('email')
                                <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                            @else
                                <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                            @enderror
                        </div>

                        <div class="{{ $fieldClass }}" data-field-group>
                            <div class="flex items-center justify-between gap-3">
                                <label for="loginPassword" class="{{ $labelClass }}">Password</label>
                                <a href="{{ route('forgot.password') }}" class="{{ $linkClass }} text-sm">Forgot Password?</a>
                            </div>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                    <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                                </svg>
                                <input
                                    type="password"
                                    name="password"
                                    id="loginPassword"
                                    class="{{ $inputWithIconClass }} pr-12 @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                                    placeholder="Enter your password"
                                    autocomplete="current-password"
                                    required
                                >
                                <button type="button" id="togglePassword" class="absolute right-3 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Show password" aria-pressed="false">
                                    <svg data-password-hidden-icon class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg data-password-visible-icon class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 3 18 18"></path>
                                        <path d="M10.6 10.7a3 3 0 0 0 4.2 4.2"></path>
                                        <path d="M9.9 5.2A10.5 10.5 0 0 1 12 5c6 0 9.5 7 9.5 7a18.8 18.8 0 0 1-3.2 3.9"></path>
                                        <path d="M6.2 6.2C3.9 7.9 2.5 12 2.5 12s3.5 7 9.5 7c1.7 0 3.2-.4 4.6-1.1"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                            @else
                                <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                            @enderror
                        </div>

                        <label for="rememberCheck" class="inline-flex items-center gap-3 text-sm text-slate-600">
                            <input
                                type="checkbox"
                                name="remember"
                                id="rememberCheck"
                                value="1"
                                class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600"
                                @checked(old('remember'))
                            >
                            <span>Keep me signed in</span>
                        </label>

                        <button type="submit" id="loginSubmitBtn" class="{{ $submitClass }}">
                            Sign In
                        </button>
                    </form>

                    <div class="mt-8 border-t border-slate-200 pt-5">
                        <p class="text-center text-sm leading-7 text-slate-500">
                            Need assistance?
                            <a href="{{ route('contact') }}" class="{{ $linkClass }}">Contact Support</a>
                        </p>

                        <p class="mt-3 text-center text-sm leading-7 text-slate-500">
                            New here?
                            <a href="{{ route('signup') }}" class="{{ $linkClass }}">Create an account</a>
                        </p>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            <span>&copy; 2026 Biogenix Corp.</span>
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('privacy') }}" class="text-inherit no-underline">Privacy</a>
                                <a href="{{ route('terms') }}" class="text-inherit no-underline">Terms</a>
                                <a href="{{ route('contact') }}" class="text-inherit no-underline">Security</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof setupPasswordToggle === 'function') {
            setupPasswordToggle('loginPassword', 'togglePassword');
        }

        const loginForm = document.getElementById('loginForm');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');
        const ajaxErrorBox = document.getElementById('loginAjaxError');
        const ajaxErrorText = document.getElementById('loginAjaxErrorText');
        const seededAdminEmail = @json($seededAdminEmail);
        const adminDashboardUrl = @json(route('admin.dashboard'));
        const storefrontHomeUrl = @json(url('/'));

        function showAjaxError(msg) {
            ajaxErrorText.textContent = msg;
            ajaxErrorBox.classList.remove('hidden');
            ajaxErrorBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function hideAjaxError() {
            ajaxErrorBox.classList.add('hidden');
            ajaxErrorText.textContent = '';
        }

        if (loginForm && loginSubmitBtn) {
            loginForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                hideAjaxError();

                // Basic client-side check
                const email = document.getElementById('loginEmail').value.trim();
                const password = document.getElementById('loginPassword').value;
                if (!email || !password) {
                    showAjaxError('Please enter both email and password.');
                    return;
                }

                loginSubmitBtn.disabled = true;
                loginSubmitBtn.classList.add('cursor-not-allowed', 'opacity-70');
                const originalText = loginSubmitBtn.innerHTML;
                loginSubmitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg> Signing in...';

                try {
                    const formData = new FormData(loginForm);
                    const normalizedEmail = email.toLowerCase();
                    const response = await fetch(loginForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (response.ok || response.redirected) {
                        // Success — reload to follow redirect
                        const targetUrl = normalizedEmail === seededAdminEmail
                            ? adminDashboardUrl
                            : (response.redirected && response.url ? response.url : storefrontHomeUrl);

                        window.location.href = targetUrl;
                        return;
                    }

                    const data = await response.json().catch(() => null);

                    if (response.status === 422 && data && data.errors) {
                        const firstError = Object.values(data.errors).flat()[0];
                        showAjaxError(firstError || 'Invalid credentials. Please try again.');
                    } else if (data && data.message) {
                        showAjaxError(data.message);
                    } else {
                        showAjaxError('These credentials do not match our records.');
                    }
                } catch (err) {
                    showAjaxError('Something went wrong. Please check your connection and try again.');
                } finally {
                    loginSubmitBtn.disabled = false;
                    loginSubmitBtn.classList.remove('cursor-not-allowed', 'opacity-70');
                    loginSubmitBtn.innerHTML = originalText;
                }
            });
        }
    });
</script>
@endpush

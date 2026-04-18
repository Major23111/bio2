<footer id="siteFooter" class="relative mt-10 overflow-hidden border-t border-primary-700/40 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900 text-white transition-[padding] duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-secondary-600/45 to-transparent"></div>
    <div class="pointer-events-none absolute -left-24 top-8 h-56 w-56 rounded-full bg-primary-500/20 blur-3xl"></div>
    <div class="pointer-events-none absolute right-0 top-0 h-72 w-72 rounded-full bg-secondary-600/10 blur-3xl"></div>

    <div class="relative mx-auto w-full max-w-none px-4 py-8 sm:px-6 lg:px-8 xl:px-10">
        <div class="grid gap-4 lg:grid-cols-12">
            {{-- Integrated Branding & Contact --}}
            <section class="rounded-[30px] border border-white/10 bg-white/10 p-5 shadow-[0_24px_60px_rgba(5,16,9,0.3)] backdrop-blur sm:p-6 lg:col-span-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/15 p-2 shadow-lg shadow-primary-950/25">
                        <img src="{{ asset('upload/icons/logo.jpg') }}" alt="Biogenix Logo" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-secondary-600/85">BIOGENIX WORKSPACE</p>
                        <h3 class="mt-1 text-xl font-semibold tracking-tight text-white leading-tight">Precision Infrastructure for <br class="hidden sm:block"> Modern Diagnostics</h3>
                    </div>
                </div>

                <p class="mt-4 max-w-2xl text-sm leading-6 text-primary-100/75">
                    Biogenix powers laboratories, hospitals, and distribution networks with integrated access to diagnostics, instruments, and procurement intelligence—designed for speed, reliability, and scale.
                </p>

                <div class="mt-6 grid gap-3 sm:grid-cols-1">
                    <div class="rounded-2xl border border-white/10 bg-primary-950/35 p-3.5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-secondary-600/85">Direct Support Channels</p>
                        <div class="mt-2 flex flex-col gap-2">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <span class="text-xs font-medium text-primary-300">Email:</span>
                                <a href="mailto:support@biogenix.in" class="text-sm font-semibold text-white no-underline transition hover:text-secondary-600">support@biogenix.in</a>
                                <span class="text-white/20">|</span>
                                <a href="mailto:info@biogenix.in" class="text-sm font-semibold text-white no-underline transition hover:text-secondary-600">info@biogenix.in</a>
                                <span class="text-white/20">|</span>
                                <a href="mailto:biogenix2007@yahoo.com" class="text-sm font-semibold text-white no-underline transition hover:text-secondary-600">biogenix2007@yahoo.com</a>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <span class="text-xs font-medium text-primary-300">Call:</span>
                                <a href="tel:+919140971443" class="text-sm font-semibold text-white no-underline transition hover:text-secondary-600">+91-9140971443</a>
                                <span class="text-white/20">|</span>
                                <a href="tel:+919889485222" class="text-sm font-semibold text-white no-underline transition hover:text-secondary-600">+91-9889485222</a>
                                <span class="text-white/20">|</span>
                                <a href="tel:+919616105666" class="text-sm font-semibold text-white no-underline transition hover:text-secondary-600">+91-9616105666</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 rounded-2xl border border-white/10 bg-primary-950/35 p-3.5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-secondary-600/85">Headquarters</p>
                    <p class="mt-1.5 text-sm leading-6 text-primary-100/75 px-1">
                        B19/A, S.I.L Ancillary Estate Amausi Industrial Area, Nadarganj, Lucknow-226008, Uttar Pradesh, India
                    </p>
                </div>
            </section>

            {{-- Platform Navigation --}}
            <section class="rounded-[30px] border border-white/10 bg-white/10 p-5 shadow-[0_24px_60px_rgba(5,16,9,0.3)] backdrop-blur lg:col-span-2">
                <h4 class="text-base font-semibold text-secondary-600/85 whitespace-nowrap">Platform Navigation</h4>
                <ul class="mt-4 space-y-2.5">
                    <li><a href="{{ route('home') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Home</a></li>
                    <li><a href="{{ route('quotation.create') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Generate Quote</a></li>
                    <li><a href="{{ route('pi-quotation.generate') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Generate Proforma Invoice</a></li>
                    <li><a href="{{ route('book-meeting') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Book a Meeting</a></li>
                    <li><a href="{{ route('about') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Contact Us</a></li>
                </ul>
            </section>

            {{-- Compliance & Policies --}}
            <section class="rounded-[30px] border border-white/10 bg-white/10 p-5 shadow-[0_24px_60px_rgba(5,16,9,0.3)] backdrop-blur lg:col-span-2">
                <h4 class="text-base font-semibold text-secondary-600/85 whitespace-nowrap">Compliance & Policies</h4>
                <ul class="mt-4 space-y-2.5">
                    <li><a href="{{ route('privacy') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Terms of Use</a></li>
                    <li><a href="{{ route('refund-policy') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">Refund & Cancellation</a></li>
                    <li><a href="{{ route('faq') }}" class="text-sm font-medium text-primary-100/70 no-underline transition hover:text-secondary-600">FAQs</a></li>
                </ul>
            </section>

            {{-- Direct Access Channels --}}
            <section class="rounded-[30px] border border-white/10 bg-white/10 p-5 shadow-[0_24px_60px_rgba(5,16,9,0.3)] backdrop-blur lg:col-span-3">
                <h4 class="text-base font-semibold text-secondary-600/85">Direct Access Channels</h4>
                <p class="mt-3 text-sm leading-6 text-primary-100/70">
                    Connect with the Biogenix team across priority channels for procurement, support, and operational coordination.
                </p>

                <div class="mt-4 flex flex-wrap gap-2.5">
                    <a href="mailto:support@biogenix.in" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-primary-100/80 transition hover:-translate-y-0.5 hover:border-secondary-600/35 hover:bg-secondary-600/10 hover:text-secondary-600" aria-label="Email support">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m4 7 8 6 8-6"></path></svg>
                    </a>
                    <a href="tel:+919140971443" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-primary-100/80 transition hover:-translate-y-0.5 hover:border-secondary-600/35 hover:bg-secondary-600/10 hover:text-secondary-600" aria-label="Call support">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.35 1.78.68 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.47-1.25a2 2 0 0 1 2.11-.45c.83.33 1.71.56 2.61.68A2 2 0 0 1 22 16.92z"></path></svg>
                    </a>
                    <a href="https://wa.me/919140971443" target="_blank" rel="noopener noreferrer" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-primary-100/80 transition hover:-translate-y-0.5 hover:border-secondary-600/35 hover:bg-secondary-600/10 hover:text-secondary-600" aria-label="WhatsApp support">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>

                <div class="mt-4 space-y-2">
                    <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-secondary-600/85">Support Desk</p>
                        <p class="mt-1.5 text-sm font-medium leading-[1.65] text-primary-100/75">Access priority assistance for order tracking, account setup, product queries, and post-order coordination.</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="mt-8 flex flex-col gap-4 border-t border-white/10 pt-6">
            <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                <p class="text-sm font-medium tracking-wide text-primary-100/60 transition-colors hover:text-secondary-600">
                    Serving diagnostic labs, hospitals, and distributors across India
                </p>
                <p class="text-sm text-secondary-600/85">
                    &copy; 2026 Biogenix Inc Private Limited - All Rights Reserved
                </p>
            </div>
        </div>
    </div>
</footer>

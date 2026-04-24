@extends('layouts.app')

@section('title', 'Terms of Use')

@section('content')
<div class="min-h-screen bg-slate-50 pb-24">
    <section class="border-b border-slate-200 bg-white py-12 md:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Terms of Use</h1>
            <p class="mt-3 flex items-center gap-2 text-sm text-primary-600">Last Updated: 22/04/2026</p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <p class="text-base leading-8 text-slate-700">
                Welcome to Biogenix Inc Pvt Ltd ("Company", "we", "our", "us"). These Terms of Use ("Terms") govern your access to and use of our website and the services provided through it.
                By accessing or using our website, you agree to be bound by these Terms. If you do not agree, please do not use our services.
            </p>
        </div>

        <div class="space-y-4">
            @php
                $sections = [
                    ['num' => 1, 'title' => 'Eligibility', 'content' => '<p class="leading-relaxed text-slate-600">By using this website, you confirm that you are at least 18 years of age, legally capable of entering into binding agreements, and will use the website in accordance with these Terms and applicable laws.</p>'],
                    ['num' => 2, 'title' => 'User Responsibilities', 'content' => '<p class="leading-relaxed text-slate-600">When using our website, you agree to provide accurate, current, and complete information; not use the website for unlawful or fraudulent activity; not attempt unauthorized access; not interfere with website functionality; and not misuse or copy website content without permission. You are responsible for keeping your account credentials confidential.</p>'],
                    ['num' => 3, 'title' => 'Services', 'content' => '<p class="leading-relaxed text-slate-600">We provide products/services through our website as described on respective pages. We reserve the right to modify, suspend, or discontinue any service without prior notice. Prices and availability may change at any time. We do not guarantee that all services will always be available or error-free.</p>'],
                    ['num' => 4, 'title' => 'Payments & Billing', 'content' => '<p class="leading-relaxed text-slate-600">All payments are processed through secure third-party payment gateways such as Razorpay. You agree to provide valid and accurate payment information. We do not store card or banking details. Payment authorization is subject to approval by your bank or payment provider. Failed transactions are refunded as per gateway policies.</p>'],
                    ['num' => 5, 'title' => 'Refunds & Cancellations', 'content' => '<p class="leading-relaxed text-slate-600">Refunds and cancellations are governed by our Refund & Cancellation Policy available on our website. By making a purchase, you agree to that policy.</p>'],
                    ['num' => 6, 'title' => 'Intellectual Property Rights', 'content' => '<p class="leading-relaxed text-slate-600">All content on this website including text, graphics, logos, images, and software is the property of Biogenix Inc Pvt Ltd and protected under applicable intellectual property laws. You may not copy, reproduce, distribute, or exploit any content without prior written permission.</p>'],
                    ['num' => 7, 'title' => 'Prohibited Activities', 'content' => '<p class="leading-relaxed text-slate-600">You agree not to use the website for illegal purposes, upload harmful code, attempt to hack/disrupt/damage the website, engage in data mining or scraping, or violate any applicable laws or regulations. Violations may result in termination of access.</p>'],
                    ['num' => 8, 'title' => 'Third-Party Services & Links', 'content' => '<p class="leading-relaxed text-slate-600">Our website may include links or integrations with third-party services. We are not responsible for their content, policies, or practices. Use of such services is at your own risk.</p>'],
                    ['num' => 9, 'title' => 'Disclaimer of Warranties', 'content' => '<p class="leading-relaxed text-slate-600">Our website and services are provided on an "as is" and "as available" basis. We do not guarantee uninterrupted or error-free availability, accuracy or reliability of content, or that defects will be corrected. Your use of the website is at your sole risk.</p>'],
                    ['num' => 10, 'title' => 'Limitation of Liability', 'content' => '<p class="leading-relaxed text-slate-600">To the maximum extent permitted by law, Biogenix Inc Pvt Ltd shall not be liable for indirect, incidental, or consequential damages; loss of data, revenue, or profits; or damages arising from use or inability to use our services.</p>'],
                    ['num' => 11, 'title' => 'Indemnification', 'content' => '<p class="leading-relaxed text-slate-600">You agree to indemnify and hold harmless Biogenix Inc Pvt Ltd, its employees, and affiliates from claims, damages, or expenses arising from your use of the website, violation of these Terms, or infringement of third-party rights.</p>'],
                    ['num' => 12, 'title' => 'Termination', 'content' => '<p class="leading-relaxed text-slate-600">We reserve the right to suspend or terminate your access to the website at any time and remove any content or data if you violate these Terms or engage in harmful activities.</p>'],
                    ['num' => 13, 'title' => 'Governing Law', 'content' => '<p class="leading-relaxed text-slate-600">These Terms are governed by the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts in Lucknow, Uttar Pradesh.</p>'],
                    ['num' => 14, 'title' => 'Changes to Terms', 'content' => '<p class="leading-relaxed text-slate-600">We may update these Terms at any time without prior notice. Updated Terms will be posted on this page. Continued use of the website implies acceptance of revised Terms.</p>'],
                    ['num' => 15, 'title' => 'Contact Information', 'content' => '<p class="leading-relaxed text-slate-600">If you have any questions regarding these Terms, please contact us:</p><p class="mt-3 leading-relaxed text-slate-600">Company Name: Biogenix Inc Pvt Ltd<br>Email:<br>Phone:<br>Address:</p>'],
                ];
            @endphp

            @foreach ($sections as $section)
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300">
                    <button
                        type="button"
                        class="flex w-full items-center gap-4 px-6 py-5 text-left transition hover:bg-slate-50"
                        onclick="toggleTermsSection({{ $section['num'] }})"
                        aria-expanded="false"
                        id="terms-btn-{{ $section['num'] }}"
                    >
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-sm font-bold text-primary-700">{{ $section['num'] }}</span>
                        <span class="flex-1 text-base font-semibold text-slate-900">{{ $section['title'] }}</span>
                        <svg class="terms-chevron h-5 w-5 shrink-0 text-slate-400 transition-transform duration-300" id="terms-chevron-{{ $section['num'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="terms-panel hidden" id="terms-panel-{{ $section['num'] }}">
                        <div class="border-t border-slate-100 px-6 pb-6 pt-4 pl-[4.5rem]">
                            {!! $section['content'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        toggleTermsSection(1);
    });

    function toggleTermsSection(num) {
        var panel = document.getElementById('terms-panel-' + num);
        var chevron = document.getElementById('terms-chevron-' + num);
        var btn = document.getElementById('terms-btn-' + num);
        if (!panel) return;

        var isOpen = !panel.classList.contains('hidden');

        document.querySelectorAll('.terms-panel').forEach(function(p) { p.classList.add('hidden'); });
        document.querySelectorAll('.terms-chevron').forEach(function(c) { c.classList.remove('rotate-180'); });
        document.querySelectorAll('[id^="terms-btn-"]').forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });

        if (!isOpen) {
            panel.classList.remove('hidden');
            chevron.classList.add('rotate-180');
            btn.setAttribute('aria-expanded', 'true');
        }
    }
</script>
@endpush
@endsection

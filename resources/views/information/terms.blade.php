@extends('layouts.app')

@section('title', 'Terms of Use')

@section('content')
<div class="min-h-screen bg-slate-50 pb-24">
    <section class="border-b border-slate-200 bg-white py-12 md:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Terms of Use</h1>
            <p class="mt-3 flex items-center gap-2 text-sm text-primary-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Last Updated: 22/04/2026
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <p class="text-base leading-8 text-slate-700">Welcome to Biogenix Inc Pvt Ltd ("Company", "we", "our", "us"). These Terms of Use ("Terms") govern your access to and use of our website [Your Website URL] and the services provided through it.</p>
            <p class="mt-3 text-base leading-8 text-slate-700">By accessing or using our website, you agree to be bound by these Terms. If you do not agree, please do not use our services.</p>
        </div>

        <div class="space-y-4">
            @php
                $sections = [
                    ['num' => 1, 'title' => 'Eligibility', 'content' => '<p class="leading-relaxed text-slate-600">By using this website, you confirm that:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>You are at least 18 years of age</li><li>You are legally capable of entering into binding agreements</li><li>You will use the website in accordance with these Terms and applicable laws</li></ul>'],
                    ['num' => 2, 'title' => 'User Responsibilities', 'content' => '<p class="leading-relaxed text-slate-600">When using our website, you agree that you will:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Provide accurate, current, and complete information</li><li>Not use the website for any unlawful or fraudulent activity</li><li>Not attempt to gain unauthorized access to our systems</li><li>Not interfere with the proper functioning of the website</li><li>Not misuse or copy website content without permission</li></ul><p class="mt-3 leading-relaxed text-slate-600">You are responsible for maintaining the confidentiality of your account credentials.</p>'],
                    ['num' => 3, 'title' => 'Services', 'content' => '<p class="leading-relaxed text-slate-600">We provide products/services through our website as described on respective pages.</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>We reserve the right to modify, suspend, or discontinue any service without prior notice</li><li>Prices and availability are subject to change at any time</li><li>We do not guarantee that all services will always be available or error-free</li></ul>'],
                    ['num' => 4, 'title' => 'Payments & Billing', 'content' => '<p class="leading-relaxed text-slate-600">All payments made on our website are processed through secure third-party payment gateways such as Razorpay.</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>You agree to provide valid and accurate payment information</li><li>We do not store your card or banking details</li><li>Payment authorization is subject to approval by your bank or payment provider</li></ul><p class="mt-3 leading-relaxed text-slate-600">In case of failed transactions, the amount will be refunded as per the payment gateway policies.</p>'],
                    ['num' => 5, 'title' => 'Refunds & Cancellations', 'content' => '<p class="leading-relaxed text-slate-600">Refunds and cancellations are governed by our Refund &amp; Cancellation Policy, which is available on our website.</p><p class="mt-3 leading-relaxed text-slate-600">By making a purchase, you agree to that policy.</p>'],
                    ['num' => 6, 'title' => 'Intellectual Property Rights', 'content' => '<p class="leading-relaxed text-slate-600">All content on this website, including but not limited to:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Text</li><li>Graphics</li><li>Logos</li><li>Images</li><li>Software</li></ul><p class="mt-3 leading-relaxed text-slate-600">is the property of Biogenix Inc Pvt Ltd and is protected under applicable intellectual property laws.</p><p class="mt-3 leading-relaxed text-slate-600">You may not copy, reproduce, distribute, or exploit any content without prior written permission.</p>'],
                    ['num' => 7, 'title' => 'Prohibited Activities', 'content' => '<p class="leading-relaxed text-slate-600">You agree not to:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Use the website for illegal purposes</li><li>Upload or transmit harmful code (viruses, malware)</li><li>Attempt to hack, disrupt, or damage the website</li><li>Engage in data mining or scraping</li><li>Violate any applicable laws or regulations</li></ul><p class="mt-3 leading-relaxed text-slate-600">Violation of these terms may result in termination of your access.</p>'],
                    ['num' => 8, 'title' => 'Third-Party Services & Links', 'content' => '<p class="leading-relaxed text-slate-600">Our website may include links or integrations with third-party services.</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>We are not responsible for the content, policies, or practices of third-party websites</li><li>Use of such services is at your own risk</li></ul>'],
                    ['num' => 9, 'title' => 'Disclaimer of Warranties', 'content' => '<p class="leading-relaxed text-slate-600">Our website and services are provided on an "as is" and "as available" basis.</p><p class="mt-3 leading-relaxed text-slate-600">We do not guarantee:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>That the website will be uninterrupted or error-free</li><li>Accuracy or reliability of content</li><li>That defects will be corrected</li></ul><p class="mt-3 leading-relaxed text-slate-600">Your use of the website is at your sole risk.</p>'],
                    ['num' => 10, 'title' => 'Limitation of Liability', 'content' => '<p class="leading-relaxed text-slate-600">To the maximum extent permitted by law, Biogenix Inc Pvt Ltd shall not be liable for:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Any indirect, incidental, or consequential damages</li><li>Loss of data, revenue, or profits</li><li>Any damages arising from use or inability to use our services</li></ul>'],
                    ['num' => 11, 'title' => 'Indemnification', 'content' => '<p class="leading-relaxed text-slate-600">You agree to indemnify and hold harmless Biogenix Inc Pvt Ltd, its employees, and affiliates from any claims, damages, or expenses arising from:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Your use of the website</li><li>Violation of these Terms</li><li>Infringement of any rights of a third party</li></ul>'],
                    ['num' => 12, 'title' => 'Termination', 'content' => '<p class="leading-relaxed text-slate-600">We reserve the right to:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Suspend or terminate your access to the website at any time</li><li>Remove any content or data</li></ul><p class="mt-3 leading-relaxed text-slate-600">If you violate these Terms or engage in harmful activities.</p>'],
                    ['num' => 13, 'title' => 'Governing Law', 'content' => '<p class="leading-relaxed text-slate-600">These Terms shall be governed by and interpreted in accordance with the laws of India.</p><p class="mt-3 leading-relaxed text-slate-600">Any disputes shall be subject to the exclusive jurisdiction of the courts in Lucknow, Uttar Pradesh.</p>'],
                    ['num' => 14, 'title' => 'Changes to Terms', 'content' => '<p class="leading-relaxed text-slate-600">We may update these Terms at any time without prior notice.</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Updated Terms will be posted on this page</li><li>Continued use of the website implies acceptance of the revised Terms</li></ul>'],
                    ['num' => 15, 'title' => 'Contact Information', 'content' => '<p class="leading-relaxed text-slate-600">If you have any questions regarding these Terms, please contact us:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Company Name: Biogenix Inc Pvt Ltd</li><li>Email:</li><li>Phone:</li><li>Address:</li></ul>'],
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

    <div class="border-t border-slate-200 bg-white py-6">
        <div class="mx-auto flex max-w-4xl flex-wrap items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="relative h-9 w-9 rounded-xl bg-primary-600">
                    <span class="absolute left-2 top-2 h-2 w-2 rounded-sm bg-white"></span>
                    <span class="absolute bottom-2 right-2 h-2 w-2 rounded-sm bg-white"></span>
                </div>
                <span class="text-sm font-semibold text-slate-900">Biogenix Labs</span>
            </div>
            <p class="text-sm text-slate-500">&copy; 2023 Biogenix International. All rights reserved.</p>
            <nav class="flex flex-wrap gap-6">
                <a href="{{ route('privacy') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Privacy Policy</a>
                <a href="{{ route('privacy') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Cookie Policy</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Compliance</a>
            </nav>
        </div>
    </div>
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

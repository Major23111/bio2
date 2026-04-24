@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
{{-- Privacy Policy accordion layout --}}
<div class="min-h-screen bg-slate-50 pb-24">
    <section class="border-b border-slate-200 bg-white py-12 md:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Privacy Policy</h1>
            <p class="mt-3 flex items-center gap-2 text-sm text-primary-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Last Updated: 22/04/2026
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <p class="text-base leading-8 text-slate-700">
                Welcome to Biogenix Inc Pvt Ltd ("Company", "we", "our", "us"). We are committed to protecting your privacy and ensuring that your personal information is handled in a safe and responsible manner.
            </p>
            <p class="mt-3 text-base leading-8 text-slate-700">
                This Privacy Policy describes how we collect, use, disclose, and safeguard your information when you visit our website biogenix.in and use our services.
            </p>
        </div>

        <div class="space-y-4">
            @php
                $privacySections = [
                    [
                        'num' => 1,
                        'title' => 'Information We Collect',
                        'content' => '<p class="leading-relaxed text-slate-600">We may collect the following types of information:</p><p class="mt-4 font-semibold text-slate-700">a) Personal Information</p><p class="mt-2 leading-relaxed text-slate-600">When you interact with our website, we may collect:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Full Name</li><li>Email Address</li><li>Phone Number</li><li>Billing and Shipping Address</li><li>Any other information you voluntarily provide</li></ul><p class="mt-4 font-semibold text-slate-700">b) Payment Information</p><p class="mt-2 leading-relaxed text-slate-600">We do not store your payment details such as card numbers, CVV, or banking credentials. All payment transactions are securely processed through our payment gateway partner (Razorpay).</p><p class="mt-4 font-semibold text-slate-700">c) Technical &amp; Usage Data</p><p class="mt-2 leading-relaxed text-slate-600">We may automatically collect:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>IP Address</li><li>Browser type and version</li><li>Device information</li><li>Pages visited and time spent</li><li>Cookies and tracking technologies</li></ul>'
                    ],
                    [
                        'num' => 2,
                        'title' => 'How We Use Your Information',
                        'content' => '<p class="leading-relaxed text-slate-600">We use your information for the following purposes:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>To process transactions and provide services</li><li>To communicate with you (order updates, support, notifications)</li><li>To improve our website, services, and user experience</li><li>To prevent fraud and ensure security</li><li>To comply with legal obligations</li></ul>'
                    ],
                    [
                        'num' => 3,
                        'title' => 'Payment Processing',
                        'content' => '<p class="leading-relaxed text-slate-600">All payments on our website are processed securely via third-party payment gateways such as Razorpay.</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Your financial information is encrypted and handled directly by Razorpay</li><li>We do not store or have access to your full payment details</li><li>Razorpay complies with PCI-DSS standards for secure transactions</li></ul><p class="mt-3 leading-relaxed text-slate-600">You are advised to review Razorpay&rsquo;s privacy policy for more details on how they handle your data.</p>'
                    ],
                    [
                        'num' => 4,
                        'title' => 'Cookies & Tracking Technologies',
                        'content' => '<p class="leading-relaxed text-slate-600">We use cookies and similar tracking technologies to:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Enhance user experience</li><li>Understand user behavior</li><li>Store user preferences</li></ul><p class="mt-3 leading-relaxed text-slate-600">You can choose to disable cookies through your browser settings. However, this may affect certain functionalities of the website.</p>'
                    ],
                    [
                        'num' => 5,
                        'title' => 'Data Sharing & Disclosure',
                        'content' => '<p class="leading-relaxed text-slate-600">We do not sell or rent your personal information. However, we may share your data with:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Trusted service providers (payment gateways, hosting, analytics)</li><li>Legal authorities if required by law</li><li>Business partners (only when necessary to provide services)</li></ul><p class="mt-3 leading-relaxed text-slate-600">All third-party partners are obligated to keep your information secure.</p>'
                    ],
                    [
                        'num' => 6,
                        'title' => 'Data Retention',
                        'content' => '<p class="leading-relaxed text-slate-600">We retain your personal information only for as long as necessary:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>To fulfill the purposes outlined in this policy</li><li>To comply with legal, accounting, or regulatory requirements</li></ul><p class="mt-3 leading-relaxed text-slate-600">Once data is no longer required, it is securely deleted or anonymized.</p>'
                    ],
                    [
                        'num' => 7,
                        'title' => 'Data Security',
                        'content' => '<p class="leading-relaxed text-slate-600">We implement appropriate technical and organizational security measures to protect your data, including:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Secure servers and encryption</li><li>Restricted access to personal information</li><li>Regular monitoring for vulnerabilities</li></ul><p class="mt-3 leading-relaxed text-slate-600">However, no online system is 100% secure, and we cannot guarantee absolute security.</p>'
                    ],
                    [
                        'num' => 8,
                        'title' => 'Your Rights',
                        'content' => '<p class="leading-relaxed text-slate-600">You have the following rights regarding your personal data:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Access your personal information</li><li>Request correction of inaccurate data</li><li>Request deletion of your data</li><li>Withdraw consent at any time</li></ul><p class="mt-3 leading-relaxed text-slate-600">To exercise your rights, please contact us at the details provided below.</p>'
                    ],
                    [
                        'num' => 9,
                        'title' => 'Third-Party Links',
                        'content' => '<p class="leading-relaxed text-slate-600">Our website may contain links to third-party websites. We are not responsible for their privacy practices or content. We recommend reviewing their policies before sharing any information.</p>'
                    ],
                    [
                        'num' => 10,
                        'title' => 'Children&rsquo;s Privacy',
                        'content' => '<p class="leading-relaxed text-slate-600">Our services are not intended for individuals under the age of 18. We do not knowingly collect personal data from children.</p>'
                    ],
                    [
                        'num' => 11,
                        'title' => 'Changes to This Privacy Policy',
                        'content' => '<p class="leading-relaxed text-slate-600">We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated "Last Updated" date.</p>'
                    ],
                    [
                        'num' => 12,
                        'title' => 'Contact Us',
                        'content' => '<p class="leading-relaxed text-slate-600">If you have any questions or concerns about this Privacy Policy, you can contact us:</p><ul class="mt-2 list-disc pl-5 text-slate-600"><li>Company Name: Biogenix Inc Pvt Ltd</li><li>Email:</li><li>Phone:</li><li>Address:</li></ul>'
                    ],
                ];
            @endphp

            @foreach ($privacySections as $section)
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300">
                    <button
                        type="button"
                        class="flex w-full items-center gap-4 px-6 py-5 text-left transition hover:bg-slate-50"
                        onclick="togglePrivacySection({{ $section['num'] }})"
                        aria-expanded="false"
                        id="privacy-btn-{{ $section['num'] }}"
                    >
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-sm font-bold text-primary-700">{{ $section['num'] }}</span>
                        <span class="flex-1 text-base font-semibold text-slate-900">{{ $section['title'] }}</span>
                        <svg class="privacy-chevron h-5 w-5 shrink-0 text-slate-400 transition-transform duration-300" id="privacy-chevron-{{ $section['num'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="privacy-panel hidden" id="privacy-panel-{{ $section['num'] }}">
                        <div class="border-t border-slate-100 px-6 pb-6 pt-4 pl-[4.5rem]">
                            {!! $section['content'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 rounded-2xl border border-primary-100 bg-primary-50/60 p-6">
            <h2 class="mb-2 flex items-center text-xl font-semibold text-slate-900">
                <svg class="mr-2 h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                Contact for Privacy Requests
            </h2>
            <p class="text-slate-600">Company Name: Biogenix Inc Pvt Ltd | Email: | Phone: | Address:</p>
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
                <a href="{{ route('terms') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Terms of Service</a>
                <a href="{{ route('privacy') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Cookie Policy</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Compliance</a>
            </nav>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        togglePrivacySection(1);
    });

    function togglePrivacySection(num) {
        var panel = document.getElementById('privacy-panel-' + num);
        var chevron = document.getElementById('privacy-chevron-' + num);
        var btn = document.getElementById('privacy-btn-' + num);
        if (!panel) return;

        var isOpen = !panel.classList.contains('hidden');
        document.querySelectorAll('.privacy-panel').forEach(function(p) { p.classList.add('hidden'); });
        document.querySelectorAll('.privacy-chevron').forEach(function(c) { c.classList.remove('rotate-180'); });
        document.querySelectorAll('[id^="privacy-btn-"]').forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });

        if (!isOpen) {
            panel.classList.remove('hidden');
            chevron.classList.add('rotate-180');
            btn.setAttribute('aria-expanded', 'true');
        }
    }
</script>
@endpush
@endsection

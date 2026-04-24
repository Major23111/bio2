@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="min-h-screen bg-slate-50 pb-24">
    <section class="border-b border-slate-200 bg-white py-12 md:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Privacy Policy</h1>
            <p class="mt-3 flex items-center gap-2 text-sm text-primary-600">Last Updated: 22/04/2026</p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <p class="text-base leading-8 text-slate-700">
                Welcome to Biogenix Inc Pvt Ltd ("Company", "we", "our", "us"). We are committed to protecting your privacy and ensuring that your personal information is handled in a safe and responsible manner.
                This Privacy Policy describes how we collect, use, disclose, and safeguard your information when you visit our website biogenix.in and use our services.
            </p>
        </div>

        <div class="space-y-4">
            @php
                $privacySections = [
                    ['num' => 1, 'title' => 'Information We Collect', 'content' => '<p class="leading-relaxed text-slate-600">We may collect the following types of information:</p><p class="mt-3 leading-relaxed text-slate-600"><strong>a) Personal Information</strong><br>When you interact with our website, we may collect: Full Name, Email Address, Phone Number, Billing and Shipping Address, and any other information you voluntarily provide.</p><p class="mt-3 leading-relaxed text-slate-600"><strong>b) Payment Information</strong><br>We do not store your payment details such as card numbers, CVV, or banking credentials. All payment transactions are securely processed through our payment gateway partner (Razorpay).</p><p class="mt-3 leading-relaxed text-slate-600"><strong>c) Technical & Usage Data</strong><br>We may automatically collect: IP Address, Browser type and version, Device information, Pages visited and time spent, Cookies and tracking technologies.</p>'],
                    ['num' => 2, 'title' => 'How We Use Your Information', 'content' => '<p class="leading-relaxed text-slate-600">We use your information for the following purposes: to process transactions and provide services, to communicate with you (order updates, support, notifications), to improve our website and services, to prevent fraud and ensure security, and to comply with legal obligations.</p>'],
                    ['num' => 3, 'title' => 'Payment Processing', 'content' => '<p class="leading-relaxed text-slate-600">All payments on our website are processed securely via third-party payment gateways such as Razorpay. Your financial information is encrypted and handled directly by Razorpay. We do not store or have access to your full payment details. Razorpay complies with PCI-DSS standards for secure transactions. You are advised to review Razorpay\'s privacy policy for more details on how they handle your data.</p>'],
                    ['num' => 4, 'title' => 'Cookies & Tracking Technologies', 'content' => '<p class="leading-relaxed text-slate-600">We use cookies and similar tracking technologies to enhance user experience, understand user behavior, and store user preferences. You can disable cookies through your browser settings, though some website features may be affected.</p>'],
                    ['num' => 5, 'title' => 'Data Sharing & Disclosure', 'content' => '<p class="leading-relaxed text-slate-600">We do not sell or rent your personal information. However, we may share your data with trusted service providers (payment gateways, hosting, analytics), legal authorities when required by law, and business partners when necessary to provide services. All third-party partners are obligated to keep your information secure.</p>'],
                    ['num' => 6, 'title' => 'Data Retention', 'content' => '<p class="leading-relaxed text-slate-600">We retain your personal information only for as long as necessary to fulfill the purposes outlined in this policy and to comply with legal, accounting, or regulatory requirements. Once data is no longer required, it is securely deleted or anonymized.</p>'],
                    ['num' => 7, 'title' => 'Data Security', 'content' => '<p class="leading-relaxed text-slate-600">We implement appropriate technical and organizational security measures to protect your data, including secure servers and encryption, restricted access to personal information, and regular monitoring for vulnerabilities. However, no online system is 100% secure, and we cannot guarantee absolute security.</p>'],
                    ['num' => 8, 'title' => 'Your Rights', 'content' => '<p class="leading-relaxed text-slate-600">You have the following rights regarding your personal data: access your personal information, request correction of inaccurate data, request deletion of your data, and withdraw consent at any time. To exercise your rights, please contact us at the details provided below.</p>'],
                    ['num' => 9, 'title' => 'Third-Party Links', 'content' => '<p class="leading-relaxed text-slate-600">Our website may contain links to third-party websites. We are not responsible for their privacy practices or content. We recommend reviewing their policies before sharing any information.</p>'],
                    ['num' => 10, 'title' => 'Children\'s Privacy', 'content' => '<p class="leading-relaxed text-slate-600">Our services are not intended for individuals under the age of 18. We do not knowingly collect personal data from children.</p>'],
                    ['num' => 11, 'title' => 'Changes to This Privacy Policy', 'content' => '<p class="leading-relaxed text-slate-600">We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated "Last Updated" date.</p>'],
                    ['num' => 12, 'title' => 'Contact Us', 'content' => '<p class="leading-relaxed text-slate-600">If you have any questions or concerns about this Privacy Policy, you can contact us:</p><p class="mt-3 leading-relaxed text-slate-600">Company Name: Biogenix Inc Pvt Ltd<br>Email:<br>Phone:<br>Address:</p>'],
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
    </section>
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

@extends('layouts.app')

@section('title', 'Refund & Cancellation Policy')

@section('content')
<div class="min-h-screen bg-slate-50 pb-24">
    <section class="border-b border-slate-200 bg-white py-12 md:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-4 text-sm text-slate-500">
                <a href="{{ route('home') }}" class="text-slate-500 no-underline hover:text-primary-600">Home</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-slate-700">Legal</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Refund &amp; Cancellation Policy</h1>
            <p class="mt-3 max-w-xl text-base leading-7 text-slate-600">Last Updated: 22/04/2026</p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-10 border-b border-slate-200">
            <nav class="flex gap-6" id="refund-tabs">
                <button type="button" class="refund-tab active border-b-2 border-primary-600 pb-3 text-sm font-semibold text-primary-700 transition" data-tab="policy-1" onclick="switchRefundTab('policy-1')">Policy Basics</button>
                <button type="button" class="refund-tab border-b-2 border-transparent pb-3 text-sm font-semibold text-slate-500 transition hover:text-slate-700" data-tab="policy-2" onclick="switchRefundTab('policy-2')">Refund Rules</button>
                <button type="button" class="refund-tab border-b-2 border-transparent pb-3 text-sm font-semibold text-slate-500 transition hover:text-slate-700" data-tab="policy-3" onclick="switchRefundTab('policy-3')">Requests &amp; Updates</button>
            </nav>
        </div>

        <div class="refund-tab-content" id="tab-policy-1">
            <div class="space-y-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900">At Biogenix Inc Pvt Ltd</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">At Biogenix Inc Pvt Ltd, we strive to provide high-quality products and services. This Refund &amp; Cancellation Policy outlines the terms under which cancellations and refunds are processed for transactions made on biogenix.in.</p>
                    <p class="mt-3 text-sm leading-7 text-slate-600">By making a purchase on our website, you agree to the terms of this policy.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">1. Cancellation Policy</h3>
                    <p class="mt-3 text-sm font-semibold text-slate-800">A. Order Cancellation by User</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>You may request cancellation of your order/service within [X hours/days] of placing the order.</li>
                        <li>Cancellation requests must be made through our official communication channels (email/phone).</li>
                        <li>Orders once processed, shipped, or delivered may not be eligible for cancellation.</li>
                    </ul>
                    <p class="mt-4 text-sm font-semibold text-slate-800">B. Cancellation by Company</p>
                    <p class="mt-2 text-sm leading-7 text-slate-600">We reserve the right to cancel any order under the following circumstances:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Product/service is unavailable</li>
                        <li>Payment is not successfully completed</li>
                        <li>Suspected fraudulent or unauthorized transaction</li>
                        <li>Incorrect pricing or technical errors</li>
                    </ul>
                    <p class="mt-3 text-sm leading-7 text-slate-600">In such cases, the full amount will be refunded to the original payment method.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">2. Refund Policy</h3>
                    <p class="mt-3 text-sm font-semibold text-slate-800">A. Eligibility for Refund</p>
                    <p class="mt-2 text-sm leading-7 text-slate-600">Refunds will be processed only under the following conditions:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Payment has been successfully made but the service/product was not delivered</li>
                        <li>Order was cancelled within the allowed time frame</li>
                        <li>Duplicate payment was made for the same transaction</li>
                        <li>Transaction failed but amount was deducted</li>
                    </ul>
                    <p class="mt-4 text-sm font-semibold text-slate-800">B. Non-Refundable Cases</p>
                    <p class="mt-2 text-sm leading-7 text-slate-600">Refunds will not be provided in the following situations:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Services that have already been rendered</li>
                        <li>Digital products that have been accessed, downloaded, or used</li>
                        <li>Requests made after the specified cancellation period</li>
                        <li>Any misuse or violation of Terms of Use</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="refund-tab-content hidden" id="tab-policy-2">
            <div class="space-y-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">3. Refund Process</h3>
                    <ul class="mt-3 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Once your refund request is approved, it will be initiated within [2 - 5 business days]</li>
                        <li>The refund will be processed through the original payment method used during the transaction</li>
                        <li>You will be notified via email once the refund is initiated</li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">4. Refund Timeline</h3>
                    <ul class="mt-3 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Refunds are typically credited within 5 - 7 business days</li>
                        <li>The exact time may vary depending on your bank or payment provider</li>
                    </ul>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Breakdown:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>UPI -> 2 - 5 working days</li>
                        <li>Credit/Debit Cards -> 5 - 7 working days</li>
                        <li>Net Banking -> 5 - 7 working days</li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">5. Payment Gateway Charges</h3>
                    <ul class="mt-3 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>In certain cases, payment gateway charges or transaction fees may be deducted from the refund amount</li>
                        <li>These charges are non-refundable and depend on the payment provider&rsquo;s policies</li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">6. Failed Transactions</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">If your transaction fails but the amount is debited:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>The amount will usually be automatically reversed by your bank</li>
                        <li>This process may take 5 - 7 business days</li>
                    </ul>
                    <p class="mt-3 text-sm leading-7 text-slate-600">If the refund is not received within this time, please contact us with transaction details.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">7. Partial Refunds</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">In certain cases, partial refunds may be issued:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>If only part of the service is delivered</li>
                        <li>If cancellation is requested after partial processing</li>
                    </ul>
                    <p class="mt-3 text-sm leading-7 text-slate-600">The refund amount will be determined at our sole discretion.</p>
                </div>
            </div>
        </div>

        <div class="refund-tab-content hidden" id="tab-policy-3">
            <div class="space-y-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">8. How to Request a Refund or Cancellation</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">To initiate a cancellation or refund request, please contact us with the following details:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Full Name</li>
                        <li>Registered Email Address</li>
                        <li>Transaction ID / Order ID</li>
                        <li>Reason for cancellation/refund</li>
                    </ul>
                    <p class="mt-3 text-sm font-semibold text-slate-800">Contact Details:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Email:</li>
                        <li>Phone:</li>
                        <li>Address:</li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">9. Dispute Resolution</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">If you are not satisfied with the resolution provided:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>You may escalate the issue to our support team</li>
                        <li>We will make reasonable efforts to resolve disputes in a fair and timely manner</li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">10. Policy Updates</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">We reserve the right to modify this Refund &amp; Cancellation Policy at any time without prior notice.</p>
                    <ul class="mt-2 list-disc pl-5 text-sm leading-7 text-slate-600">
                        <li>Changes will be updated on this page</li>
                        <li>Continued use of our services constitutes acceptance of the updated policy</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="mt-8 border-t border-slate-200 bg-white py-6">
        <div class="mx-auto flex max-w-4xl flex-wrap items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <p class="text-sm text-slate-500">&copy; 2024 Biogenix Biotech Solutions. All rights reserved.</p>
            <nav class="flex flex-wrap gap-6">
                <a href="{{ route('terms') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Terms of Service</a>
                <a href="{{ route('privacy') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Privacy Policy</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Compliance</a>
            </nav>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchRefundTab(tab) {
    document.querySelectorAll('.refund-tab').forEach(function(btn) {
        var isActive = btn.getAttribute('data-tab') === tab;
        btn.classList.toggle('active', isActive);
        btn.classList.toggle('border-primary-600', isActive);
        btn.classList.toggle('text-primary-700', isActive);
        btn.classList.toggle('border-transparent', !isActive);
        btn.classList.toggle('text-slate-500', !isActive);
    });

    document.querySelectorAll('.refund-tab-content').forEach(function(content) {
        content.classList.add('hidden');
    });
    var activeContent = document.getElementById('tab-' + tab);
    if (activeContent) {
        activeContent.classList.remove('hidden');
    }
}
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Refund & Cancellation Policy')

@section('content')
<div class="min-h-screen bg-slate-50 pb-24">
    <section class="border-b border-slate-200 bg-white py-12 md:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Refund &amp; Cancellation Policy</h1>
            <p class="mt-3 max-w-xl text-base leading-7 text-slate-600">Last Updated: 22/04/2026</p>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                At Biogenix Inc Pvt Ltd, we strive to provide high-quality products and services. This policy outlines the terms under which cancellations and refunds are processed for transactions made on biogenix.in.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-10 border-b border-slate-200">
            <nav class="flex gap-6" id="refund-tabs">
                <button type="button" class="refund-tab active border-b-2 border-primary-600 pb-3 text-sm font-semibold text-primary-700 transition" data-tab="cancellation" onclick="switchRefundTab('cancellation')">Cancellation</button>
                <button type="button" class="refund-tab border-b-2 border-transparent pb-3 text-sm font-semibold text-slate-500 transition hover:text-slate-700" data-tab="refunds" onclick="switchRefundTab('refunds')">Refunds</button>
                <button type="button" class="refund-tab border-b-2 border-transparent pb-3 text-sm font-semibold text-slate-500 transition hover:text-slate-700" data-tab="support" onclick="switchRefundTab('support')">Support & Updates</button>
            </nav>
        </div>

        <div class="refund-tab-content" id="tab-cancellation">
            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">1. Cancellation Policy</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600"><strong>A. Order Cancellation by User:</strong> You may request cancellation within [X hours/days] of placing the order through official channels (email/phone). Orders already processed, shipped, or delivered may not be eligible.</p>
                    <p class="mt-3 text-sm leading-7 text-slate-600"><strong>B. Cancellation by Company:</strong> We may cancel if product/service is unavailable, payment is unsuccessful, suspicious transaction is detected, or pricing/technical errors occur. In such cases, full refund is made to the original payment method.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">6. Failed Transactions</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">If a transaction fails but amount is debited, it is usually reversed automatically by your bank within 5-7 business days. If delayed, contact us with transaction details.</p>
                </div>
            </div>
        </div>

        <div class="refund-tab-content hidden" id="tab-refunds">
            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">2. Refund Policy</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600"><strong>Eligibility:</strong> Refunds are processed when payment is successful but service/product is not delivered, order is cancelled in allowed period, duplicate payment occurs, or a failed transaction still debits amount.</p>
                    <p class="mt-3 text-sm leading-7 text-slate-600"><strong>Non-Refundable Cases:</strong> Rendered services, accessed/downloaded digital products, late requests beyond cancellation period, and misuse or Terms violations.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">3. Refund Process</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Approved refunds are initiated within [2 - 5 business days], processed to the original payment method, and notified by email.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">4. Refund Timeline</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Typical credit timeline is 5 - 7 business days depending on bank/payment provider.</p>
                    <p class="mt-2 text-sm leading-7 text-slate-600">UPI: 2 - 5 working days | Credit/Debit Cards: 5 - 7 working days | Net Banking: 5 - 7 working days</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">5. Payment Gateway Charges</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">In certain cases, payment gateway/transaction fees may be deducted based on provider policy. Such charges are non-refundable.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">7. Partial Refunds</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Partial refunds may apply where part of service is delivered or cancellation is requested after partial processing. Amount is determined at company discretion.</p>
                </div>
            </div>
        </div>

        <div class="refund-tab-content hidden" id="tab-support">
            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">8. How to Request a Refund or Cancellation</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Share: Full Name, Registered Email Address, Transaction ID / Order ID, and reason for request.</p>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Contact Details:<br>Email:<br>Phone:<br>Address:</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">9. Dispute Resolution</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">If you are not satisfied with the resolution, you may escalate to our support team. We make reasonable efforts to resolve disputes in a fair and timely manner.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">10. Policy Updates</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">We may modify this policy at any time without prior notice. Changes are posted on this page, and continued use of services implies acceptance of the updated policy.</p>
                </div>
            </div>
        </div>
    </section>
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

@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
@php
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-600/40';
    $faqCategories = [
        ['key' => 'payments', 'label' => 'Payments'],
        ['key' => 'refunds', 'label' => 'Refunds'],
        ['key' => 'support', 'label' => 'Support'],
    ];

    $faqs = collect([
        (object) ['category' => 'Payments', 'question' => 'What payment methods do you accept?', 'answer' => 'We accept Credit Cards (Visa, MasterCard, etc.), Debit Cards, Net Banking, UPI (Unified Payments Interface), and Wallets (supported by Razorpay). All payments are processed securely via our payment gateway.', 'is_default_open' => true],
        (object) ['category' => 'Payments', 'question' => 'Is it safe to make payments on your website?', 'answer' => 'Yes. All transactions are processed through a secure and encrypted payment gateway powered by Razorpay, which complies with PCI-DSS standards. Your payment details such as card number, CVV, and banking credentials are not stored on our servers.', 'is_default_open' => false],
        (object) ['category' => 'Payments', 'question' => 'Will I receive a confirmation after payment?', 'answer' => 'Yes, once your payment is successfully completed, you will receive a payment confirmation on the website and an email and/or SMS confirmation (if contact details are provided). If you do not receive confirmation, please contact our support team.', 'is_default_open' => false],
        (object) ['category' => 'Payments', 'question' => 'What happens if my payment fails?', 'answer' => 'If your payment fails, the amount may be debited temporarily and the transaction will be marked as failed. The amount is usually reversed automatically within 5-7 business days, depending on your bank or payment provider. If delayed, contact us with transaction details.', 'is_default_open' => false],
        (object) ['category' => 'Refunds', 'question' => 'Can I cancel my order after making payment?', 'answer' => 'Yes, you can cancel your order or service request, subject to our Cancellation Policy. Cancellation requests must be made within the specified time frame. Once the service has been processed or delivered, cancellation may not be possible.', 'is_default_open' => false],
        (object) ['category' => 'Refunds', 'question' => 'How will I receive my refund?', 'answer' => 'Refunds, if applicable, will be processed using the original payment method: Credit/Debit Card to same card, UPI to same UPI ID, and Net Banking to originating account. Refund processing time is typically 5-7 working days, depending on your bank.', 'is_default_open' => false],
        (object) ['category' => 'Payments', 'question' => 'Are there any additional charges on payments?', 'answer' => 'We do not charge any hidden fees. Your bank or payment provider may apply charges such as transaction fees or taxes. These charges are outside our control.', 'is_default_open' => false],
        (object) ['category' => 'Support', 'question' => 'What should I do if my payment is successful but I did not receive the service/product?', 'answer' => 'Check your email/SMS confirmation, then contact our support team with transaction ID, payment details, and order details. We will verify the transaction and ensure proper resolution.', 'is_default_open' => false],
        (object) ['category' => 'Payments', 'question' => 'Do you store my payment information?', 'answer' => 'No. We do not store any sensitive payment information such as card numbers, CVV, or banking credentials. All payment data is handled securely by Razorpay in compliance with industry standards.', 'is_default_open' => false],
        (object) ['category' => 'Refunds', 'question' => 'What should I do if I was charged multiple times?', 'answer' => 'Check your bank statement carefully, as failed transactions may show temporary holds. If duplicate charges are confirmed, the extra amount will be refunded automatically, and you can also contact us for assistance.', 'is_default_open' => false],
        (object) ['category' => 'Support', 'question' => 'Can I get an invoice for my payment?', 'answer' => 'Yes, you can request an invoice by contacting our support team, or it may be automatically sent to your registered email address after successful payment.', 'is_default_open' => false],
        (object) ['category' => 'Support', 'question' => 'Who should I contact for payment-related issues?', 'answer' => 'For payment-related queries, contact us with transaction details for faster resolution. Email:  Phone:  Address:', 'is_default_open' => false],
    ]);

    $hasDefaultOpen = $faqs->contains(fn ($faq) => (bool) $faq->is_default_open);
@endphp

<div class="min-h-screen bg-slate-50">
    <section class="relative overflow-hidden bg-primary-800 py-16 text-white md:py-24">
        <img src="{{ asset('upload/corousel/image4.jpg') }}" alt="FAQ Background" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-primary-800/95 via-primary-800/70 to-primary-600/30"></div>
        <div class="relative z-10 mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
            <h1 class="mx-auto max-w-4xl font-display text-4xl font-bold tracking-tight text-secondary-600 md:text-5xl lg:text-6xl">Frequently Asked Questions (FAQ)</h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-secondary-600 md:text-lg">Last Updated: 22/04/2026</p>
        </div>
    </section>

    <section class="relative z-20 -mt-8">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="md:col-span-3">
                        <label for="faqSearch" class="mb-2 block text-sm font-semibold text-slate-700">Search FAQs</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input id="faqSearch" type="text" class="{{ $inputClass }} pl-10" placeholder="Search payment or refund questions...">
                        </div>
                    </div>
                    <div class="mt-2 md:col-span-3">
                        <label class="mb-3 block text-sm font-semibold text-slate-700">Filter by Category</label>
                        <div id="faqFilterTabs" class="flex flex-wrap gap-2">
                            <button type="button" data-filter="all" class="rounded-full bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition">All Categories</button>
                            @foreach ($faqCategories as $faqCategory)
                                <button type="button" data-filter="{{ $faqCategory['key'] }}" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200">
                                    {{ $faqCategory['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8 xl:px-10">
            <div id="faqAccordion" class="space-y-6">
                @foreach ($faqs as $faq)
                    @php
                        $categoryKey = \Illuminate\Support\Str::slug($faq->category);
                        $isOpen = (bool) $faq->is_default_open || (! $hasDefaultOpen && $loop->first);
                        $searchText = strtolower($faq->category . ' ' . $faq->question . ' ' . $faq->answer);
                    @endphp
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-primary-200"
                        data-faq-item
                        data-faq-category="{{ $categoryKey }}"
                        data-faq-search-text="{{ $searchText }}"
                    >
                        <x-accordion :title="$faq->question" :open="$isOpen">
                            <p class="text-slate-600">{{ $faq->answer }}</p>
                        </x-accordion>
                    </div>
                @endforeach
            </div>

            <div id="faqEmptyState" class="mt-8 hidden rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">No results found</h3>
                <p class="mt-2 text-sm text-slate-500">Try adjusting your keywords or category filter.</p>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('faqSearch');
        const tabs = document.querySelectorAll('#faqFilterTabs button');
        const items = Array.from(document.querySelectorAll('[data-faq-item]'));
        const emptyState = document.getElementById('faqEmptyState');
        let currentFilter = 'all';

        function normalize(value) {
            return (value || '').toLowerCase().trim();
        }

        function applyFilter() {
            const searchTerm = normalize(searchInput ? searchInput.value : '');
            let visibleCount = 0;

            items.forEach(function (item) {
                const category = item.getAttribute('data-faq-category') || '';
                const textForSearch = (item.getAttribute('data-faq-search-text') || '').toLowerCase();
                const categoryMatch = currentFilter === 'all' || currentFilter === category;
                const searchMatch = !searchTerm || textForSearch.includes(searchTerm);
                const visible = categoryMatch && searchMatch;

                item.classList.toggle('hidden', !visible);
                if (visible) {
                    visibleCount++;
                }
            });

            if (emptyState) {
                emptyState.classList.toggle('hidden', visibleCount !== 0);
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilter);
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => {
                    t.classList.remove('bg-primary-600', 'text-white');
                    t.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                });

                this.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                this.classList.add('bg-primary-600', 'text-white');

                currentFilter = this.getAttribute('data-filter');
                applyFilter();
            });
        });

        applyFilter();
    });
</script>
@endpush
@endsection

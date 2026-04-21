@extends('admin.layout')

@section('title', 'Generate PI - Biogenix Admin')

@section('admin_content')

<form
    method="POST"
    id="piForm"
    @if(isset($proforma))
        action="{{ route('admin.pi-quotation.update', $proforma['id']) }}"
    @else
        action="{{ route('admin.pi-quotation.store') }}"
    @endif
>
    @csrf
    @if(isset($proforma))
        @method('PUT')
    @endif

    {{-- Hidden inputs for status, items, and terms submitted with the form --}}
    <input type="hidden" id="hidden_status" name="status" value="{{ old('status', $proforma['status'] ?? 'draft') }}">
    <input type="hidden" id="hidden_items_json" name="items_json" value="">
    <input type="hidden" id="hidden_terms" name="terms" value="">
    <input type="hidden" id="hidden_submit_action" name="submit_action" value="{{ old('submit_action', 'save') }}">

<div class="w-full py-8">

    <!-- Back Arrow + Breadcrumb -->
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('admin.pi-quotation.index') }}" class="ajax-link h-8 w-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition shrink-0 cursor-pointer" title="Back to PI Management">
            <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        

    </div>

    {{-- ─── Page Header ─── --}}
    <div class="mb-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Generate PI</h1>
            <p class="text-sm text-slate-500 mt-1">Create a new Proforma Invoice with product and billing details.</p>
        </div>
    </div>

    {{-- ═╤═ PI Header Info ═╤═ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center gap-2.5">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-600 text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <h2 class="text-lg font-bold tracking-tight text-slate-900">PI Header Info</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">PI Number</label>
                <input id="piNumber" type="text" name="pi_number" placeholder="Enter PI Number"
                    value="{{ old('pi_number', $proforma['piNumber'] ?? '') }}"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-800 outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Date</label>
                <input id="piDate" type="date" name="pi_date"
                    value="{{ old('pi_date', $proforma['piDate'] ?? '') }}"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none focus:border-primary-600">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">State</label>
                <input id="piStateCode" type="text" name="seller_state_code" placeholder="27 (Maharashtra)"
                    value="{{ old('seller_state_code', $proforma['sellerStateCode'] ?? '') }}"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">GSTIN</label>
                <input id="piGstin" type="text" name="seller_gstin" placeholder="27AAACB1234F1Z5"
                    value="{{ old('seller_gstin', $proforma['sellerGstin'] ?? '') }}"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600">
            </div>
        </div>
    </div>

    {{-- ═╤═ Customer Details ═╤═ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-600 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0113 0"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Customer Details</h2>
            </div>
            <div class="flex items-center gap-2 select-none">
                <span class="text-sm font-semibold text-slate-600">Same as Billing</span>
                <button id="toggleTrack" type="button" role="switch" aria-checked="false"
                    class="relative h-6 w-[42px] rounded-full bg-slate-300 transition-colors duration-200 cursor-pointer">
                    <span id="toggleThumb"
                        class="absolute left-[3px] top-[3px] h-[18px] w-[18px] translate-x-0 rounded-full bg-white shadow-[0_1px_3px_rgba(0,0,0,0.2)] transition-transform duration-200"></span>
                </button>
                <input id="sameAsBilling" type="checkbox" class="hidden">
            </div>
        </div>

        <div class="mb-1.5 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Billing Address</label>
                <textarea id="billingAddress" name="billing_address" rows="4" placeholder="Enter full billing address..."
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600">{{ old('billing_address', $proforma['billingAddress'] ?? '') }}</textarea>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Shipping Address</label>
                <textarea id="shippingAddress" name="shipping_address" rows="4" placeholder="Enter full shipping address..."
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600">{{ old('shipping_address', $proforma['shippingAddress'] ?? '') }}</textarea>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <input id="contactPerson" type="text" name="contact_person" placeholder="Contact Person"
                    value="{{ old('contact_person', $proforma['targetName'] ?? '') }}"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600">
            </div>
            <div>
                <input id="customerEmail" type="email" name="target_email" placeholder="Email Address (Mandatory) *"
                    value="{{ old('target_email', $proforma['targetEmail'] ?? '') }}"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-rose-400 focus:ring-1 focus:ring-rose-400 transition">
            </div>
            <div>
                <input id="customerGstin" type="text" name="customer_gstin" placeholder="GSTIN (Customer)"
                    value="{{ old('customer_gstin', $proforma['customerGstin'] ?? '') }}"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600">
            </div>
            <div>
                <input id="deliveryPhone" type="text" name="target_phone" placeholder="Delivery Contact / Phone"
                    value="{{ old('target_phone', $proforma['targetPhone'] ?? '') }}"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600">
            </div>
        </div>
    </div>

    {{-- ═╤═ Product Details ═╤═ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-600 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Product Details</h2>
            </div>
            <button id="addProductRow" type="button"
                class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-4 py-2 text-[0.82rem] font-bold text-white shadow-[0_2px_8px_rgba(26,77,46,0.25)] transition-colors hover:bg-primary-700 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14m-7-7h14"/></svg>
                Add Product Row
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="productTable">
                <thead>
                    <tr class="border-b-2 border-slate-200 bg-slate-50">
                        <th class="w-[45px] whitespace-nowrap px-2 py-2.5 text-center text-xs font-bold uppercase tracking-wider text-slate-500">S.No</th>
                        <th class="w-[130px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Cat. No</th>
                        <th class="min-w-[130px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Product Name</th>
                        <th class="w-[100px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Pack Size</th>
                        <th class="w-[60px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Qty</th>
                        <th class="w-[95px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Rate (&#8377;)</th>
                        <th class="w-[95px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Value (&#8377;)</th>
                        <th class="w-[60px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">GST %</th>
                        <th class="w-[100px] whitespace-nowrap px-2 py-2.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Total (&#8377;)</th>
                        <th class="w-9"></th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                </tbody>
            </table>
            <p id="emptyTableMsg" class="py-6 text-center text-sm text-slate-400">No products added yet. Click "Add Product Row" to add items.</p>
        </div>
    </div>

    {{-- ═╤═ Bottom: Terms & Totals ═╤═ --}}
    <div class="grid grid-cols-1 items-start gap-5 md:grid-cols-2">

        @php
            $defaultTerms = [
                'Supply within 3-4 week after confirmation order along with 100% advance payment.',
                'All Disputes are subject to Lucknow Jurisdiction only',
            ];

            $savedTermsText = old('terms', $proforma['terms'] ?? implode("\n", $defaultTerms));
            $savedTermsLines = preg_split('/\r\n|\r|\n/', (string) $savedTermsText) ?: [];
            $termInputLines = [];

            foreach ($savedTermsLines as $savedTermLine) {
                $cleanTermLine = trim((string) $savedTermLine);

                if ($cleanTermLine !== '') {
                    $termInputLines[] = $cleanTermLine;
                }
            }

            if ($termInputLines === []) {
                $termInputLines = $defaultTerms;
            }
        @endphp

        {{-- Terms & Conditions --}}
        <div class="self-start rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-600 text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </span>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight text-slate-900">Terms & Conditions</h2>
                        <p class="mt-0.5 text-xs font-medium text-slate-400">Add, edit, and save invoice terms before final submission.</p>
                    </div>
                </div>
                <button id="saveTermsBtn" type="button"
                    class="inline-flex h-9 items-center justify-center gap-2 self-start rounded-lg border border-slate-200 bg-white px-4 text-[11px] font-black uppercase tracking-widest text-slate-700 transition hover:bg-slate-50 hover:text-primary-800 cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Save Terms
                </button>
            </div>

            <ol id="termsList" class="space-y-2.5">
                @foreach($termInputLines as $termIndex => $termInputLine)
                <li data-term-item data-editing="false" class="group rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm transition">
                    <div class="flex items-start gap-3">
                        <span data-term-number class="mt-0.5 shrink-0 text-[13px] font-black text-primary-600">{{ $termIndex + 1 }}.</span>
                        <div class="min-w-0 flex-1">
                            <p data-term-text class="whitespace-normal break-words text-sm font-medium leading-6 text-slate-700">{{ $termInputLine }}</p>
                            <textarea data-term-input rows="2"
                                class="mt-2 hidden min-h-[72px] w-full resize-none rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">{{ $termInputLine }}</textarea>
                        </div>
                        <div class="flex shrink-0 items-start gap-1">
                            <button type="button" class="term-edit-btn inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-primary-50 hover:text-primary-600 cursor-pointer" aria-label="Edit term">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button type="button" class="term-delete-btn hidden h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 cursor-pointer" aria-label="Delete term">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </li>
                @endforeach
            </ol>

            <button id="openTermComposerBtn" type="button"
                class="mt-3 inline-flex items-center gap-2 rounded-lg px-1 py-1 text-sm font-semibold text-primary-700 transition hover:text-primary-800 cursor-pointer">
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-primary-200 text-base leading-none text-primary-600">+</span>
                <span>Add Term</span>
            </button>

            <div id="termComposerPanel" class="mt-3 hidden rounded-xl border border-slate-200 bg-slate-50 p-2">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <input id="newTermInput" type="text" placeholder="Add a new term or condition..."
                        class="h-10 flex-1 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    <button id="addTermBtn" type="button"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 text-[12px] font-bold text-white shadow-sm shadow-primary-600/20 transition hover:bg-primary-700 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
                        Add Term
                    </button>
                </div>
            </div>

            <p class="mt-3 text-xs font-medium text-slate-400">Click the pencil icon to edit a term. The delete option appears only while that term is being edited.</p>
        </div>

        {{-- Actions and Summary / Totals --}}
        <div>
            @isset($proforma)
            <!-- Approve/Reject actions (Edit mode only) -->
            <div class="mb-5 flex items-center justify-end gap-3">
                <button id="rejectPiTopBtn" type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-white border border-rose-200 px-5 py-2.5 text-sm font-bold text-rose-600 transition-colors hover:bg-rose-50 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    Reject PI
                </button>
                <button id="approvePiTopBtn" type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-emerald-700 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    Approve PI
                </button>
            </div>
            @endisset

        <div class="rounded-2xl bg-primary-600 p-6 text-white shadow-lg">
            <div class="space-y-3">
                <div class="flex justify-between border-b border-primary-500/30 pb-3 text-sm">
                    <span class="text-primary-100/80">Subtotal</span>
                    <span class="font-bold text-white" id="sumSubtotal">₹ 0.00</span>
                </div>
                <div class="flex justify-between border-b border-primary-500/30 pb-3 text-sm">
                    <span class="text-primary-100/80">GST Total</span>
                    <span class="font-bold text-white" id="sumGst">₹ 0.00</span>
                </div>
                <div class="flex justify-between border-b border-primary-500/30 pb-3 text-sm">
                    <span class="text-primary-100/80">Freight Charges</span>
                    <input id="freightCharges" type="number" name="freight_charges" min="0" step="1"
                        value="{{ old('freight_charges', $proforma['freightCharges'] ?? 0) }}"
                        class="w-20 rounded-lg border border-primary-500/50 bg-primary-700/50 px-2 py-1 text-right text-sm font-bold text-white outline-none focus:border-secondary-400">
                </div>
                <div class="flex justify-between border-b border-primary-500/30 pb-3 text-sm">
                    <span class="text-primary-100/80">Freight GST (18%)</span>
                    <span class="font-bold text-white" id="sumFreightTax">₹ 0.00</span>
                </div>
                <div class="flex justify-between pb-1 text-sm">
                    <span class="text-primary-100/80">Round Off</span>
                    <span class="font-bold text-white" id="sumRoundOff">₹ 0.00</span>
                </div>
            </div>

            <div class="mt-6 flex flex-col items-end border-t border-primary-500/50 pt-6">
                <p class="mb-1 text-[0.65rem] font-bold uppercase tracking-widest text-secondary-600">Grand Total</p>
                <div class="flex items-center gap-2">
                    <span class="text-3xl font-extrabold text-secondary-600">₹</span>
                    <span class="text-4xl font-black text-secondary-600 tracking-tight" id="sumGrandTotal">0.00</span>
                </div>
                <div class="mt-3 w-full text-left">
                    <p class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-secondary-600">Amount in Words</p>
                    <p class="mt-1 text-xs font-medium italic text-primary-100/90" id="sumAmountWords">Zero Only</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button id="sendEmailBtn" type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-secondary-600 px-5 py-3.5 text-sm font-bold text-primary-800 shadow-[0_4px_14px_rgba(253,224,71,0.25)] transition-all hover:bg-secondary-500 hover:-translate-y-px cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Send Email
                </button>
                <button id="generatePdfBtn" type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-800 px-5 py-3.5 text-sm font-bold text-white shadow-[0_4px_14px_rgba(15,23,42,0.25)] transition-all hover:bg-primary-700 hover:-translate-y-px cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Generate PDF
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═╤═
     ADD PRODUCT MODAL
     ═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═╧═ --}}
<div id="addProductModal" class="fixed inset-0 z-[9999] hidden">
    {{-- Backdrop --}}
    <div id="modalBackdrop" class="absolute inset-0 bg-slate-950/55 opacity-0 backdrop-blur-[4px] transition-opacity duration-300"></div>

    {{-- Dialog --}}
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div id="modalDialog" class="pointer-events-auto relative w-full max-w-[520px] translate-y-[10px] scale-95 rounded-[20px] bg-white p-8 opacity-0 shadow-[0_25px_60px_rgba(15,23,42,0.22)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]">

            {{-- Close button --}}
            <button id="modalCloseBtn" type="button"
                class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-100 cursor-pointer">
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Header --}}
            <h3 class="text-[1.3rem] font-extrabold text-slate-800">Add Product to Invoice</h3>
            <p class="mt-1 text-[0.82rem] text-slate-500">Search and configure product details for Biogenix Inventory</p>

            {{-- Form fields --}}
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                {{-- Cat NO with Search Results --}}
                <div class="relative">
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Cat NO</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-primary-600">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        </span>
                        <input id="modalCatNo" type="text" placeholder="e.g., BG-9920" autocomplete="off"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    {{-- Cat NO Search Results --}}
                    <div id="catNoSearchResults" class="absolute left-0 right-0 z-[10001] mt-1 hidden max-h-[180px] overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-2xl p-1">
                        <!-- Results injected here -->
                    </div>
                </div>

                {{-- Product Name with Search Results --}}
                <div class="relative">
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Product Name</label>
                    <div id="modalProductNameField" class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-primary-600">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        </span>
                        <input id="modalProductName" type="text" placeholder="Search or select product" autocomplete="off"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    
                    {{-- Product Name Search Results --}}
                    <div id="productNameSearchResults" class="absolute left-0 right-0 z-[10001] mt-1 hidden max-h-[180px] overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-2xl p-1">
                        <!-- Results injected here -->
                    </div>
                </div>

                {{-- Pack Size --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Pack Size</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-primary-600">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </span>
                        <input id="modalPackSize" type="text" placeholder="e.g. Box of 5"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Quantity</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-primary-600">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3v4M8 3v4"/></svg>
                        </span>
                        <input id="modalQty" type="number" min="1" value="1" placeholder="Enter qty"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                {{-- Rate (Unit Price) --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Rate (Unit Price)</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-primary-600">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                           <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 3h18v2H3V3zm0 4h10a5 5 0 010 10H9l6 6h-4l-6-6v-2h8a3 3 0 000-6H3V7z"/>
                            </svg>
                         </span>
                        <input id="modalRate" type="number" min="0" step="0.01" value="0" placeholder="&#8377; 0.00"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                {{-- GST % --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">GST (%)</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-primary-600">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-[0.82rem] font-bold text-slate-400">%</span>
                        <input id="modalGst" type="number" min="0" max="100" step="0.01" value="18"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none">
                    </div>
                </div>
            </div>

            {{-- Total Calculated Amount --}}
            <div class="mt-5 flex items-center justify-between rounded-[14px] border border-slate-200 bg-slate-50 px-6 py-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    </span>
                    <span class="text-[0.88rem] font-semibold text-slate-600">Total Calculated Amount</span>
                </div>
                <div class="text-right">
                    <p class="text-[0.65rem] font-bold uppercase tracking-[0.1em] text-primary-600">INCL. TAXES</p>
                    <p id="modalTotalAmount" class="text-2xl font-extrabold text-slate-800">&#8377; 0.00</p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="mt-6 flex justify-end gap-3">
                <button id="modalCancelBtn" type="button"
                    class="h-11 rounded-xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50 cursor-pointer">
                    Cancel
                </button>
                <button id="modalAddBtn" type="button"
                    class="inline-flex h-11 items-center gap-2 rounded-xl bg-primary-600 px-7 text-sm font-bold text-white shadow-[0_2px_10px_rgba(26,77,46,0.25)] transition-colors hover:bg-primary-700 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Add to Invoice
                </button>
            </div>
        </div>
    </div>
</div>
@if(isset($proforma))
<script>
    var preloadedItems = @json($proforma['items'] ?? []);
</script>
@endif

<script id="inventory-products-data" type="application/json">
    @json($products ?? [])
</script>

<script>
(function() {
    // ─── Auto-fill PI Number and Date ───
    var piForm = document.getElementById('piForm');
    var piNumberInput = document.getElementById('piNumber');
    var piDateInput = document.getElementById('piDate');
    var customerEmailInput = document.getElementById('customerEmail');
    var now = new Date();
    var fy = now.getMonth() >= 3 ? now.getFullYear() : now.getFullYear() - 1;
    var piNum = 'PI/' + fy + '-' + String(fy + 1).slice(2) + '/' + String(Math.floor(1000 + Math.random() * 9000));
    if (piNumberInput && !piNumberInput.value.trim()) {
        piNumberInput.value = piNum;
    }

    if (piDateInput && !piDateInput.value) {
        piDateInput.value = now.toISOString().split('T')[0];
    }

    // ─── Same as Billing toggle ───
    var toggleTrack = document.getElementById('toggleTrack');
    var toggleThumb = document.getElementById('toggleThumb');
    var sameCheckbox = document.getElementById('sameAsBilling');
    var billingAddr = document.getElementById('billingAddress');
    var shippingAddr = document.getElementById('shippingAddress');
    var toggleOn = false;

    function syncShippingToggleState() {
        sameCheckbox.checked = toggleOn;
        toggleTrack.setAttribute('aria-checked', toggleOn ? 'true' : 'false');
        toggleTrack.classList.toggle('bg-primary-600', toggleOn);
        toggleTrack.classList.toggle('bg-slate-300', !toggleOn);
        toggleThumb.classList.toggle('translate-x-[18px]', toggleOn);
        toggleThumb.classList.toggle('translate-x-0', !toggleOn);

        if (toggleOn) {
            shippingAddr.value = billingAddr.value;
        }

        shippingAddr.readOnly = toggleOn;
        shippingAddr.classList.toggle('bg-slate-100', toggleOn);
        shippingAddr.classList.toggle('bg-white', !toggleOn);
    }

    toggleTrack.addEventListener('click', function () {
        toggleOn = !toggleOn;
        syncShippingToggleState();
    });

    billingAddr.addEventListener('input', function () {
        if (toggleOn) { shippingAddr.value = billingAddr.value; }
    });

    // ─── Product Table ───
    var tableBody = document.getElementById('productTableBody');
    var emptyMsg = document.getElementById('emptyTableMsg');

    function addRowToTable(data) {
        var rows = tableBody.querySelectorAll('.product-row');
        var sno = rows.length + 1;
        var value = data.qty * data.rate;
        var gstAmt = value * data.gst / 100;
        var total = value + gstAmt;

        var tr = document.createElement('tr');
        tr.className = 'product-row border-b border-slate-100';
        tr.innerHTML =
            '<td class="sno px-2 py-2 text-center text-sm font-semibold text-slate-500">' + String(sno).padStart(2, '0') + '</td>' +
            '<td class="px-2 py-2 text-[0.85rem] text-slate-800">' + escHtml(data.catNo) + '</td>' +
            '<td class="px-2 py-2 text-[0.85rem] text-slate-800">' + escHtml(data.productName) + '</td>' +
            '<td class="px-2 py-2 text-[0.85rem] text-slate-800">' + escHtml(data.packSize) + '</td>' +
            '<td class="row-qty-val px-2 py-2 text-center text-[0.85rem] text-slate-800">' + data.qty + '</td>' +
            '<td class="row-rate-val px-2 py-2 text-right text-[0.85rem] text-slate-800">' + formatNum(data.rate) + '</td>' +
            '<td class="row-value px-2 py-2 text-right text-[0.85rem] text-slate-600">' + formatNum(value) + '</td>' +
            '<td class="row-gst-val px-2 py-2 text-center text-[0.85rem] text-slate-800">' + data.gst + '</td>' +
            '<td class="row-total px-2 py-2 text-right text-sm font-bold text-slate-800">' + formatNum(total) + '</td>' +
            '<td class="px-2 py-2 text-center"><button type="button" class="del-row-btn inline-flex h-[30px] w-[30px] items-center justify-center rounded-lg border border-rose-200 text-rose-600 transition-colors hover:bg-rose-50 cursor-pointer"><svg class="h-[15px] w-[15px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></td>';

        // Store data attributes for calc
        tr.setAttribute('data-product-id', data.productId || '');
        tr.setAttribute('data-variant-id', data.variantId || '');
        tr.setAttribute('data-qty', data.qty);
        tr.setAttribute('data-rate', data.rate);
        tr.setAttribute('data-gst', data.gst);

        tableBody.appendChild(tr);

        // Delete button
        tr.querySelector('.del-row-btn').addEventListener('click', function () {
            tr.remove();
            renumberRows();
            recalcTotals();
            toggleEmptyMsg();
        });

        renumberRows();
        recalcTotals();
        toggleEmptyMsg();
    }

    function escHtml(str) {
        var div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function renumberRows() {
        tableBody.querySelectorAll('.product-row').forEach(function (r, i) {
            r.querySelector('.sno').textContent = String(i + 1).padStart(2, '0');
        });
    }

    function toggleEmptyMsg() {
        var rows = tableBody.querySelectorAll('.product-row');
        emptyMsg.classList.toggle('hidden', rows.length > 0);
    }

    function recalcTotals() {
        var subtotal = 0;
        var gstTotal = 0;
        tableBody.querySelectorAll('.product-row').forEach(function (row) {
            var qty = parseFloat(row.getAttribute('data-qty')) || 0;
            var rate = parseFloat(row.getAttribute('data-rate')) || 0;
            var gstPct = parseFloat(row.getAttribute('data-gst')) || 0;
            var val = qty * rate;
            subtotal += val;
            gstTotal += val * gstPct / 100;
        });

        var freight = parseFloat(document.getElementById('freightCharges').value) || 0;
        var freightTax = freight * 0.18;
        var rawTotal = subtotal + gstTotal + freight + freightTax;
        var rounded = Math.round(rawTotal);
        var roundOff = rounded - rawTotal;

        document.getElementById('sumSubtotal').textContent = '\u20B9 ' + formatNum(subtotal);
        document.getElementById('sumGst').textContent = '\u20B9 ' + formatNum(gstTotal);
        document.getElementById('sumFreightTax').textContent = '\u20B9 ' + formatNum(freightTax);
        document.getElementById('sumRoundOff').textContent = '\u20B9 ' + (roundOff >= 0 ? '' : '-') + formatNum(Math.abs(roundOff));
        document.getElementById('sumGrandTotal').textContent = formatNum(rounded);
        document.getElementById('sumAmountWords').textContent = numberToWords(rounded) + ' Only';
    }

    function formatNum(n) {
        return Number(n).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function numberToWords(num) {
        if (num === 0) return 'Zero';
        var ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        var tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        function convert(n) {
            if (n < 0) return 'Minus ' + convert(-n);
            if (n < 20) return ones[n];
            if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 ? ' ' + ones[n % 10] : '');
            if (n < 1000) return ones[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + convert(n % 100) : '');
            if (n < 100000) return convert(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + convert(n % 1000) : '');
            if (n < 10000000) return convert(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + convert(n % 100000) : '');
            return convert(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + convert(n % 10000000) : '');
        }
        return convert(Math.abs(Math.round(num)));
    }

    // Modal logic
    var modal = document.getElementById('addProductModal');
    
    var backdrop = document.getElementById('modalBackdrop');
    var dialog = document.getElementById('modalDialog');
    var closeBtn = document.getElementById('modalCloseBtn');
    var cancelBtn = document.getElementById('modalCancelBtn');
    var addBtn = document.getElementById('modalAddBtn');

    var mCatNo = document.getElementById('modalCatNo');
    var mProdName = document.getElementById('modalProductName');
    var mProdNameField = document.getElementById('modalProductNameField');
    var mPackSize = document.getElementById('modalPackSize');
    var mQty = document.getElementById('modalQty');
    var mRate = document.getElementById('modalRate');
    var mGst = document.getElementById('modalGst');
    var mTotal = document.getElementById('modalTotalAmount');
    var selectedProductId = 0;
    var selectedVariantId = 0;
    var modalIsOpen = false;
    var closeTimer = null;

    function setProductNameError(hasError) {
        mProdNameField.classList.toggle('border-rose-400', hasError);
        mProdNameField.classList.toggle('ring-1', hasError);
        mProdNameField.classList.toggle('ring-rose-200', hasError);
    }

    function openModal() {
        mCatNo.value = '';
        mProdName.value = '';
        mPackSize.value = '';
        mQty.value = '1';
        mRate.value = '0';
        mGst.value = '18';
        selectedProductId = 0;
        selectedVariantId = 0;
        setProductNameError(false);
        updateModalTotal();

        clearTimeout(closeTimer);
        modal.classList.remove('hidden');
        modalIsOpen = true;
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(function () {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            dialog.classList.remove('opacity-0', 'scale-95', 'translate-y-[10px]');
            dialog.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        });
    }

    function closeModal() {
        if (!modalIsOpen && modal.classList.contains('hidden')) {
            return;
        }

        modalIsOpen = false;
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        dialog.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        dialog.classList.add('opacity-0', 'scale-95', 'translate-y-[10px]');
        document.body.classList.remove('overflow-hidden');
        clearTimeout(closeTimer);
        closeTimer = setTimeout(function () {
            if (!modalIsOpen) {
                modal.classList.add('hidden');
            }
        }, 300);
    }

    function updateModalTotal() {
        var qty = parseFloat(mQty.value) || 0;
        var rate = parseFloat(mRate.value) || 0;
        var gst = parseFloat(mGst.value) || 0;
        var value = qty * rate;
        var total = value + (value * gst / 100);
        mTotal.textContent = '\u20B9 ' + formatNum(total);
    }

    mQty.addEventListener('input', updateModalTotal);
    mRate.addEventListener('input', updateModalTotal);
    mGst.addEventListener('input', updateModalTotal);

    // ─── Inventory Product Search Logic ───
    var inventoryProductsData = document.getElementById('inventory-products-data');
    var inventoryProducts = [];
    if (inventoryProductsData) {
        try {
            inventoryProducts = JSON.parse(inventoryProductsData.textContent);
            console.log('AdminPI: Loaded ' + inventoryProducts.length + ' products for selection.');
        } catch(e) { 
            console.error('AdminPI: Failed to parse inventory data', e); 
        }
    }

    var catNoResults = document.getElementById('catNoSearchResults');
    var prodNameResults = document.getElementById('productNameSearchResults');
    
    function showProducts(query, targetField) {
        var isCatField = (targetField.id === 'modalCatNo');
        var resultsDiv = isCatField ? catNoResults : prodNameResults;
        var otherDiv = isCatField ? prodNameResults : catNoResults;
        
        otherDiv.classList.add('hidden');
        
        var q = (query || '').toLowerCase().trim();
        var matches = q === '' 
            ? inventoryProducts 
            : inventoryProducts.filter(p => p.searchString.includes(q));
            
        matches = matches.slice(0, 30);

        if (matches.length === 0) {
            resultsDiv.classList.add('hidden');
            return;
        }

        resultsDiv.innerHTML = '';
        matches.forEach(product => {
            var item = document.createElement('div');
            item.className = 'px-3 py-2 hover:bg-primary-50 cursor-pointer rounded-lg transition-colors border-b last:border-0 border-slate-50 group';
            
            if (isCatField) {
                // Show Category + Cat NO for the Cat NO field
                item.innerHTML = `
                    <div class="flex items-center justify-between pointer-events-none">
                        <span class="text-sm font-bold text-slate-700 group-hover:text-primary-700">${escHtml(product.catNo)}</span>
                        <span class="text-[10px] font-black uppercase text-slate-400 group-hover:text-primary-500">${escHtml(product.category)}</span>
                    </div>
                `;
            } else {
                // Show Product Name + Pack Size for the Product field
                item.innerHTML = `
                    <div class="flex items-center justify-between pointer-events-none">
                        <span class="text-sm font-bold text-slate-700 group-hover:text-primary-700">${escHtml(product.name)}</span>
                        <span class="text-[10px] font-medium text-slate-400 group-hover:text-primary-500">${escHtml(product.packSize)}</span>
                    </div>
                `;
            }

            item.addEventListener('mousedown', function(e) {
                e.preventDefault();
                mProdName.value = product.name;
                mCatNo.value = product.catNo;
                mPackSize.value = product.packSize;
                mRate.value = product.rate;
                mGst.value = product.gst;
                selectedProductId = parseInt(product.id, 10) || 0;
                selectedVariantId = parseInt(product.variantId, 10) || 0;
                
                resultsDiv.classList.add('hidden');
                updateModalTotal();
            });
            resultsDiv.appendChild(item);
        });

        resultsDiv.classList.remove('hidden');
    }

    mProdName.addEventListener('focus', function() { showProducts(mProdName.value, mProdName); });
    mProdName.addEventListener('input', function() {
        selectedProductId = 0;
        selectedVariantId = 0;
        showProducts(mProdName.value, mProdName);
    });
    
    mCatNo.addEventListener('focus', function() { showProducts(mCatNo.value, mCatNo); });
    mCatNo.addEventListener('input', function() {
        selectedProductId = 0;
        selectedVariantId = 0;
        showProducts(mCatNo.value, mCatNo);
    });

    // Close search results on outside click or blur
    document.addEventListener('mousedown', function(e) {
        if (!mProdName.contains(e.target) && !mCatNo.contains(e.target) && !catNoResults.contains(e.target) && !prodNameResults.contains(e.target)) {
            catNoResults.classList.add('hidden');
            prodNameResults.classList.add('hidden');
        }
    });

    document.getElementById('addProductRow').addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modalIsOpen) closeModal(); });

    addBtn.addEventListener('click', function () {
        var catNo = mCatNo.value.trim();
        var productName = mProdName.value.trim();
        var qty = parseFloat(mQty.value) || 1;
        var rate = parseFloat(mRate.value) || 0;
        var gst = parseFloat(mGst.value) || 0;
        var packSize = mPackSize.value.trim();

        if (!productName) {
            setProductNameError(true);
            mProdName.focus();
            return;
        }
        setProductNameError(false);

        addRowToTable({
            productId: selectedProductId,
            variantId: selectedVariantId,
            catNo: catNo,
            productName: productName,
            packSize: packSize,
            qty: qty,
            rate: rate,
            gst: gst
        });

        closeModal();
    });

    // ─── Freight input recalc ───
    document.getElementById('freightCharges').addEventListener('input', recalcTotals);

    // ─── Approve / Reject Handlers ───
    function showPiToast(msg, type) {
        if (window.AdminToast) window.AdminToast.show(msg, type);
        else alert(msg);
    }

    // Terms & Conditions manager
    var termsList = document.getElementById('termsList');
    var openTermComposerBtn = document.getElementById('openTermComposerBtn');
    var termComposerPanel = document.getElementById('termComposerPanel');
    var newTermInput = document.getElementById('newTermInput');
    var addTermBtn = document.getElementById('addTermBtn');
    var saveTermsBtn = document.getElementById('saveTermsBtn');
    var hiddenTermsInput = document.getElementById('hidden_terms');
    var defaultSaveTermsMarkup = saveTermsBtn ? saveTermsBtn.innerHTML : '';
    var saveTermsFlashTimer = null;

    function openTermComposer() {
        if (!termComposerPanel) return;

        termComposerPanel.classList.remove('hidden');
        openTermComposerBtn?.classList.add('hidden');

        if (newTermInput) {
            requestAnimationFrame(function () {
                newTermInput.focus();
            });
        }
    }

    function normalizeTermValue(value) {
        return String(value || '')
            .replace(/\r\n|\r|\n/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function autoResizeTermInput(termInput) {
        if (!termInput) return;

        termInput.style.height = 'auto';
        termInput.style.height = Math.max(termInput.scrollHeight, 72) + 'px';
    }

    function refreshTermDisplay(termItem) {
        if (!termItem) return '';

        var termInput = termItem.querySelector('[data-term-input]');
        var termText = termItem.querySelector('[data-term-text]');

        if (!termInput || !termText) return '';

        var normalizedValue = normalizeTermValue(termInput.value);
        termInput.value = normalizedValue;
        termText.textContent = normalizedValue || 'Add a term...';
        termText.classList.toggle('italic', normalizedValue === '');
        termText.classList.toggle('text-slate-400', normalizedValue === '');
        termText.classList.toggle('font-medium', normalizedValue !== '');
        termText.classList.toggle('text-slate-700', normalizedValue !== '');
        autoResizeTermInput(termInput);

        return normalizedValue;
    }

    function exitTermEditMode(termItem) {
        if (!termItem) return;

        var termText = termItem.querySelector('[data-term-text]');
        var termInput = termItem.querySelector('[data-term-input]');
        var deleteBtn = termItem.querySelector('.term-delete-btn');
        var editBtn = termItem.querySelector('.term-edit-btn');

        refreshTermDisplay(termItem);
        termItem.dataset.editing = 'false';
        termItem.classList.remove('border-primary-200', 'shadow-primary-100');
        termText?.classList.remove('hidden');
        termInput?.classList.add('hidden');
        deleteBtn?.classList.add('hidden');
        deleteBtn?.classList.remove('inline-flex');
        editBtn?.classList.remove('bg-primary-50', 'text-primary-600');
    }

    function closeOtherTermEditors(activeTermItem) {
        if (!termsList) return;

        termsList.querySelectorAll('[data-term-item]').forEach(function (termItem) {
            if (termItem !== activeTermItem && termItem.dataset.editing === 'true') {
                exitTermEditMode(termItem);
            }
        });
    }

    function enterTermEditMode(termItem) {
        if (!termItem) return;

        var termText = termItem.querySelector('[data-term-text]');
        var termInput = termItem.querySelector('[data-term-input]');
        var deleteBtn = termItem.querySelector('.term-delete-btn');
        var editBtn = termItem.querySelector('.term-edit-btn');

        closeOtherTermEditors(termItem);
        refreshTermDisplay(termItem);
        termItem.dataset.editing = 'true';
        termItem.classList.add('border-primary-200', 'shadow-primary-100');
        termText?.classList.add('hidden');
        termInput?.classList.remove('hidden');
        deleteBtn?.classList.remove('hidden');
        deleteBtn?.classList.add('inline-flex');
        editBtn?.classList.add('bg-primary-50', 'text-primary-600');
        autoResizeTermInput(termInput);

        if (termInput) {
            requestAnimationFrame(function () {
                termInput.focus();
                termInput.select();
            });
        }
    }

    function finalizeAllTermEdits() {
        if (!termsList) return;

        termsList.querySelectorAll('[data-term-item]').forEach(function (termItem) {
            exitTermEditMode(termItem);
        });
    }

    function renumberTerms() {
        if (!termsList) return;

        termsList.querySelectorAll('[data-term-item]').forEach(function (item, index) {
            var numberEl = item.querySelector('[data-term-number]');
            if (numberEl) {
                numberEl.textContent = (index + 1) + '.';
            }
        });
    }

    function syncTermsHiddenField() {
        if (!termsList || !hiddenTermsInput) return [];

        var termValues = Array.from(termsList.querySelectorAll('[data-term-input]'))
            .map(function(input) {
                input.value = normalizeTermValue(input.value);
                return input.value;
            })
            .filter(function(value) { return value !== ''; });

        hiddenTermsInput.value = termValues.join('\n');
        return termValues;
    }

    function flashTermsSavedState() {
        if (!saveTermsBtn) return;

        clearTimeout(saveTermsFlashTimer);
        saveTermsBtn.innerHTML = '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Saved';
        saveTermsBtn.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
        saveTermsBtn.classList.add('border-emerald-600', 'bg-emerald-600', 'text-white');

        saveTermsFlashTimer = setTimeout(function () {
            saveTermsBtn.innerHTML = defaultSaveTermsMarkup;
            saveTermsBtn.classList.remove('border-emerald-600', 'bg-emerald-600', 'text-white');
            saveTermsBtn.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
        }, 1400);
    }

    function wireTermItem(termItem) {
        if (!termItem) return;

        termItem.dataset.editing = termItem.dataset.editing || 'false';

        var input = termItem.querySelector('[data-term-input]');
        var editBtn = termItem.querySelector('.term-edit-btn');
        var deleteBtn = termItem.querySelector('.term-delete-btn');

        refreshTermDisplay(termItem);

        input?.addEventListener('input', function () {
            autoResizeTermInput(input);
            syncTermsHiddenField();
        });

        input?.addEventListener('blur', function () {
            syncTermsHiddenField();
        });

        input?.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                exitTermEditMode(termItem);
                syncTermsHiddenField();
            }
        });

        editBtn?.addEventListener('click', function () {
            if (termItem.dataset.editing === 'true') {
                exitTermEditMode(termItem);
            } else {
                enterTermEditMode(termItem);
            }

            syncTermsHiddenField();
        });

        deleteBtn?.addEventListener('click', function () {
            termItem.remove();
            renumberTerms();
            syncTermsHiddenField();
        });
    }

    function createTermItem(termValue) {
        var termItem = document.createElement('li');
        termItem.setAttribute('data-term-item', '');
        termItem.dataset.editing = 'false';
        termItem.className = 'group rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm transition';

        var row = document.createElement('div');
        row.className = 'flex items-start gap-3';

        var numberEl = document.createElement('span');
        numberEl.setAttribute('data-term-number', '');
        numberEl.className = 'mt-0.5 shrink-0 text-[13px] font-black text-primary-600';
        numberEl.textContent = '0.';

        var content = document.createElement('div');
        content.className = 'min-w-0 flex-1';

        var textEl = document.createElement('p');
        textEl.setAttribute('data-term-text', '');
        textEl.className = 'whitespace-normal break-words text-sm font-medium leading-6 text-slate-700';

        var input = document.createElement('textarea');
        input.setAttribute('data-term-input', '');
        input.rows = 2;
        input.value = termValue || '';
        input.placeholder = 'Enter term or condition...';
        input.className = 'mt-2 hidden min-h-[72px] w-full resize-none rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600';

        var actions = document.createElement('div');
        actions.className = 'flex shrink-0 items-start gap-1';

        var editBtn = document.createElement('button');
        editBtn.type = 'button';
        editBtn.className = 'term-edit-btn inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-primary-50 hover:text-primary-600 cursor-pointer';
        editBtn.setAttribute('aria-label', 'Edit term');
        editBtn.innerHTML = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>';

        var deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'term-delete-btn hidden h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 cursor-pointer';
        deleteBtn.setAttribute('aria-label', 'Delete term');
        deleteBtn.innerHTML = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

        content.appendChild(textEl);
        content.appendChild(input);
        actions.appendChild(editBtn);
        actions.appendChild(deleteBtn);
        row.appendChild(numberEl);
        row.appendChild(content);
        row.appendChild(actions);
        termItem.appendChild(row);

        wireTermItem(termItem);
        return termItem;
    }

    function appendTermItem(termValue) {
        if (!termsList) return null;

        finalizeAllTermEdits();

        var termItem = createTermItem(termValue);
        termsList.appendChild(termItem);
        renumberTerms();
        syncTermsHiddenField();
        return termItem;
    }

    openTermComposerBtn?.addEventListener('click', openTermComposer);

    addTermBtn?.addEventListener('click', function () {
        var draftTerm = newTermInput ? newTermInput.value.trim() : '';
        if (!draftTerm) {
            newTermInput?.focus();
            return;
        }

        appendTermItem(draftTerm);

        if (newTermInput) {
            newTermInput.value = '';
        }
    });

    newTermInput?.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter') return;

        event.preventDefault();
        addTermBtn?.click();
    });

    saveTermsBtn?.addEventListener('click', function () {
        finalizeAllTermEdits();
        syncTermsHiddenField();
        flashTermsSavedState();
        showPiToast('Terms saved successfully.', 'success');
    });

    termsList?.querySelectorAll('[data-term-item]').forEach(wireTermItem);
    renumberTerms();
    syncTermsHiddenField();

    // Collect all product rows into JSON before submitting the form.
    function collectAndSubmitForm(statusValue, submitActionValue) {
        var rows = tableBody.querySelectorAll('.product-row');
        var items = [];
        rows.forEach(function(row) {
            items.push({
                productId:   parseInt(row.getAttribute('data-product-id')) || 0,
                variantId:   parseInt(row.getAttribute('data-variant-id')) || 0,
                catNo:       row.cells[1].textContent.trim(),
                productName: row.cells[2].textContent.trim(),
                packSize:    row.cells[3].textContent.trim(),
                qty:         parseFloat(row.getAttribute('data-qty')) || 0,
                rate:        parseFloat(row.getAttribute('data-rate')) || 0,
                gst:         parseFloat(row.getAttribute('data-gst')) || 0
            });
        });
        document.getElementById('hidden_items_json').value = JSON.stringify(items);

        // Collect terms from the editable term inputs.
        finalizeAllTermEdits();
        syncTermsHiddenField();

        // Set the approval/rejection status.
        document.getElementById('hidden_status').value = statusValue;

        // Set the requested next action for the backend flow.
        document.getElementById('hidden_submit_action').value = submitActionValue || 'save';

        // Submit the PI form to the backend.
        if (typeof piForm.requestSubmit === 'function') {
            piForm.requestSubmit();
            return;
        }

        piForm.submit();
    }

    document.getElementById('approvePiTopBtn')?.addEventListener('click', function() { collectAndSubmitForm('approved', 'save'); });
    document.getElementById('rejectPiTopBtn')?.addEventListener('click', function() { collectAndSubmitForm('rejected', 'save'); });

    document.getElementById('sendEmailBtn')?.addEventListener('click', function () {
        var customerEmail = customerEmailInput.value.trim();
        var currentStatus = document.getElementById('hidden_status').value || 'draft';

        if (!customerEmail) {
            showPiToast('Enter customer email before sending PI.', 'info');
            customerEmailInput.focus();
            return;
        }

        collectAndSubmitForm(currentStatus, 'send_email');
    });

    document.getElementById('generatePdfBtn')?.addEventListener('click', function () {
        var currentStatus = document.getElementById('hidden_status').value || 'draft';

        collectAndSubmitForm(currentStatus, 'download_pdf');
    });

    // Load existing product rows when editing an existing PI.
    if (typeof preloadedItems !== 'undefined' && preloadedItems.length > 0) {
        preloadedItems.forEach(function(item) { addRowToTable(item); });
    }

    toggleEmptyMsg();
    updateModalTotal();
    recalcTotals();
    syncShippingToggleState();
})();
</script>

</form>

@endsection

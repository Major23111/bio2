@extends('admin.layout')
@section('title', 'Map Pricing — ' . ($variant->product?->name ?? 'Product'))
@section('admin_content')

<div class="space-y-6 max-w-[1200px] mx-auto pb-10 mt-2">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pricing.index') }}" class="ajax-link inline-flex items-center justify-center h-9 w-9 rounded-lg border border-slate-200 bg-white text-slate-500 hover:text-primary-700 hover:border-primary-300 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-[22px] font-extrabold text-[var(--ui-text)] tracking-tight">Pricing Management</h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative w-64 shadow-sm border border-slate-200/60 rounded-lg overflow-hidden flex items-center bg-white group hover:border-slate-300 transition">
                <svg class="h-4 w-4 text-slate-400 absolute left-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search products or SKU..." class="w-full pl-10 pr-4 py-2 bg-transparent text-[13px] font-medium text-slate-700 outline-none placeholder:text-slate-400">
            </div>
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-[12px] font-bold text-slate-600 hover:bg-slate-50 transition">
                Export CSV
            </button>
        </div>
    </div>

    {{-- Product Header --}}
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
            <div>
                <p class="text-[10px] font-black text-primary-600 tracking-widest uppercase mb-1">SELECTED PRODUCT</p>
                <h1 class="text-[22px] font-extrabold text-[var(--ui-text)] tracking-tight uppercase">{{ $variant->product?->name ?? 'Unknown Product' }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="inline-flex items-center bg-slate-100 text-[11px] font-bold text-slate-600 px-3 py-1 rounded-md tracking-wide">CAT NO. {{ $variant->catalog_number ?? $variant->sku }}</span>
                    @if($variant->product?->category)
                        <span class="text-[12px] text-slate-400 font-medium">• {{ $variant->product->category->name }}</span>
                    @endif
                </div>
            </div>
            {{-- Pack Size Tabs --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                @php
                    $packSizes = ['15 ML', '30 ML', '60 ML'];
                    $currentIndex = 0; // Default to first tab active
                    // If there are sibling variants, map them to tabs
                    if ($siblingVariants->count() > 1) {
                        $currentIndex = $siblingVariants->search(fn($s) => $s->id === $variant->id);
                    }
                @endphp
                @foreach($packSizes as $idx => $size)
                    @if($idx === $currentIndex || ($siblingVariants->count() <= 1 && $idx === 0))
                        <span class="px-5 py-2 rounded-lg bg-primary-600 text-white text-[12px] font-extrabold tracking-wide shadow-md cursor-default">{{ $size }}</span>
                    @elseif($siblingVariants->count() > 1 && isset($siblingVariants[$idx]))
                        <a href="{{ route('admin.pricing.map-price.form', ['variant_id' => $siblingVariants[$idx]->id]) }}" class="ajax-link px-5 py-2 rounded-lg border border-slate-200 bg-white text-[12px] font-bold text-slate-600 hover:border-primary-300 hover:text-primary-700 transition">{{ $size }}</a>
                    @else
                        <span class="px-5 py-2 rounded-lg border border-slate-200 bg-white text-[12px] font-bold text-slate-600 cursor-default">{{ $size }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Core Pricing + Discount Strategy --}}
    <form id="corePricingForm" action="{{ route('admin.pricing.map-price.save') }}" method="POST">
        @csrf
        <input type="hidden" name="variant_id" value="{{ $variant->id }}">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            {{-- Core Pricing Structure (3 cols) --}}
            <div class="lg:col-span-3 bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
                <h2 class="text-[13px] font-extrabold text-[var(--ui-text)] tracking-tight uppercase flex items-center gap-2 mb-6">
                    <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    CORE PRICING STRUCTURE ({{ strtoupper($variant->variant_name) }})
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">GUEST PRICE (MRP)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[13px]">₹</span>
                            <input type="number" step="0.01" name="base_price" id="basePriceInput" value="{{ $basePrice ? number_format((float)$basePrice->amount, 2, '.', '') : '' }}" required placeholder="0.00" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2B BASE PRICE</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[13px]">₹</span>
                            <input type="number" step="0.01" name="b2b_price" id="b2bPriceInput" value="{{ $b2bPrice ? number_format((float)$b2bPrice->amount, 2, '.', '') : '' }}" required placeholder="0.00" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2C PRICE MARGIN (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="b2c_percentage" id="b2cMarginInput" value="{{ $b2cPrice && $b2bPrice && (float)$b2bPrice->amount > 0 ? number_format((((float)$b2cPrice->amount - (float)$b2bPrice->amount) / (float)$b2bPrice->amount) * 100, 2, '.', '') : '' }}" required placeholder="e.g., 15" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-10 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5 flex items-center gap-1.5">
                            B2C CALCULATED PRICE
                            <span id="b2cTooltip" class="hidden text-[9px] font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded-full"></span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[13px]">₹</span>
                            <input type="text" id="b2cCalcDisplay" value="{{ $b2cPrice ? number_format((float)$b2cPrice->amount, 2) : '—' }}" readonly class="h-12 w-full rounded-xl border border-slate-200 bg-slate-100 pl-8 pr-4 text-[14px] font-extrabold text-slate-800 outline-none cursor-not-allowed">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Discount Strategy (2 cols) --}}
            <div class="lg:col-span-2 bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
                <h2 class="text-[13px] font-extrabold text-[var(--ui-text)] tracking-tight uppercase flex items-center gap-2 mb-6">
                    <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    DISCOUNT STRATEGY
                </h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">TARGET AUDIENCE</label>
                        <select name="apply_discount_to" class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-4 text-[13px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600 cursor-pointer">
                            <option value="B2C" {{ old('apply_discount_to', ($b2cPrice->DiscountType ?? '') === 'percent' ? 'B2C' : '') === 'B2C' ? 'selected' : '' }}>B2C</option>
                            <option value="B2B" {{ old('apply_discount_to') === 'B2B' ? 'selected' : '' }}>B2B</option>
                            <option value="Both B2C and B2B" {{ old('apply_discount_to') === 'Both B2C and B2B' ? 'selected' : '' }}>Both (B2B & B2C)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">VALUE (%)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="discount_percentage" value="{{ $b2cPrice ? number_format((float)($b2cPrice->Discount ?? 0), 2, '.', '') : '0' }}" placeholder="0" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-8 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">MAX CAP (₹)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[13px]">₹</span>
                                <input type="number" step="0.01" placeholder="50" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="w-full mt-4 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white py-3 text-[11px] font-extrabold tracking-widest uppercase text-slate-700 hover:bg-slate-50 transition">
                        APPLY GLOBAL DISCOUNT
                    </button>
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.pricing.index') }}" class="ajax-link inline-flex items-center px-6 py-3 rounded-xl border border-slate-200 bg-white text-[13px] font-bold text-slate-600 hover:bg-slate-50 transition">Discard Changes</a>
            <button type="submit" class="inline-flex items-center px-7 py-3 rounded-xl bg-primary-600 text-[13px] font-bold text-white shadow-md shadow-primary-600/20 hover:bg-primary-700 transition">Save All Configurations</button>
        </div>
    </form>

    {{-- Bulk Pricing Configuration --}}
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h2 class="text-[13px] font-extrabold text-[var(--ui-text)] tracking-tight uppercase flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.5 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm0 2a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/></svg>
                BULK PRICING CONFIGURATION
            </h2>
            <button type="button" id="addBulkSlabBtn" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-[#f8fafc] px-4 py-2 text-[12px] font-bold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                ADD BULK SLAB
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]" id="bulkPricingTable">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">QUANTITY RANGE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">UNIT PRICE (₹)</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">DISCOUNT APPLIED</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">ACTIVE STATUS</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400 text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="bulkSlabBody">
                    @php $basePriceVal = $basePrice ? (float)$basePrice->amount : 0; @endphp
                    @forelse($variant->bulkPrices as $bp)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 text-[13px] font-semibold text-slate-700">{{ $bp->min_quantity }}{{ $bp->max_quantity ? ' - ' . $bp->max_quantity : '+' }} Units</td>
                            <td class="py-4 text-[13px] font-bold text-slate-800">₹{{ number_format((float)$bp->amount, 2) }}</td>
                            <td class="py-4">
                                @if($basePriceVal > 0)
                                    <span class="text-[13px] font-bold text-emerald-600">{{ number_format((($basePriceVal - (float)$bp->amount) / $basePriceVal) * 100, 2) }}% Off</span>
                                @else
                                    <span class="text-[13px] text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="py-4"><span class="text-[10px] font-black tracking-widest uppercase {{ $bp->is_active ? 'text-emerald-600' : 'text-slate-400' }}">{{ $bp->is_active ? 'ACTIVE' : 'INACTIVE' }}</span></td>
                            <td class="py-4 text-right flex items-center justify-end gap-2">
                                <button type="button" class="text-slate-400 hover:text-primary-600 transition p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                <button type="button" class="text-slate-400 hover:text-red-500 transition p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-[13px] text-slate-400 font-medium">No bulk pricing slabs configured.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Company Specific Pricing --}}
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h2 class="text-[13px] font-extrabold text-[var(--ui-text)] tracking-tight uppercase flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                COMPANY SPECIFIC PRICING
            </h2>
            <button type="button" id="openCompanyPricingBtn" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-[12px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                ADD COMPANY SPECIFIC PRICING
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">COMPANY NAME</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">DEDICATED B2B PRICE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">PRIVATE BULK SLABS</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">CONTRACT END DATE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400 text-right">MANAGE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($companyPrices as $cp)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[11px] font-black text-slate-600">{{ strtoupper(substr($cp->company?->name ?? '?', 0, 2)) }}</div>
                                    <span class="text-[13px] font-semibold text-slate-800">{{ $cp->company?->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-[13px] font-extrabold text-slate-900">₹{{ number_format((float)$cp->amount, 2) }}</td>
                            <td class="py-4"><span class="text-[12px] font-medium text-slate-500">• No</span></td>
                            <td class="py-4 text-[12px] font-medium text-slate-500">—</td>
                            <td class="py-4 text-right"><span class="text-[11px] font-extrabold text-primary-800 hover:text-primary-600 cursor-pointer transition uppercase tracking-widest">MODIFY TERMS</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-[13px] text-slate-400 font-medium">No company specific pricing configured for this product.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Bulk Pricing Slab Modal --}}
<div id="addBulkSlabModal" class="fixed inset-0 z-[9999] hidden">
    <div id="addBulkSlabBackdrop" class="absolute inset-0 bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-300"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div id="addBulkSlabDialog" class="pointer-events-auto relative w-full max-w-[480px] translate-y-2 scale-95 opacity-0 overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,0.16)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]">

            <div class="flex items-start justify-between border-b border-slate-100 px-7 pb-5 pt-7">
                <div>
                    <h3 class="text-[17px] font-extrabold text-slate-900 tracking-tight leading-none mb-1">Add Bulk Pricing Slab</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">TIERED PRICING ARCHITECTURE</p>
                </div>
                <button type="button" onclick="closeBulkSlabModal()" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="bulkSlabForm" method="POST" class="px-7 py-6">
                @csrf
                <input type="hidden" name="variant_id" value="{{ $variant->id }}">

                <div id="bulkSlabRows">
                    <div class="bulk-slab-row space-y-4 mb-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">BULK PRICE (AMOUNT)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[13px]">₹</span>
                                <input type="number" step="0.01" name="slabs[0][amount]" placeholder="0.00" required class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">MINIMUM QUANTITY</label>
                                <input type="number" name="slabs[0][min_quantity]" placeholder="e.g. 50" required class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">MAXIMUM QUANTITY</label>
                                <input type="number" name="slabs[0][max_quantity]" placeholder="e.g. 500" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="addMoreSlabBtn" class="flex w-full justify-center items-center gap-2 rounded-xl border-[1.5px] border-dashed border-slate-200 py-3 text-[11px] font-extrabold tracking-widest uppercase text-slate-500 transition hover:text-slate-700 hover:bg-slate-50 hover:border-slate-300 mb-5">
                    <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Add More Bulk Slab
                </button>

                <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                    <button type="button" onclick="closeBulkSlabModal()" class="flex-1 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white py-3 text-[13px] font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-primary-600 py-3 text-[13px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">Save Pricing Slab</button>
                </div>

                <p class="mt-4 text-[11px] text-slate-400 font-medium flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 opacity-60" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    Changes will reflect in real-time across regional catalogs.
                </p>
            </form>
        </div>
    </div>
</div>

{{-- Add Company Specific Pricing Modal --}}
<div id="addCompanyPricingModal" class="fixed inset-0 z-[9998] hidden">
    <div id="addCompanyPricingBackdrop" class="absolute inset-0 bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-300"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div id="addCompanyPricingDialog" class="pointer-events-auto relative w-full max-w-[560px] translate-y-2 scale-95 opacity-0 overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,0.16)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[90vh] overflow-y-auto">

            <div class="flex items-start justify-between border-b border-slate-100 px-7 pb-5 pt-7">
                <div>
                    <h3 class="text-[17px] font-extrabold text-slate-900 tracking-tight leading-none mb-1">Add Company Specific Pricing</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase flex items-center gap-1.5">
                        <svg class="w-3 h-3 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                        CONFIGURE B2B EXCLUSIVE RATES
                    </p>
                </div>
                <button type="button" onclick="closeCompanyPricingModal()" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="companyPricingForm" action="{{ route('admin.pricing.company-price.save') }}" method="POST" class="px-7 py-6 space-y-5">
                @csrf
                <input type="hidden" name="variant_id" value="{{ $variant->id }}">

                {{-- Company Name Search --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2 flex items-center justify-between">
                        COMPANY NAME SEARCH
                        <svg class="w-3.5 h-3.5 text-primary-600 opacity-70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                        <input type="text" name="company_name" id="companySearchInput" placeholder="Start typing company name..." autocomplete="off" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                    </div>
                </div>

                {{-- Company Details (read-only) --}}
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">LEGAL BUSINESS NAME</label>
                        <input type="text" id="cpLegalName" readonly placeholder="—" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-100 px-3 text-[13px] font-medium text-slate-600 outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">COMPANY REG NUMBER</label>
                        <input type="text" id="cpRegNumber" readonly placeholder="—" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-100 px-3 text-[13px] font-medium text-slate-600 outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">GST NUMBER</label>
                        <input type="text" id="cpGstNumber" readonly placeholder="—" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-100 px-3 text-[13px] font-medium text-slate-600 outline-none cursor-not-allowed">
                    </div>
                </div>

                {{-- Pricing Fields --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">SPECIFIC B2B PRICE (AMOUNT)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[13px]">₹</span>
                            <input type="number" step="0.01" name="b2b_price" placeholder="0.00" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">EXCLUSIVE DISCOUNT (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="exclusive_discount" placeholder="0" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-8 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                </div>

                {{-- Volume-Based Slab Pricing --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="h-8 w-8 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-primary-700" fill="currentColor" viewBox="0 0 20 20"><path d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.5 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm0 2a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/></svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-extrabold text-slate-800">Volume-Based Slab Pricing</p>
                            <p class="text-[11px] text-slate-500 font-medium">Add tiered pricing for high-volume orders specific to this client.</p>
                        </div>
                    </div>
                    <button type="button" onclick="openBulkSlabModal()" class="flex w-full justify-center items-center gap-2 rounded-xl border border-slate-200 bg-white py-3 text-[11px] font-extrabold tracking-widest uppercase text-slate-600 transition hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300">
                        <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        Add Specific Bulk Pricing Slab
                    </button>
                </div>

                {{-- Warning Note --}}
                <div class="rounded-xl border border-amber-200/60 bg-amber-50/50 px-4 py-3 flex items-start gap-2.5">
                    <span class="text-amber-500 mt-0.5">⚠</span>
                    <p class="text-[11px] text-amber-800/80 font-medium italic leading-relaxed">This pricing is specifically for the selected company and will not be displayed to other customers.</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                    <button type="button" onclick="closeCompanyPricingModal()" class="flex-1 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white py-3 text-[13px] font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-primary-600 py-3 text-[13px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/></svg>
                        Save Pricing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const b2bInput = document.getElementById('b2bPriceInput');
    const marginInput = document.getElementById('b2cMarginInput');
    const calcDisplay = document.getElementById('b2cCalcDisplay');
    const tooltip = document.getElementById('b2cTooltip');

    function recalcB2C() {
        const b2b = parseFloat(b2bInput?.value) || 0;
        const margin = parseFloat(marginInput?.value) || 0;
        if (b2b > 0 && margin > 0) {
            const b2c = b2b + (b2b * margin / 100);
            calcDisplay.value = '₹' + b2c.toFixed(2);
            tooltip.textContent = 'B2B ₹' + b2b.toFixed(2) + ' + ' + margin + '% = ₹' + b2c.toFixed(2);
            tooltip.classList.remove('hidden');
        } else if (b2b > 0) {
            calcDisplay.value = '₹' + b2b.toFixed(2);
            tooltip.classList.add('hidden');
        } else {
            calcDisplay.value = '—';
            tooltip.classList.add('hidden');
        }
    }

    if (b2bInput) b2bInput.addEventListener('input', recalcB2C);
    if (marginInput) marginInput.addEventListener('input', recalcB2C);
    recalcB2C();

    // ─── Bulk Slab Modal ───
    const modal = document.getElementById('addBulkSlabModal');
    const backdrop = document.getElementById('addBulkSlabBackdrop');
    const dialog = document.getElementById('addBulkSlabDialog');

    window.openBulkSlabModal = function() {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                backdrop.classList.replace('opacity-0', 'opacity-100');
                dialog.classList.remove('opacity-0', 'scale-95', 'translate-y-2');
                dialog.classList.add('opacity-100', 'scale-100', 'translate-y-0');
            });
        });
    };

    window.closeBulkSlabModal = function() {
        backdrop.classList.replace('opacity-100', 'opacity-0');
        dialog.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        dialog.classList.add('opacity-0', 'scale-95', 'translate-y-2');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    };

    // Close on backdrop click
    backdrop.addEventListener('click', closeBulkSlabModal);

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeBulkSlabModal();
    });

    // Wire up the ADD BULK SLAB button
    const addBulkBtn = document.getElementById('addBulkSlabBtn');
    if (addBulkBtn) addBulkBtn.addEventListener('click', openBulkSlabModal);

    // ─── Company Pricing Modal ───
    const cpModal = document.getElementById('addCompanyPricingModal');
    const cpBackdrop = document.getElementById('addCompanyPricingBackdrop');
    const cpDialog = document.getElementById('addCompanyPricingDialog');

    window.openCompanyPricingModal = function() {
        cpModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                cpBackdrop.classList.replace('opacity-0', 'opacity-100');
                cpDialog.classList.remove('opacity-0', 'scale-95', 'translate-y-2');
                cpDialog.classList.add('opacity-100', 'scale-100', 'translate-y-0');
            });
        });
    };

    window.closeCompanyPricingModal = function() {
        cpBackdrop.classList.replace('opacity-100', 'opacity-0');
        cpDialog.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        cpDialog.classList.add('opacity-0', 'scale-95', 'translate-y-2');
        setTimeout(() => {
            cpModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    };

    cpBackdrop.addEventListener('click', closeCompanyPricingModal);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !cpModal.classList.contains('hidden')) closeCompanyPricingModal();
    });

    const openCpBtn = document.getElementById('openCompanyPricingBtn');
    if (openCpBtn) openCpBtn.addEventListener('click', openCompanyPricingModal);

    // ─── Add More Slab Rows ───
    let slabIndex = 1;
    const addMoreBtn = document.getElementById('addMoreSlabBtn');
    const slabContainer = document.getElementById('bulkSlabRows');

    if (addMoreBtn) {
        addMoreBtn.addEventListener('click', () => {
            const row = document.createElement('div');
            row.className = 'bulk-slab-row space-y-4 mb-5 pt-5 border-t border-slate-100';
            row.innerHTML = `
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">BULK PRICE (AMOUNT)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[13px]">₹</span>
                        <input type="number" step="0.01" name="slabs[${slabIndex}][amount]" placeholder="0.00" required class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">MINIMUM QUANTITY</label>
                        <input type="number" name="slabs[${slabIndex}][min_quantity]" placeholder="e.g. 50" required class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">MAXIMUM QUANTITY</label>
                        <input type="number" name="slabs[${slabIndex}][max_quantity]" placeholder="e.g. 500" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                    </div>
                </div>
            `;
            slabContainer.appendChild(row);
            slabIndex++;
            row.querySelector('input[type="number"]').focus();
        });
    }
})();
</script>
@endpush

@endsection


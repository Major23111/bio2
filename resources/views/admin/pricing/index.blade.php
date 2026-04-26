@extends('admin.layout')
@section('title', 'Pricing Management')
@section('admin_content')

<div class="space-y-6 max-w-[1200px] mx-auto pb-10 mt-2">

    <!-- Header & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div class="flex items-center gap-4">
            <h1 class="text-[22px] font-extrabold text-[var(--ui-text)] tracking-tight">Pricing Management</h1>
        </div>
        <div class="flex items-center">
            <div class="relative w-80 shadow-sm border border-slate-200/60 rounded-lg overflow-hidden flex items-center bg-white group hover:border-slate-300 transition">
                <svg class="h-4 w-4 text-slate-400 absolute left-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Search pricing matrices..." class="w-full bg-[#f8fafc] border-none text-[13px] font-medium text-slate-800 focus:bg-white pl-9 pr-4 py-2 outline-none placeholder:text-slate-400 transition">
            </div>
        </div>
    </div>

    <!-- Mapped Pricing Box -->
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-[19px] font-bold text-[var(--ui-text)] tracking-tight leading-none">Mapped Pricing</h2>
                <p class="text-[13px] text-slate-500 mt-1.5 align-middle">Products with base, B2C and B2B pricing configured</p>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 border border-slate-200 bg-white rounded-lg text-[13px] font-bold text-slate-700 flex items-center gap-2 hover:bg-slate-50 transition">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <button class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-[13px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">
                    <svg class="w-3.5 h-3.5 text-white/80" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    Export CSV
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">SKU / PRODUCT NAME</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">GUEST (BASE)</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">B2C RATE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">B2B RATE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400 text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($mappedProducts as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900">
                                <div class="text-[10px] text-slate-400 font-extrabold tracking-widest mb-1 leading-none uppercase">{{ $product['sku'] }}</div>
                                <div class="text-[13px] text-slate-800 font-bold leading-none">{{ $product['product_name'] }}</div>
                            </td>
                            <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">
                                {{ $product['base_price'] !== null ? '₹' . number_format($product['base_price'], 2) : '—' }}
                            </td>
                            <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">
                                {{ $product['b2c_price'] !== null ? '₹' . number_format($product['b2c_price'], 2) : '—' }}
                            </td>
                            <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">
                                {{ $product['b2b_price'] !== null ? '₹' . number_format($product['b2b_price'], 2) : '—' }}
                            </td>
                            <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900 text-right">
                                <button type="button" data-pricing-modal-open="editProductModal" data-variant-id="{{ $product['variant_id'] }}" class="text-slate-400 hover:text-primary-600 transition p-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[13px] text-slate-400 font-medium">No products with mapped pricing yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mappedProducts->hasPages())
            <div class="px-6 lg:px-8 py-4 border-t border-slate-100">
                {{ $mappedProducts->links() }}
            </div>
        @endif
    </div>

    <!-- Unmapped Products Box -->
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex items-center gap-3.5 mb-8">
            <h2 class="text-[19px] font-bold text-[var(--ui-text)] tracking-tight leading-none">Unmapped Products</h2>
            @if ($unmappedProducts->total() > 0)
                <span class="bg-red-50 text-[#e11d48] px-2.5 py-1 rounded-[4px] text-[9px] font-bold tracking-widest uppercase border border-red-100/50">
                    {{ $unmappedProducts->total() }} Pending Configuration
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">PRODUCT NAME</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">CAT NO.</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">DATE ADDED</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400 text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($unmappedProducts as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 text-[13px] font-semibold text-slate-800">{{ $product['product_name'] }}</td>
                            <td class="py-4 text-[12px] font-medium text-slate-500 tracking-wide uppercase font-mono">{{ $product['catalog_number'] }}</td>
                            <td class="py-4 text-[12px] font-medium text-slate-500">{{ $product['date_added'] }}</td>
                            <td class="py-4 text-right">
                                <a href="{{ route('admin.pricing.map-price.form', ['variant_id' => $product['variant_id']]) }}" class="ajax-link text-[11px] font-extrabold text-primary-800 hover:text-primary-600 transition uppercase tracking-widest">MAP PRICING &rsaquo;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-[13px] text-slate-400 font-medium">All products have pricing configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($unmappedProducts->hasPages())
            <div class="px-6 lg:px-8 py-4 border-t border-slate-100">
                {{ $unmappedProducts->links() }}
            </div>
        @endif
    </div>


</div>


{{-- MODALS --}}
@include('admin.pricing.modals.all-modals')

@endsection

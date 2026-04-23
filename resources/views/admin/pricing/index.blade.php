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
                <p class="text-[13px] text-slate-500 mt-1.5 align-middle">Global price architecture for cobalt derivatives</p>
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
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900">
                            <div class="text-[10px] text-slate-400 font-extrabold tracking-widest mb-1 leading-none uppercase">CO-99.9-IND</div>
                            <div class="text-[13px] text-slate-800 font-bold leading-none">Industrial Cobalt Sulfate</div>
                        </td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹1,180.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹1,530.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹1,060.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900 text-right">
                            <button type="button" data-pricing-modal-open="editProductModal" class="text-slate-400 hover:text-primary-600 transition p-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900">
                            <div class="text-[10px] text-slate-400 font-extrabold tracking-widest mb-1 leading-none uppercase">CO-PH-99</div>
                            <div class="text-[13px] text-slate-800 font-bold leading-none">Pharma-Grade Cobalt Chloride</div>
                        </td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹1,865.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹2,400.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹1,590.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900 text-right">
                            <button type="button" data-pricing-modal-open="editProductModal" class="text-slate-400 hover:text-primary-600 transition p-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900">
                            <div class="text-[10px] text-slate-400 font-extrabold tracking-widest mb-1 leading-none uppercase">CO-MET-LOW</div>
                            <div class="text-[13px] text-slate-800 font-bold leading-none">Low-Density Cobalt Metal</div>
                        </td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹2,580.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹2,820.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-700">₹2,200.00/kg</td>
                        <td class="py-5 border-b border-slate-50 text-[13px] font-semibold text-slate-900 text-right">
                            <button type="button" data-pricing-modal-open="editProductModal" class="text-slate-400 hover:text-primary-600 transition p-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition border-b-0">
                        <td class="py-5 text-[13px] font-semibold text-slate-900">
                            <div class="text-[10px] text-slate-400 font-extrabold tracking-widest mb-1 leading-none uppercase">CO-BIO-ACC</div>
                            <div class="text-[13px] text-slate-800 font-bold leading-none">Biogenic Accelerator Pack</div>
                        </td>
                        <td class="py-5 text-[13px] font-semibold text-slate-700">₹9,300.00/u</td>
                        <td class="py-5 text-[13px] font-semibold text-slate-700">₹12,030.00/u</td>
                        <td class="py-5 text-[13px] font-semibold text-slate-700">₹8,130.00/u</td>
                        <td class="py-5 text-[13px] font-semibold text-slate-900 text-right">
                            <button type="button" data-pricing-modal-open="editProductModal" class="text-slate-400 hover:text-primary-600 transition p-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Unmapped Products Box -->
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex items-center gap-3.5 mb-8">
            <h2 class="text-[19px] font-bold text-[var(--ui-text)] tracking-tight leading-none">Unmapped Products</h2>
            <span class="bg-red-50 text-[#e11d48] px-2.5 py-1 rounded-[4px] text-[9px] font-bold tracking-widest uppercase border border-red-100/50">4 Pending Configuration</span>
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
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 text-[13px] font-semibold text-slate-800">Recycled Cobalt Slag</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500 tracking-wide uppercase font-mono">SLAG-D8293</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500">2023-10-24</td>
                        <td class="py-4 text-right">
                            <button type="button" data-pricing-modal-open="mapPricingModal" class="text-[11px] font-extrabold text-primary-800 hover:text-primary-600 transition uppercase tracking-widest">MAP PRICING &rsaquo;</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 text-[13px] font-semibold text-slate-800">Isotope Sample Alpha</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500 tracking-wide uppercase font-mono">ISO-ALP-1</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500">2023-10-22</td>
                        <td class="py-4 text-right">
                            <button type="button" data-pricing-modal-open="mapPricingModal" class="text-[11px] font-extrabold text-primary-800 hover:text-primary-600 transition uppercase tracking-widest">MAP PRICING &rsaquo;</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 text-[13px] font-semibold text-slate-800">Cobalt Acetate Liquid</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500 tracking-wide uppercase font-mono">LIQ-ACE-44</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500">2023-10-21</td>
                        <td class="py-4 text-right">
                            <button type="button" data-pricing-modal-open="mapPricingModal" class="text-[11px] font-extrabold text-primary-800 hover:text-primary-600 transition uppercase tracking-widest">MAP PRICING &rsaquo;</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 text-[13px] font-semibold text-slate-800">Crude Ore Bulk</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500 tracking-wide uppercase font-mono">ORE-CRU-B</td>
                        <td class="py-4 text-[12px] font-medium text-slate-500">2023-10-18</td>
                        <td class="py-4 text-right">
                            <button type="button" data-pricing-modal-open="mapPricingModal" class="text-[11px] font-extrabold text-primary-800 hover:text-primary-600 transition uppercase tracking-widest">MAP PRICING &rsaquo;</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Discount Slabs Box -->
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-[19px] font-bold text-[var(--ui-text)] tracking-tight leading-none">Discount Slabs</h2>
                <p class="text-[13px] text-slate-500 mt-1.5 align-middle">Volume-based tiering rules</p>
            </div>
            <button type="button" data-pricing-modal-open="bulkPricingModal" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13px] font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-primary-800">
                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Discount Slab
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">PRODUCT RANGE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">MIN QTY RATE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">MID QTY RATE</th>
                        <th class="pb-3 text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">MAX QTY RATE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 text-[13px] font-semibold text-slate-800">Sulfate Series</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹450</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹1,000</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹1,500</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 text-[13px] font-semibold text-slate-800">Metallic Base</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹250</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹650</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹1,200</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 text-[13px] font-semibold text-slate-800">Accelerator Packs</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹850</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹1,700</td>
                        <td class="py-4 text-[13px] font-medium text-slate-500">₹3,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Company Specific Pricing Box -->
    <div class="bg-[var(--ui-surface)] rounded-[16px] shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row justify-between lg:items-center mb-8 gap-6">
            <div>
                <h2 class="text-[19px] font-bold text-slate-900 tracking-tight leading-none">Company Specific Pricing</h2>
                <p class="text-[13px] text-slate-500 mt-1.5 align-middle">Custom B2B rates and negotiated slabs for key accounts</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative border border-slate-200/80 rounded-[8px] overflow-hidden flex items-center bg-white hover:border-slate-300 transition">
                    <svg class="h-4 w-4 text-slate-400 absolute left-3 flex-shrink-0 bg-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <input type="text" placeholder="Search Company Name..." class="w-full sm:w-64 pl-9 pr-4 py-2 border-none outline-none text-[13px] placeholder:text-slate-400 focus:bg-[#f8fafc] transition shadow-inner shadow-white">
                </div>
                <button type="button" data-pricing-modal-open="companyPricingModal" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-primary-600 px-5 py-2.5 text-[13px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    Add Company Pricing
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-8">
            
            <!-- Card 1 -->
            <div class="border border-slate-100 rounded-[12px] bg-[#fcfdfd] p-6 relative group hover:border-slate-200 transition duration-200 shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:shadow-[0_8px_24px_rgba(0,0,0,0.04)]">
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="h-[46px] w-[46px] bg-white border border-slate-100 shadow-sm rounded-lg flex items-center justify-center font-black text-slate-800 text-[15px]">
                            NC
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-[16px] tracking-tight leading-none mb-1">Nova Cobalt Corp</h3>
                            <p class="text-[9px] font-black tracking-widest text-[#64748b] uppercase">STRATEGIC PARTNER</p>
                        </div>
                    </div>
                    <button type="button" data-pricing-modal-open="companyPricingModal" class="rounded-lg border border-slate-200 bg-white p-1.5 text-slate-400 shadow-sm transition hover:border-primary-100 hover:bg-primary-50 hover:text-primary-600">
                        <svg class="w-[15px] h-[15px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                </div>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-slate-500 font-medium">Preferred B2B Rate</span>
                        <span class="font-extrabold text-slate-900">-₹1,000 Base</span>
                    </div>
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-slate-500 font-medium">Private Slab Active</span>
                        <span class="font-black text-[#059669] tracking-wider">YES</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-5 border-t border-slate-100">
                    <span class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">LAST SYNC: 2H AGO</span>
                    <a href="#" class="font-bold text-primary-800 text-[12px] hover:text-primary-600 transition tracking-wide">View Matrix</a>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="border border-slate-100 rounded-[12px] bg-[#fcfdfd] p-6 relative group hover:border-slate-200 transition duration-200 shadow-[0_2px_12px_rgba(0,0,0,0.01)] hover:shadow-[0_8px_24px_rgba(0,0,0,0.04)]">
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="h-[46px] w-[46px] bg-white border border-slate-100 shadow-sm rounded-lg flex items-center justify-center font-black text-slate-800 text-[15px]">
                            BT
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-[16px] tracking-tight leading-none mb-1">BioTech Solutions</h3>
                            <p class="text-[9px] font-black tracking-widest text-[#64748b] uppercase">REGULAR CLIENT</p>
                        </div>
                    </div>
                    <button type="button" data-pricing-modal-open="companyPricingModal" class="rounded-lg border border-slate-200 bg-white p-1.5 text-slate-400 shadow-sm transition hover:border-primary-100 hover:bg-primary-50 hover:text-primary-600">
                        <svg class="w-[15px] h-[15px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                </div>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-slate-500 font-medium">Preferred B2B Rate</span>
                        <span class="font-extrabold text-slate-900">-₹400 Base</span>
                    </div>
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-slate-500 font-medium">Private Slab Active</span>
                        <span class="font-black text-slate-400 tracking-wider">NO</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-5 border-t border-slate-100">
                    <span class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">LAST SYNC: 1D AGO</span>
                    <a href="#" class="font-bold text-primary-800 text-[12px] hover:text-primary-600 transition tracking-wide">View Matrix</a>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- MODALS --}}
@include('admin.pricing.modals.all-modals')

@endsection

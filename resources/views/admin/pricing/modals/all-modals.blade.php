<!-- 1. Map Pricing Modal -->
<div id="mapPricingModal" class="fixed inset-0 z-[9999] hidden" data-pricing-modal-root>
    <div id="mapPricingBackdrop" class="absolute inset-0 bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-300"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div id="mapPricingDialog" class="pointer-events-auto relative w-full max-w-xl translate-y-2 scale-95 opacity-0 overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,0.16)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[90vh] overflow-y-auto">

            <div class="flex items-start justify-between border-b border-slate-100 px-8 pb-6 pt-8">
                <div>
                    <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Map Pricing</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">PRICING CONFIGURATION MATRIX</p>
                </div>
                <button onclick="window.PricingModals.close('mapPricingModal')" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form class="space-y-6 px-8 py-7">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">GUEST PRICE (BASE PRICE)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rs.</span>
                        <input type="text" placeholder="0.00" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2C PRICE (%)</label>
                        <div class="relative">
                            <input type="text" placeholder="e.g., 10%" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-10 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2B PRICE (MANUAL)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rs.</span>
                            <input type="text" placeholder="0.00" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                    </div>
                </div>

                <p class="text-[11px] text-[#64748b] font-medium italic flex items-start gap-1.5 leading-snug">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5 opacity-60" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    B2B price is calculated as a further offset from the defined B2C price tier.
                </p>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2B UNITS</label>
                    <input type="text" placeholder="e.g., Medical Terms" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">DISCOUNT (%)</label>
                        <div class="relative">
                            <input type="text" placeholder="0%" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-8 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">APPLY DISCOUNT TO</label>
                        <div class="relative">
                            <select class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-4 text-[13px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600 cursor-pointer">
                                <option>B2C</option>
                                <option>B2B</option>
                                <option>Both B2C and B2B</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                    <button type="button" onclick="window.PricingModals.close('mapPricingModal')" class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-[13px] font-bold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">Cancel</button>
                    <button type="button" class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-7 py-2.5 text-[14px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">Apply Pricing</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Edit Product Pricing Modal -->
<div id="editProductModal" class="fixed inset-0 z-[9999] hidden" data-pricing-modal-root>
    <div id="editProductBackdrop" class="absolute inset-0 bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-300"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div id="editProductDialog" class="pointer-events-auto relative w-full max-w-2xl translate-y-2 scale-95 opacity-0 overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,0.16)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[90vh] overflow-y-auto">

            <div class="flex items-start justify-between border-b border-slate-100 px-8 pb-6 pt-8">
                <div>
                    <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Edit Product Pricing</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">PRICING SPECIFICATION ENGINE V4.2</p>
                </div>
                <button onclick="window.PricingModals.close('editProductModal')" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form class="space-y-6 px-8 py-7">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">GUEST PRICE (BASE PRICE)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rs.</span>
                            <input type="text" value="24,500" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2C PRICE (%)</label>
                        <div class="relative flex h-12 items-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition focus-within:border-primary-600 focus-within:bg-white focus-within:ring-1 focus-within:ring-primary-600">
                            <input type="text" value="15" class="w-full bg-transparent pl-4 pr-3 text-[14px] font-bold text-slate-800 outline-none">
                            <span class="flex h-full items-center self-stretch border-l border-slate-200 bg-white px-4 text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2B RATE CONFIGURATION</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative flex h-12 items-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition focus-within:border-primary-600 focus-within:bg-white focus-within:ring-1 focus-within:ring-primary-600">
                            <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">Rs.</span>
                            <input type="text" value="18,000" class="w-full bg-transparent pr-4 text-[14px] font-bold text-slate-800 outline-none">
                        </div>
                        <div class="relative flex h-12 items-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition focus-within:border-primary-600 focus-within:bg-white focus-within:ring-1 focus-within:ring-primary-600">
                            <input type="text" value="test cases" class="w-full bg-transparent px-4 text-[13px] font-bold text-slate-700 outline-none">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-6">
                    <h4 class="text-[13px] font-extrabold text-primary-800 mb-4 tracking-tight">Global Promotional Rules</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-slate-500 tracking-widest uppercase mb-2">DISCOUNT PERCENTAGE</label>
                            <div class="relative flex h-11 items-center overflow-hidden rounded-xl border border-slate-200 bg-white transition focus-within:border-primary-600 focus-within:ring-1 focus-within:ring-primary-600">
                                <input type="text" value="5" class="w-full pl-4 text-[14px] font-bold text-slate-800 outline-none">
                                <span class="pr-4 text-slate-400 font-bold">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-500 tracking-widest uppercase mb-2">APPLY DISCOUNT TO</label>
                            <div class="relative rounded-xl border border-slate-200 bg-white transition focus-within:border-primary-600 focus-within:ring-1 focus-within:ring-primary-600">
                                <select class="h-11 w-full appearance-none bg-transparent px-4 text-[13px] font-bold text-slate-700 outline-none cursor-pointer">
                                    <option>B2C</option>
                                    <option>B2B</option>
                                    <option>Both B2C and B2B</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 pt-6">
                    <span class="text-[11px] font-medium text-slate-400 italic flex items-center gap-1.5">
                        <svg class="h-[14px] w-[14px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Last modified: 24 May, 2024
                    </span>
                    <div class="flex gap-2">
                        <button type="button" onclick="window.PricingModals.close('editProductModal')" class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-[13px] font-bold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">Cancel</button>
                        <button type="button" class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-6 py-2.5 text-[13px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">Update Pricing</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. Bulk Pricing Configuration Modal -->
<div id="bulkPricingModal" class="fixed inset-0 z-[9999] hidden" data-pricing-modal-root>
    <div id="bulkPricingBackdrop" class="absolute inset-0 bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-300"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div id="bulkPricingDialog" class="pointer-events-auto relative w-full max-w-lg translate-y-2 scale-95 opacity-0 overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_26px_72px_rgba(15,23,42,0.16)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[90vh] overflow-y-auto">

            <div class="flex items-start justify-between border-b border-slate-100 px-6 pb-5 pt-6">
                <div>
                    <h3 class="text-[17px] font-extrabold text-slate-900 tracking-tight leading-none mb-1">Bulk Pricing Configuration</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">PRICING ENGINE V2.4</p>
                </div>
                <button onclick="window.PricingModals.close('bulkPricingModal')" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form class="space-y-5 px-6 py-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">TARGET PRODUCT</label>
                    <div class="relative flex h-11 items-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition focus-within:border-primary-600 focus-within:bg-white focus-within:ring-1 focus-within:ring-primary-600">
                        <span class="pl-3.5 pr-2 py-3 text-slate-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" placeholder="Type product name..." class="w-full bg-transparent px-2 text-[12px] font-bold text-[var(--ui-text)] outline-none placeholder:text-slate-400">
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="grid gap-x-3 gap-y-2.5 items-center" style="grid-template-columns: 1fr 1fr 24px;">
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase">PRICE (INR)</label>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase">MIN QUANTITY</label>
                        <span></span>

                        <div id="bulkPricingSlabRows" class="contents">
                            <div class="contents" data-bulk-slab-row>
                                <div class="relative flex h-11 items-center rounded-xl border border-slate-200 bg-white">
                                    <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">INR</span>
                                    <input type="text" value="415.00" class="w-full bg-transparent pr-3 text-[12px] font-bold text-slate-800 outline-none" data-bulk-slab-price>
                                </div>
                                <input type="text" value="100" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-[12px] font-bold text-slate-800 outline-none" data-bulk-slab-qty>
                                <button type="button" class="text-slate-300 hover:text-red-500 transition" data-bulk-slab-remove aria-label="Remove slab">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>

                            <div class="contents" data-bulk-slab-row>
                                <div class="relative flex h-11 items-center rounded-xl border border-slate-200 bg-white">
                                    <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">INR</span>
                                    <input type="text" value="385.50" class="w-full bg-transparent pr-3 text-[12px] font-bold text-slate-800 outline-none" data-bulk-slab-price>
                                </div>
                                <input type="text" value="500" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-[12px] font-bold text-slate-800 outline-none" data-bulk-slab-qty>
                                <button type="button" class="text-slate-300 hover:text-red-500 transition" data-bulk-slab-remove aria-label="Remove slab">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="flex w-full justify-center items-center gap-2 rounded-xl border-[1.5px] border-dashed border-slate-200 py-2.5 text-[10px] font-extrabold tracking-widest uppercase text-slate-500 transition hover:text-slate-700 hover:bg-slate-50 hover:border-slate-300" data-bulk-slab-add>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    ADD ANOTHER SLAB
                </button>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" onclick="window.PricingModals.close('bulkPricingModal')" class="inline-flex h-10 items-center justify-center rounded-xl px-4 text-[12px] font-bold text-slate-500 transition hover:bg-slate-50 hover:text-slate-900 uppercase tracking-widest">CANCEL</button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-[12px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">
                        Save Slabs
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. Company Specific Pricing Modal -->
<div id="companyPricingModal" class="fixed inset-0 z-[9999] hidden" data-pricing-modal-root>
    <div id="companyPricingBackdrop" class="absolute inset-0 bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-300"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div id="companyPricingDialog" class="pointer-events-auto relative w-full max-w-2xl translate-y-2 scale-95 opacity-0 overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,0.16)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[90vh] overflow-y-auto">

            <div class="flex items-start justify-between border-b border-slate-100 px-8 pb-6 pt-8">
                <div>
                    <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Add Company Specific Pricing</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">OVERRIDE CONFIGURATION ENGINE</p>
                </div>
                <button onclick="window.PricingModals.close('companyPricingModal')" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form class="space-y-6 px-8 py-7">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">COMPANY NAME (TYPE-AHEAD)</label>
                    <input id="companyPricingName" type="text" placeholder="Start typing company name..." list="companyPricingSuggestions" autocomplete="off" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-bold text-slate-800 outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                    <datalist id="companyPricingSuggestions">
                        <option value="Global Bioceuticals Inc."></option>
                        <option value="Nova Cobalt Corp"></option>
                        <option value="BioTech Solutions"></option>
                    </datalist>
                    <div class="grid grid-cols-1 gap-4 mt-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Legal Business Name</label>
                            <input id="companyPricingLegalName" type="text" placeholder="Legal name..." class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13px] text-slate-600 font-medium outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Company Reg. Number</label>
                            <input id="companyPricingRegNumber" type="text" placeholder="CIN / Reg number..." class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13px] text-slate-600 font-medium outline-none transition focus:border-primary-600 focus:bg-white focus:ring-1 focus:ring-primary-600">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">GST Number</label>
                            <input id="companyPricingGstin" type="text" placeholder="GST number will auto-fill from company selection" readonly class="h-11 w-full rounded-xl border border-slate-200 bg-slate-100 px-3 text-[13px] text-slate-600 font-semibold outline-none read-only:cursor-not-allowed read-only:text-slate-500">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-6">
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-4">PRODUCT SELECTION</label>
                    <div class="flex gap-5 mb-4 flex-wrap">
                        <label class="flex items-center gap-2 cursor-pointer text-[13px] font-bold text-primary-800">
                            <input type="radio" name="co_prod_sel" checked class="accent-primary-600"> Apply to All
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-[13px] font-medium text-slate-500">
                            <input type="radio" name="co_prod_sel" class="accent-primary-600"> Specific Category
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-[13px] font-medium text-slate-500">
                            <input type="radio" name="co_prod_sel" class="accent-primary-600"> Single Product
                        </label>
                    </div>
                    <div class="relative flex h-12 items-center overflow-hidden rounded-xl border border-slate-200 bg-white transition focus-within:border-primary-600 focus-within:ring-1 focus-within:ring-primary-600">
                        <input type="text" placeholder="Search product or category..." class="w-full bg-transparent px-4 text-[13px] font-medium outline-none placeholder:text-slate-400">
                        <span class="pr-4 text-slate-400"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">SPECIFIC B2B PRICE</label>
                        <div class="relative flex h-12 items-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition focus-within:border-primary-600 focus-within:bg-white focus-within:ring-1 focus-within:ring-primary-600">
                            <span class="pl-4 pr-1 text-slate-400 font-bold">INR</span>
                            <input type="text" placeholder="0.00" class="w-full bg-transparent pr-4 text-[14px] font-bold text-slate-800 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">EXCLUSIVE DISCOUNT (%)</label>
                        <div class="relative flex h-12 items-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition focus-within:border-primary-600 focus-within:bg-white focus-within:ring-1 focus-within:ring-primary-600">
                            <input type="text" placeholder="0" class="w-full bg-transparent pl-4 pr-1 text-[14px] font-bold text-slate-800 outline-none">
                            <span class="pr-4 text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-[#64748b] font-medium italic flex items-start gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5 opacity-60" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    The exclusive discount, if added, will be applied over the specific B2B price configured for this company.
                </p>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                    <button type="button" onclick="window.PricingModals.close('companyPricingModal')" class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-[13px] font-bold text-slate-500 transition hover:bg-slate-50 hover:text-slate-900">Cancel</button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-[13px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/></svg>
                        Save Company Pricing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
window.PricingModals = {
    _modals: ['mapPricingModal', 'editProductModal', 'bulkPricingModal', 'companyPricingModal'],
    _isInitialised: false,

    init() {
        if (this._isInitialised) return;
        this._isInitialised = true;

        this._modals.forEach((id) => {
            const el = document.getElementById(id);
            if (el && el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
        });

        document.addEventListener('click', (event) => {
            const openTrigger = event.target.closest('[data-pricing-modal-open]');
            if (openTrigger) {
                event.preventDefault();
                const modalId = openTrigger.getAttribute('data-pricing-modal-open');
                
                // Inject Variant ID if available
                const variantId = openTrigger.getAttribute('data-variant-id');
                const modalEl = document.getElementById(modalId);
                if (modalEl && variantId) {
                    const variantInput = modalEl.querySelector('input[name="variant_id"]');
                    if (variantInput) {
                        variantInput.value = variantId;
                    }
                }

                // Inject Company ID if available (for editing company pricing rules if needed)
                const companyId = openTrigger.getAttribute('data-company-id');
                if (modalEl && companyId) {
                    const companyInput = modalEl.querySelector('select[name="company_id"], input[name="company_id"]');
                    if (companyInput) {
                        companyInput.value = companyId;
                    }
                }

                this.open(modalId);
                return;
            }

            const closeTrigger = event.target.closest('[data-pricing-modal-close]');
            if (closeTrigger) {
                event.preventDefault();
                this.close(closeTrigger.getAttribute('data-pricing-modal-close'));
                return;
            }

            const backdrop = event.target.closest('[id$="Backdrop"]');
            if (backdrop) {
                const modal = backdrop.closest('[data-pricing-modal-root]');
                if (modal) {
                    this.close(modal.id);
                }
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this._modals.forEach((id) => {
                    const el = document.getElementById(id);
                    if (el && !el.classList.contains('hidden')) {
                        this.close(id);
                    }
                });
            }
        });
    },

    open(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const backdrop = modal.querySelector('[id$="Backdrop"]');
        const dialog = modal.querySelector('[id$="Dialog"]');

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                if (backdrop) backdrop.classList.replace('opacity-0', 'opacity-100');
                if (dialog) {
                    dialog.classList.remove('opacity-0', 'scale-95', 'translate-y-2');
                    dialog.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                }
            });
        });
    },

    close(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const backdrop = modal.querySelector('[id$="Backdrop"]');
        const dialog = modal.querySelector('[id$="Dialog"]');

        if (backdrop) backdrop.classList.replace('opacity-100', 'opacity-0');
        if (dialog) {
            dialog.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
            dialog.classList.add('opacity-0', 'scale-95', 'translate-y-2');
        }

        setTimeout(() => {
            modal.classList.add('hidden');

            const hasOpenModal = this._modals.some((id) => {
                const currentModal = document.getElementById(id);
                return currentModal && !currentModal.classList.contains('hidden');
            });

            if (!hasOpenModal) {
                document.body.classList.remove('overflow-hidden');
            }
        }, 300);
    },

    toggle(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        if (modal.classList.contains('hidden')) {
            this.open(modalId);
            return;
        }

        this.close(modalId);
    },
};

(function () {
    const companyDirectory = {
        'Global Bioceuticals Inc.': {
            legalName: 'Global Bioceuticals India Pvt Ltd',
            regNumber: 'CIN: U74999MH2023PTC123456',
            gstin: '27AAJCG1284M1ZV',
        },
        'Nova Cobalt Corp': {
            legalName: 'Nova Cobalt Corporation Private Limited',
            regNumber: 'CIN: U24299GJ2022PTC451208',
            gstin: '24AABCN4512Q1ZH',
        },
        'BioTech Solutions': {
            legalName: 'Biotech Solutions India Private Limited',
            regNumber: 'CIN: U73100KA2021PTC778321',
            gstin: '29AAICB7783L1ZK',
        },
    };

    function updateBulkSlabRemoveState() {
        const rows = Array.from(document.querySelectorAll('[data-bulk-slab-row]'));

        rows.forEach((row) => {
            const removeButton = row.querySelector('[data-bulk-slab-remove]');
            if (!removeButton) return;

            const disable = rows.length <= 1;
            removeButton.disabled = disable;
            removeButton.classList.toggle('opacity-40', disable);
            removeButton.classList.toggle('cursor-not-allowed', disable);
        });
    }

    function createBulkSlabRow(priceValue = '', qtyValue = '') {
        const wrapper = document.createElement('div');
        wrapper.className = 'contents';
        wrapper.setAttribute('data-bulk-slab-row', '');
        wrapper.innerHTML = `
            <div class="relative flex h-11 items-center rounded-xl border border-slate-200 bg-white">
                <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">INR</span>
                <input type="text" value="${String(priceValue)}" class="w-full bg-transparent pr-3 text-[12px] font-bold text-slate-800 outline-none" data-bulk-slab-price>
            </div>
            <input type="text" value="${String(qtyValue)}" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-[12px] font-bold text-slate-800 outline-none" data-bulk-slab-qty>
            <button type="button" class="text-slate-300 hover:text-red-500 transition" data-bulk-slab-remove aria-label="Remove slab">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        `;

        return wrapper;
    }

    function hydrateCompanyFields(companyName) {
        const legalNameInput = document.getElementById('companyPricingLegalName');
        const regNumberInput = document.getElementById('companyPricingRegNumber');
        const gstinInput = document.getElementById('companyPricingGstin');
        const normalizedName = String(companyName || '').trim();
        const companyData = companyDirectory[normalizedName];

        if (!legalNameInput || !regNumberInput || !gstinInput) {
            return;
        }

        if (!companyData) {
            legalNameInput.value = '';
            regNumberInput.value = '';
            gstinInput.value = '';
            return;
        }

        legalNameInput.value = companyData.legalName;
        regNumberInput.value = companyData.regNumber;
        gstinInput.value = companyData.gstin;
    }

    function initPricingModalEnhancements() {
        const slabContainer = document.getElementById('bulkPricingSlabRows');
        const addSlabButton = document.querySelector('[data-bulk-slab-add]');
        const companyNameInput = document.getElementById('companyPricingName');

        if (slabContainer && !slabContainer.dataset.bound) {
            slabContainer.dataset.bound = 'true';
            slabContainer.addEventListener('click', (event) => {
                const removeButton = event.target.closest('[data-bulk-slab-remove]');
                if (!removeButton) return;

                const row = removeButton.closest('[data-bulk-slab-row]');
                if (!row || document.querySelectorAll('[data-bulk-slab-row]').length <= 1) {
                    return;
                }

                row.remove();
                updateBulkSlabRemoveState();
            });
        }

        if (addSlabButton && !addSlabButton.dataset.bound) {
            addSlabButton.dataset.bound = 'true';
            addSlabButton.addEventListener('click', () => {
                if (!slabContainer) return;

                const newRow = createBulkSlabRow();
                slabContainer.appendChild(newRow);

                const slabRows = slabContainer.querySelectorAll('[data-bulk-slab-row]');
                const latestPriceInput = slabRows.length
                    ? slabRows[slabRows.length - 1].querySelector('[data-bulk-slab-price]')
                    : null;
                if (latestPriceInput) {
                    latestPriceInput.focus();
                }

                updateBulkSlabRemoveState();
            });
        }

        if (companyNameInput && !companyNameInput.dataset.bound) {
            companyNameInput.dataset.bound = 'true';
            const syncCompany = () => hydrateCompanyFields(companyNameInput.value);
            companyNameInput.addEventListener('input', syncCompany);
            companyNameInput.addEventListener('change', syncCompany);
        }

        updateBulkSlabRemoveState();
        if (companyNameInput) {
            hydrateCompanyFields(companyNameInput.value);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.PricingModals.init();
            initPricingModalEnhancements();
        }, { once: true });
    } else {
        window.PricingModals.init();
        initPricingModalEnhancements();
    }
})();

window.toggleModal = function (id) { window.PricingModals.toggle(id); };
function toggleModal(id) { window.PricingModals.toggle(id); }
</script>

<style>
    @keyframes pricingFadeIn {
        from { opacity: 0; transform: translateY(8px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>

<!-- 4. Add Company Specific Pricing Modal -->
<div id="companyPricingModal" style="display:none;" class="fixed inset-0 z-[9999] items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 modal-backdrop" onclick="window.toggleModal('companyPricingModal')"></div>
    <div class="relative w-full max-w-[550px] bg-white rounded-[16px] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.3)] p-8 animate-fade-in-up border border-slate-100 z-10 max-h-[95vh] overflow-y-auto">
        
        <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-5">
            <div>
                <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Add Company Specific Pricing</h3>
                <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">OVERRIDE CONFIGURATION ENGINE</p>
            </div>
            <button onclick="window.toggleModal('companyPricingModal')" class="text-slate-400 hover:text-slate-800 transition">
                <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form class="space-y-6">
            <!-- Company Selection -->
            <div>
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">
                    <svg class="w-[14px] h-[14px] text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    COMPANY NAME (TYPE-AHEAD)
                </label>
                <div class="relative">
                    <input type="text" value="Global Bioceuticals Inc." class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-100 rounded-lg text-[14px] font-bold text-slate-800 focus:outline-none focus:ring-1 focus:border-slate-300">
                    <!-- Dropdown simulation for type-ahead -->
                    <div class="hidden absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-40 overflow-y-auto">
                        <div class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-[13px] font-medium text-slate-700">Global Bioceuticals Inc.</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="block text-[10px] font-medium text-slate-500 mb-1.5 px-0.5">Legal Business Name</label>
                        <input type="text" value="Global Bioceuticals India Pvt Ltd" class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-100 rounded text-[13px] text-slate-600 font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-slate-500 mb-1.5 px-0.5">Company Reg. Number</label>
                        <input type="text" value="CIN: U74999MH2023PTC123456" class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-100 rounded text-[13px] text-slate-600 font-medium">
                    </div>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="border border-slate-100 rounded-2xl p-5 bg-[#fcfdfd] shadow-[0_2px_8px_rgba(0,0,0,0.015)]">
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-500 tracking-widest uppercase mb-4">
                    <svg class="w-[14px] h-[14px] text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    PRODUCT SELECTION
                </label>
                
                <div class="flex gap-5 mb-5 flex-wrap">
                    <label class="flex items-center gap-2 cursor-pointer text-[13px] font-bold text-[#0f172a]">
                        <input type="radio" name="prod_sel" checked class="w-[18px] h-[18px] text-[#0f172a] bg-white border-slate-300 focus:ring-[#0f172a] accent-[#0f172a]">
                        Apply to All
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-[13px] font-medium text-slate-500 hover:text-slate-700">
                        <input type="radio" name="prod_sel" class="w-[18px] h-[18px] text-[#0f172a] bg-white border-slate-300 focus:ring-[#0f172a] accent-[#0f172a]">
                        Specific Category
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-[13px] font-medium text-slate-500 hover:text-slate-700">
                        <input type="radio" name="prod_sel" class="w-[18px] h-[18px] text-[#0f172a] bg-white border-slate-300 focus:ring-[#0f172a] accent-[#0f172a]">
                        Single Product
                    </label>
                </div>
                
                <div class="relative bg-white border border-slate-200 rounded-lg flex items-center overflow-hidden focus-within:ring-1 focus-within:ring-slate-300 focus-within:border-slate-300 transition">
                    <input type="text" placeholder="Search product or category..." class="w-full px-4 py-2.5 bg-transparent border-none text-[13px] text-slate-800 font-medium focus:outline-none placeholder:text-slate-400">
                    <span class="pr-4 py-2.5 text-slate-400">
                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
            </div>

            <!-- Price Setting -->
            <div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="flex items-center gap-2 text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">
                            <svg class="w-[14px] h-[14px] text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            SPECIFIC B2B PRICE
                        </label>
                        <div class="relative flex items-center bg-[#f8fafc] border border-slate-100 rounded-xl overflow-hidden focus-within:ring-1 focus-within:border-slate-300">
                            <span class="pl-4 pr-1 text-slate-400 font-bold">₹</span>
                            <input type="text" value="12,450" class="w-full py-3.5 pr-4 border-none bg-transparent text-[14px] font-bold text-slate-800 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">
                            <svg class="w-[14px] h-[14px] text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            EXCLUSIVE DISCOUNT (%)
                        </label>
                        <div class="relative flex items-center bg-[#f8fafc] border border-slate-100 rounded-xl overflow-hidden focus-within:ring-1 focus-within:border-slate-300">
                            <input type="text" value="12" class="w-full pl-4 pr-1 py-3.5 border-none bg-transparent text-[14px] font-bold text-slate-800 focus:outline-none">
                            <span class="pr-4 text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                </div>
                <!-- Helper text addressing the discount logic constraint -->
                <p class="text-[11px] text-[#64748b] font-medium italic mt-2.5 flex items-start gap-1.5 pl-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5 opacity-60 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    The exclusive discount, if added, will be applied over the specific B2B price configured for this company.
                </p>
            </div>

            <!-- Custom Bulk Pricing Box -->
            <div class="border border-slate-100 rounded-2xl p-6 bg-white relative shadow-sm mt-6">
                <label class="flex items-center gap-3 cursor-pointer mb-5 text-[14px] font-extrabold text-[#0f172a]">
                    <div class="w-5 h-5 bg-[#0b1727] rounded text-white flex items-center justify-center border border-[#0b1727]">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    Enable Custom Bulk Pricing
                </label>
                
                <div class="grid grid-cols-[1fr,1fr,1.3fr] gap-3 mb-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 mb-1.5 px-0.5">Min Qty</label>
                        <input type="text" value="50" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-[13px] font-bold text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 mb-1.5 px-0.5">Max Qty</label>
                        <input type="text" value="200" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-[13px] font-bold text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 mb-1.5 px-0.5">Price (₹)</label>
                        <div class="relative flex items-center bg-white border border-slate-200 rounded-lg overflow-hidden">
                            <span class="pl-3 pr-1 py-2.5 text-slate-400 font-bold text-[13px]">₹</span>
                            <input type="text" value="11,200" class="w-full py-2.5 pr-2 border-none bg-transparent text-[13px] font-bold text-slate-800 focus:outline-none">
                        </div>
                    </div>
                </div>

                <button type="button" class="w-full py-3 border-[1.5px] border-dashed border-slate-200 rounded-lg text-[10px] font-extrabold tracking-widest uppercase text-slate-500 hover:text-[#0b1727] hover:bg-slate-50/50 hover:border-slate-300 transition flex justify-center items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    ADD PRICING SLAB
                </button>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <button type="button" onclick="window.toggleModal('companyPricingModal')" class="px-5 py-3 text-[13px] font-bold text-slate-500 hover:text-slate-900 transition hover:bg-slate-50 rounded-lg">Cancel</button>
                <button type="button" onclick="window.toggleModal('companyPricingModal')" class="px-6 py-3 bg-[#0b1727] hover:bg-[#1e293b] text-white flex gap-2.5 items-center text-[13px] font-bold rounded-lg transition shadow-md shadow-slate-900/10">
                    <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 20 20"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/></svg>
                    Save Company Pricing
                </button>
            </div>
        </form>
    </div>
</div>

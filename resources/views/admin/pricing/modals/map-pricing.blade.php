<!-- 1. Map Pricing Modal -->
<div id="mapPricingModal" style="display:none;" class="fixed inset-0 z-[9999] items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 modal-backdrop" onclick="window.toggleModal('mapPricingModal')"></div>
    <div class="relative w-full max-w-[440px] bg-white rounded-[16px] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.3)] p-8 animate-fade-in-up border border-slate-100 z-10 max-h-[95vh] overflow-y-auto">
        
        <div class="flex justify-between items-start mb-7">
            <div>
                <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Map Pricing</h3>
                <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">PRICING CONFIGURATION MATRIX</p>
            </div>
            <button onclick="window.toggleModal('mapPricingModal')" class="text-slate-400 hover:text-slate-800 transition">
                <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form class="space-y-6">
            <div>
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">GUEST PRICE (BASE PRICE)</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-medium">₹</span>
                    <input type="text" placeholder="0.00" class="w-full pl-8 pr-4 py-3.5 bg-[#f8fafc] border border-slate-100 rounded-xl text-[14px] font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-300">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2C PRICE (%)</label>
                    <div class="relative">
                        <input type="text" placeholder="e.g., 10%" class="w-full pl-4 pr-10 py-3.5 bg-[#f8fafc] border border-slate-100 rounded-xl text-[14px] font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-300">
                        <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2B PRICE (MANUAL)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-medium">₹</span>
                        <input type="text" placeholder="0.00" class="w-full pl-8 pr-4 py-3.5 bg-[#f8fafc] border border-slate-100 rounded-xl text-[14px] font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-300">
                    </div>
                </div>
            </div>
            
            <p class="text-[11px] text-[#64748b] font-medium italic flex items-start gap-1.5 leading-snug">
                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5 opacity-60 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M7.414 15.414a2 2 0 11-2.828-2.828l3-3a2 2 0 012.828 0 1 1 0 001.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 005.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5z" clip-rule="evenodd"/></svg>
                B2B price is calculated as a further offset from the defined B2C price tier.
            </p>

            <div>
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2B UNITS</label>
                <input type="text" placeholder="e.g., Medical Terms" class="w-full px-4 py-3.5 bg-[#f8fafc] border border-slate-100 rounded-xl text-[14px] font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-300">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">DISCOUNT (%)</label>
                    <div class="relative">
                        <input type="text" placeholder="0%" class="w-full pl-4 pr-8 py-3.5 bg-[#f8fafc] border border-slate-100 rounded-xl text-[14px] font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-300">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">APPLY DISCOUNT TO</label>
                    <div class="relative">
                        <select class="w-full pl-4 pr-10 py-3.5 bg-[#f8fafc] border border-slate-100 rounded-xl text-[13px] font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-300 appearance-none cursor-pointer">
                            <option>B2C</option>
                            <option>B2B</option>
                            <option>Both B2C and B2B</option>
                        </select>
                        <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 pb-2 border-t border-slate-100 mt-6">
                <button type="button" onclick="window.toggleModal('mapPricingModal')" class="px-5 py-2.5 text-[13px] font-bold text-slate-600 hover:text-slate-900 transition tracking-wide">Cancel</button>
                <button type="button" onclick="window.toggleModal('mapPricingModal')" class="px-7 py-2.5 bg-[#0b1727] hover:bg-[#1e293b] text-white text-[14px] font-bold rounded-lg transition shadow-md shadow-slate-900/10">Apply Pricing</button>
            </div>
        </form>
    </div>
</div>

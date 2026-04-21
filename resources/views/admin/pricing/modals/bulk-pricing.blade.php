<!-- 3. Bulk Pricing Configuration Modal -->
<div id="bulkPricingModal" style="display:none;" class="fixed inset-0 z-[9999] items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 modal-backdrop" onclick="window.toggleModal('bulkPricingModal')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-2xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.3)] p-8 animate-fade-in-up border border-slate-100 z-10 max-h-[95vh] overflow-y-auto">
        
        <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-5">
            <div>
                <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Bulk Pricing Configuration</h3>
                <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">PRICING ENGINE V2.4</p>
            </div>
            <button onclick="window.toggleModal('bulkPricingModal')" class="text-slate-400 hover:text-slate-800 transition">
                <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form class="space-y-6">
            <div>
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">TARGET PRODUCT</label>
                
                <!-- Type-ahead element simulation -->
                <div class="relative">
                    <div class="relative flex items-center border border-slate-200 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-slate-300 focus-within:border-slate-300">
                        <span class="pl-4 pr-2 py-3 bg-white text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" value="Amoxicillin 500mg - Capsule Strip" class="w-full px-2 py-3 border-none bg-white text-[13px] font-bold text-[#0f172a] focus:outline-none placeholder:text-slate-400">
                        <div class="flex items-center">
                            <span class="px-2 py-3 bg-white text-emerald-600">
                                <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </span>
                            <button type="button" class="px-3 py-3 bg-white text-slate-400 hover:text-slate-600 transition border-l border-slate-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hidden Dropdown for type-ahead to convey meaning to developers -->
                    <div class="hidden absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <div class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-[13px] font-medium text-slate-700">Amoxicillin 250mg - Tablet</div>
                        <div class="px-4 py-2 bg-slate-50 cursor-pointer text-[13px] font-bold text-slate-900 border-l-2 border-[#0b1727]">Amoxicillin 500mg - Capsule Strip</div>
                        <div class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-[13px] font-medium text-slate-700">Amoxicillin Liquid Suspension</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-[1fr,1fr,24px] gap-x-4 gap-y-4 pt-2 items-center">
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase col-span-1">PRICE (₹)</label>
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase col-span-2">MIN QUANTITY</label>
                
                <!-- Row 1 -->
                <div class="relative flex items-center bg-white border border-slate-200 rounded-lg focus-within:border-slate-300">
                    <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">₹</span>
                    <input type="text" value="415.00" class="w-full pr-3 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">
                </div>
                <div class="bg-white border border-slate-200 rounded-lg focus-within:border-slate-300">
                    <input type="text" value="100" class="w-full px-4 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">
                </div>
                <button type="button" class="text-slate-300 hover:text-red-500 transition mx-auto">
                    <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>

                <!-- Row 2 -->
                <div class="relative flex items-center bg-white border border-slate-200 rounded-lg focus-within:border-slate-300">
                    <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">₹</span>
                    <input type="text" value="385.50" class="w-full pr-3 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">
                </div>
                <div class="bg-white border border-slate-200 rounded-lg focus-within:border-slate-300">
                    <input type="text" value="500" class="w-full px-4 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">
                </div>
                <button type="button" class="text-slate-300 hover:text-red-500 transition mx-auto">
                    <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            <button type="button" class="w-full mt-2 py-3 border-[1.5px] border-dashed border-slate-200 rounded-lg text-[10px] font-extrabold tracking-widest uppercase text-slate-500 hover:text-slate-700 hover:bg-slate-50/50 hover:border-slate-300 transition flex justify-center items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                ADD ANOTHER SLAB
            </button>

            <div class="flex justify-end gap-3 pt-7 border-t border-slate-100 mt-6 pb-1">
                <button type="button" onclick="window.toggleModal('bulkPricingModal')" class="px-5 py-2.5 text-[11px] font-black text-slate-500 hover:text-slate-900 tracking-widest uppercase rounded transition hover:bg-slate-50">CANCEL</button>
                <button type="button" onclick="window.toggleModal('bulkPricingModal')" class="px-6 py-2.5 bg-[#0b1727] hover:bg-[#1e293b] text-white flex items-center gap-2.5 text-[13px] font-bold rounded-lg transition shadow-md shadow-slate-900/10">
                    Save Slabs 
                    <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                </button>
            </div>
        </form>
    </div>
</div>

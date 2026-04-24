<!-- 2. Edit Product Pricing Modal -->
<div id="editProductModal" style="display:none;" class="fixed inset-0 z-[9999] items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 modal-backdrop" onclick="window.toggleModal('editProductModal')"></div>
    <div class="relative w-full max-w-[480px] bg-white rounded-[16px] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.3)] p-8 animate-fade-in-up border border-slate-100 z-10 max-h-[95vh] overflow-y-auto">
        
        <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-5">
            <div>
                <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Edit Product Pricing</h3>
                <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">PRICING SPECIFICATION ENGINE V4.2</p>
            </div>
            <button onclick="window.toggleModal('editProductModal')" class="text-slate-400 hover:text-slate-800 transition">
                <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">GUEST PRICE (BASE PRICE)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₹</span>
                        <input type="text" value="24,500" class="w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-lg text-[14px] font-bold text-slate-800 focus:outline-none focus:border-slate-300 focus:ring-1 focus:ring-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">B2C PRICE (%)</label>
                    <div class="relative flex items-center bg-white border border-slate-200 rounded-lg overflow-hidden focus-within:border-slate-300 focus-within:ring-1 focus-within:ring-slate-300">
                        <input type="text" value="15" class="w-full pl-4 pr-3 py-3 border-none text-[14px] font-bold text-slate-800 focus:outline-none">
                        <span class="px-4 text-slate-400 font-bold bg-[#f8fafc] border-l border-slate-200 h-full flex items-center justify-center">%</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mt-5 mb-2.5">B2B RATE CONFIGURATION</label>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative flex items-center border border-slate-200 rounded-lg overflow-hidden focus-within:border-slate-300 focus-within:ring-1 focus-within:ring-slate-300">
                        <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">₹</span>
                        <input type="text" value="18,000" class="w-full pr-4 py-3 border-none bg-white text-[14px] font-bold text-slate-800 focus:outline-none">
                    </div>
                    <div class="relative flex items-center border border-slate-200 rounded-lg overflow-hidden focus-within:border-slate-300 focus-within:ring-1 focus-within:ring-slate-300">
                        <span class="pl-3 pr-2 py-3 text-slate-400 bg-[#f8fafc] border-r border-slate-100 h-full flex items-center justify-center">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        </span>
                        <input type="text" value="test cases" class="w-full pl-3 pr-4 py-3 border-none bg-white text-[13px] font-bold text-slate-700 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Global Promo Rules box -->
            <div class="mt-8 border border-slate-100 bg-[#f5f8ff] rounded-2xl p-6 relative">
                <h4 class="text-[13px] font-extrabold text-[#0b1727] mb-5 tracking-tight flex items-center gap-2">Global Promotional Rules</h4>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[9px] font-black text-slate-500 tracking-widest uppercase mb-2">DISCOUNT PERCENTAGE</label>
                        <div class="relative flex items-center bg-white border border-slate-200 rounded-lg overflow-hidden">
                            <input type="text" value="5" class="w-full pl-4 py-2.5 text-[14px] font-bold text-slate-800 border-none focus:outline-none">
                            <span class="pr-4 text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-500 tracking-widest uppercase mb-2">APPLY DISCOUNT TO</label>
                        <div class="relative block w-full bg-white border border-slate-200 rounded-lg">
                            <select class="w-full px-4 py-2.5 text-[13px] font-bold text-slate-700 outline-none appearance-none bg-transparent cursor-pointer">
                                <option>B2C</option>
                                <option>B2B</option>
                                <option>Both B2C and B2B</option>
                            </select>
                            <svg class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-5 mt-4">
                <div class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5 italic">
                    <svg class="h-[14px] w-[14px] text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Last modified: {{ date('d M, Y') }}
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="window.toggleModal('editProductModal')" class="px-5 py-2.5 text-[13px] font-bold text-slate-600 hover:text-slate-900 transition tracking-wide">Cancel</button>
                    <button type="button" onclick="window.toggleModal('editProductModal')" class="px-6 py-2.5 bg-[#0b1727] hover:bg-[#1e293b] text-white text-[13px] font-bold rounded-lg transition shadow-md shadow-slate-900/10">Update Pricing</button>
                </div>
            </div>
        </form>
    </div>
</div>

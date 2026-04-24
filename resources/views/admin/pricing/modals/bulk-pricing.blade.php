<!-- Bulk Pricing Configuration Modal -->
<div id="bulkPricingModal" style="display:none;" class="fixed inset-0 z-[9999] items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 modal-backdrop" onclick="window.toggleModal('bulkPricingModal')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-2xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.3)] p-8 animate-fade-in-up border border-slate-100 z-10 max-h-[95vh] overflow-y-auto">

        <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-5">
            <div>
                <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5">Bulk Pricing Configuration</h3>
                <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">VOLUME-BASED SLAB RULES</p>
            </div>
            <button onclick="window.toggleModal('bulkPricingModal')" class="text-slate-400 hover:text-slate-800 transition">
                <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="bulkPricingForm" method="POST" action="{{ route('admin.pricing.bulk-price.save') }}" class="space-y-6">
            @csrf

            {{-- Hidden field that holds the selected variant id --}}
            <input type="hidden" name="variant_id" id="bulkPricingVariantId" value="">

            <div>
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2.5">TARGET PRODUCT</label>

                {{-- Native select — styled by the global initCustomSelects --}}
                <select
                    id="bulkPricingProductSelect"
                    class="w-full px-4 py-3 border border-slate-200 rounded-lg text-[13px] font-bold text-slate-800 bg-white focus:outline-none"
                    onchange="document.getElementById('bulkPricingVariantId').value = this.value"
                >
                    <option value="" disabled selected>Select a product...</option>
                    @foreach ($allProductsForDropdown as $product)
                        <option value="{{ $product['variant_id'] }}">
                            {{ $product['product_name'] }} ({{ $product['sku'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Slab rows container --}}
            <div id="bulkSlabRows" class="grid grid-cols-[1fr,1fr,24px] gap-x-4 gap-y-4 pt-2 items-center">
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase col-span-1">PRICE (₹)</label>
                <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase col-span-2">MIN QUANTITY</label>

                <!-- Initial blank slab row -->
                <div class="relative flex items-center bg-white border border-slate-200 rounded-lg focus-within:border-slate-300">
                    <span class="pl-4 pr-1 py-3 text-slate-400 font-bold">₹</span>
                    <input type="number" name="slabs[0][amount]" step="0.01" min="0.01" placeholder="0.00"
                        class="w-full pr-3 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">
                </div>
                <div class="bg-white border border-slate-200 rounded-lg focus-within:border-slate-300">
                    <input type="number" name="slabs[0][min_quantity]" min="1" placeholder="100"
                        class="w-full px-4 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">
                </div>
                <button type="button" onclick="removeBulkSlabRow(this)" class="text-slate-300 hover:text-red-500 transition mx-auto">
                    <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            <button type="button" onclick="addBulkSlabRow()" class="w-full mt-2 py-3 border-[1.5px] border-dashed border-slate-200 rounded-lg text-[10px] font-extrabold tracking-widest uppercase text-slate-500 hover:text-slate-700 hover:bg-slate-50/50 hover:border-slate-300 transition flex justify-center items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                ADD ANOTHER SLAB
            </button>

            <div class="flex justify-end gap-3 pt-7 border-t border-slate-100 mt-6 pb-1">
                <button type="button" onclick="window.toggleModal('bulkPricingModal')" class="px-5 py-2.5 text-[11px] font-black text-slate-500 hover:text-slate-900 tracking-widest uppercase rounded transition hover:bg-slate-50">CANCEL</button>
                <button type="submit" class="px-6 py-2.5 bg-[#0b1727] hover:bg-[#1e293b] text-white flex items-center gap-2.5 text-[13px] font-bold rounded-lg transition shadow-md shadow-slate-900/10">
                    Save Slabs
                    <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Track the current slab row count to keep input names unique.
    var bulkSlabRowCount = 1;

    // Add a new slab price + min quantity row to the form.
    function addBulkSlabRow() {
        var slabIndex = bulkSlabRowCount;
        bulkSlabRowCount++;

        var rowContainer = document.getElementById('bulkSlabRows');

        // Build price input cell.
        var priceCell = document.createElement('div');
        priceCell.className = 'relative flex items-center bg-white border border-slate-200 rounded-lg focus-within:border-slate-300';
        priceCell.innerHTML = '<span class="pl-4 pr-1 py-3 text-slate-400 font-bold">₹</span>'
            + '<input type="number" name="slabs[' + slabIndex + '][amount]" step="0.01" min="0.01" placeholder="0.00"'
            + ' class="w-full pr-3 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">';

        // Build min quantity input cell.
        var qtyCell = document.createElement('div');
        qtyCell.className = 'bg-white border border-slate-200 rounded-lg focus-within:border-slate-300';
        qtyCell.innerHTML = '<input type="number" name="slabs[' + slabIndex + '][min_quantity]" min="1" placeholder="100"'
            + ' class="w-full px-4 py-3 border-none text-[13px] font-bold text-slate-800 focus:outline-none bg-transparent">';

        // Build delete button cell.
        var deleteCell = document.createElement('button');
        deleteCell.type = 'button';
        deleteCell.className = 'text-slate-300 hover:text-red-500 transition mx-auto';
        deleteCell.setAttribute('onclick', 'removeBulkSlabRow(this)');
        deleteCell.innerHTML = '<svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

        rowContainer.appendChild(priceCell);
        rowContainer.appendChild(qtyCell);
        rowContainer.appendChild(deleteCell);
    }

    // Remove the clicked slab row (the price, qty, and delete cells are siblings).
    function removeBulkSlabRow(deleteButton) {
        var deleteCell = deleteButton;
        var qtyCell    = deleteCell.previousElementSibling;
        var priceCell  = qtyCell.previousElementSibling;

        // Remove all three cells from the grid.
        deleteCell.remove();
        qtyCell.remove();
        priceCell.remove();
    }
</script>

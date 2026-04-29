@extends('admin.layout')

@section('title', 'Inventory Management - Biogenix Admin')

@section('admin_content')
    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Inventory Management</h2>
                <p class="text-sm text-[var(--ui-text-muted)] mt-1 font-medium">Real-time biogenic stock and reagent tracking system.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <form action="{{ route('admin.inventory.index') }}" method="GET" id="search-form" onsubmit="handleSearchSubmit(event)">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="SEARCH INVENTORY..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-xs font-bold tracking-wider uppercase rounded-xl pl-9 pr-4 py-2.5 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-[var(--ui-text-muted)]">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Available -->
            <div class="bg-[var(--ui-surface)] border border-[var(--ui-border)] rounded-2xl p-5 shadow-sm relative overflow-hidden flex items-center justify-between">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
                <div class="pl-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">AVAILABLE</p>
                    <p class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $stats['availablePercent'] }}%</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
            </div>

            <!-- Low Alerts -->
            <div class="bg-[var(--ui-surface)] border border-[var(--ui-border)] rounded-2xl p-5 shadow-sm relative overflow-hidden flex items-center justify-between">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>
                <div class="pl-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">LOW ALERTS</p>
                    <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ $stats['lowStockCount'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>

            <!-- Out of Stock -->
            <div class="bg-[var(--ui-surface)] border border-[var(--ui-border)] rounded-2xl p-5 shadow-sm relative overflow-hidden flex items-center justify-between">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500"></div>
                <div class="pl-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">OUT OF STOCK</p>
                    <p class="text-3xl font-extrabold text-rose-600 mt-1">{{ $stats['outOfStockCount'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-[var(--ui-surface)] border border-[var(--ui-border)] rounded-2xl shadow-sm overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 border-b border-[var(--ui-border)] flex flex-col sm:flex-row justify-between items-center gap-4 bg-[var(--ui-surface-subtle)]">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <p class="text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider">FILTER CATEGORY</p>
                    <form action="{{ route('admin.inventory.index') }}" method="GET" id="filter-form" class="flex items-center gap-2">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        <select name="category" onchange="handleFilterChange()" class="bg-white border border-slate-200 text-sm rounded-lg px-3 py-1.5 outline-none focus:border-primary-500 text-slate-700 font-medium">
                            <option value="all">All Categories</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <select name="status" onchange="handleFilterChange()" class="bg-white border border-slate-200 text-sm rounded-lg px-3 py-1.5 outline-none focus:border-primary-500 text-slate-700 font-medium">
                            <option value="">All Statuses</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[var(--ui-border)]">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">#</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">PRODUCT NAME</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">CATEGORY</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">AVAILABLE QTY</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)]">STATUS</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[var(--ui-text-muted)] text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ui-border)]">
                        @forelse($items as $item)
                            @php
                                $status = \App\Services\AdminPanel\InventoryCrudService::getStockStatus($item->stock_quantity);
                            @endphp
                            <tr class="hover:bg-[var(--ui-surface-subtle)] transition">
                                <td class="px-6 py-4 text-xs font-bold text-[var(--ui-text-muted)] whitespace-nowrap">
                                    {{ $item->sku ?? $item->product->sku ?? ('BC-'.str_pad($item->id, 4, '0', STR_PAD_LEFT)) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-lg border border-[var(--ui-border)] overflow-hidden flex items-center justify-center bg-white flex-shrink-0">
                                            @if($item->product->primaryImage)
                                                <img src="{{ Storage::url($item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="h-full w-full object-contain p-1" onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'h-5 w-5 text-slate-300\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z\' /></svg>';">
                                            @else
                                                <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[var(--ui-text)]">{{ $item->product->name }}</p>
                                            <p class="text-[11px] text-[var(--ui-text-muted)] uppercase tracking-wider mt-0.5">
                                                @if($item->variant_name) {{ $item->variant_name }} | @endif 
                                                REF: {{ $item->catalog_number ?? $item->model_number ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                                        {{ $item->product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-sm font-extrabold text-[var(--ui-text)]" id="qty-val-{{ $item->id }}">{{ $item->stock_quantity }}</span>
                                        <span class="text-xs font-semibold text-[var(--ui-text-muted)]">Units</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4" id="status-col-{{ $item->id }}">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest {{ $status['bg'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $status['dot'] }}"></span>
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button" onclick="openStockModal(
                                        {{ $item->id }}, 
                                        '{{ addslashes($item->product->name . ($item->variant_name ? ' - '.$item->variant_name : '')) }}', 
                                        {{ $item->stock_quantity }},
                                        '{{ addslashes($item->product->category->name ?? '') }}',
                                        '{{ addslashes($item->pack_size ?? '') }}',
                                        '{{ addslashes($item->coa_no ?? '') }}',
                                        '{{ addslashes($item->batch_no ?? '') }}',
                                        '{{ $item->mfg_date ? $item->mfg_date->format('Y-m-d') : '' }}',
                                        '{{ $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '' }}'
                                    )" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition" title="Update Stock">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-[var(--ui-text-muted)]">
                                    No inventory items found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
            <div class="p-4 border-t border-[var(--ui-border)] flex items-center justify-between">
                <div class="text-xs text-[var(--ui-text-muted)] font-medium">
                    Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} entries
                </div>
                <div>
                    {{ $items->links('pagination::tailwind') }}
                </div>
            </div>
            @else
            <div class="p-4 border-t border-[var(--ui-border)] flex items-center justify-between">
                <div class="text-xs text-[var(--ui-text-muted)] font-medium">
                    Showing {{ $items->count() }} entries
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Stock Update Modal -->
    <div id="stock-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="stock-modal-backdrop" onclick="closeStockModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-2xl bg-[var(--ui-surface)] rounded-2xl shadow-2xl overflow-hidden scale-95 opacity-0 transition-all duration-300 transform" id="stock-modal-content">
            <div class="p-6 border-b border-[var(--ui-border)] flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-[var(--ui-text)] tracking-tight">Edit Stock</h3>
                    <p class="text-xs text-[var(--ui-text-muted)] mt-1">Update existing inventory details in the centralized ledger.</p>
                </div>
                <button type="button" onclick="closeStockModal()" class="text-[var(--ui-text-muted)] hover:text-slate-700 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="stock-update-form" onsubmit="submitStockUpdate(event)">
                @csrf
                <input type="hidden" id="modal-variant-id" name="variant_id">
                <div class="p-6 space-y-5">
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">CATEGORY</label>
                            <input type="text" id="modal-category-name" readonly class="w-full bg-[var(--ui-surface-subtle)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none text-[var(--ui-text-muted)] cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">PRODUCT</label>
                            <input type="text" id="modal-product-name" readonly class="w-full bg-[var(--ui-surface-subtle)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none text-[var(--ui-text-muted)] cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="modal-stock-quantity" class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">QUANTITY</label>
                            <input type="number" id="modal-stock-quantity" name="stock_quantity" min="0" required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition">
                        </div>
                        <div>
                            <label for="modal-pack-size" class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">QUANTITY UNIT</label>
                            <select id="modal-pack-size" name="pack_size" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition">
                                <option value="">Select unit...</option>
                                <option value="Kits">Kits</option>
                                <option value="Units">Units</option>
                                <option value="Packs">Packs</option>
                                <option value="Bottles">Bottles</option>
                                <option value="Boxes">Boxes</option>
                                <option value="Pieces">Pieces</option>
                                <option value="Tests">Tests</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="modal-coa-no" class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">COA NO</label>
                            <input type="text" id="modal-coa-no" name="coa_no" placeholder="e.g. CERT-9821-X" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition placeholder-[var(--ui-text-muted)]">
                        </div>
                        <div>
                            <label for="modal-batch-no" class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">LOT/BATCH NO</label>
                            <input type="text" id="modal-batch-no" name="batch_no" placeholder="e.g. BTCH-5512" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition placeholder-[var(--ui-text-muted)]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="modal-mfg-date" class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">MFG DATE</label>
                            <input type="date" id="modal-mfg-date" name="mfg_date" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition text-[var(--ui-text-muted)] uppercase">
                        </div>
                        <div>
                            <label for="modal-expiry-date" class="block text-xs font-bold text-[var(--ui-text-muted)] uppercase tracking-wider mb-2">EXPIRY DATE</label>
                            <input type="date" id="modal-expiry-date" name="expiry_date" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-xl px-4 py-2.5 text-sm font-semibold outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition text-[var(--ui-text-muted)] uppercase">
                        </div>
                    </div>

                </div>
                <div class="p-4 border-t border-[var(--ui-border)] bg-[var(--ui-surface-subtle)] flex justify-end gap-3">
                    <button type="button" onclick="closeStockModal()" class="px-5 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                    <button type="submit" id="stock-save-btn" class="px-5 py-2 text-sm font-bold text-white bg-primary-900 rounded-lg hover:bg-primary-800 shadow-lg shadow-primary-900/20 transition flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Update Stock</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Make pagination links use layout's AJAX loader
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('nav[role="navigation"] a');
            if (paginationLink) {
                e.preventDefault();
                triggerAjaxLoad(paginationLink.href);
            }
        });

        function triggerAjaxLoad(urlStr) {
            let proxyLink = document.getElementById('ajax-proxy-link');
            if (!proxyLink) {
                proxyLink = document.createElement('a');
                proxyLink.id = 'ajax-proxy-link';
                proxyLink.className = 'ajax-link hidden';
                document.body.appendChild(proxyLink);
            }
            proxyLink.href = urlStr;
            proxyLink.click();
        }

        function handleSearchSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const url = new URL(form.action);
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value) url.searchParams.set(key, value);
            }
            triggerAjaxLoad(url.toString());
        }

        function handleFilterChange() {
            const form = document.getElementById('filter-form');
            const url = new URL(form.action);
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value && value !== 'all') {
                    url.searchParams.set(key, value);
                }
            }
            triggerAjaxLoad(url.toString());
        }

        function openStockModal(variantId, productName, currentStock, categoryName, packSize, coaNo, batchNo, mfgDate, expiryDate) {
            const modal = document.getElementById('stock-modal');
            const backdrop = document.getElementById('stock-modal-backdrop');
            const content = document.getElementById('stock-modal-content');
            
            document.getElementById('modal-variant-id').value = variantId;
            document.getElementById('modal-product-name').value = productName;
            document.getElementById('modal-category-name').value = categoryName || 'Uncategorized';
            document.getElementById('modal-stock-quantity').value = currentStock;
            
            // Set pack_size if it exists in the options, otherwise append it
            const packSizeSelect = document.getElementById('modal-pack-size');
            let optionExists = false;
            if (packSize) {
                Array.from(packSizeSelect.options).forEach(opt => {
                    if (opt.value === packSize) optionExists = true;
                });
                if (!optionExists) {
                    const newOption = new Option(packSize, packSize);
                    packSizeSelect.add(newOption);
                }
                packSizeSelect.value = packSize;
            } else {
                packSizeSelect.value = '';
            }

            document.getElementById('modal-coa-no').value = coaNo || '';
            document.getElementById('modal-batch-no').value = batchNo || '';
            document.getElementById('modal-mfg-date').value = mfgDate || '';
            document.getElementById('modal-expiry-date').value = expiryDate || '';
            
            modal.classList.remove('hidden');
            
            // Trigger reflow
            void modal.offsetWidth;
            
            backdrop.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
            content.classList.replace('opacity-0', 'opacity-100');
            
            document.getElementById('modal-stock-quantity').focus();
        }

        function closeStockModal() {
            const modal = document.getElementById('stock-modal');
            const backdrop = document.getElementById('stock-modal-backdrop');
            const content = document.getElementById('stock-modal-content');
            
            backdrop.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            content.classList.replace('opacity-100', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        async function submitStockUpdate(e) {
            e.preventDefault();
            const btn = document.getElementById('stock-save-btn');
            const variantId = document.getElementById('modal-variant-id').value;
            
            const payload = {
                stock_quantity: document.getElementById('modal-stock-quantity').value,
                pack_size: document.getElementById('modal-pack-size').value,
                coa_no: document.getElementById('modal-coa-no').value,
                batch_no: document.getElementById('modal-batch-no').value,
                mfg_date: document.getElementById('modal-mfg-date').value,
                expiry_date: document.getElementById('modal-expiry-date').value
            };
            
            if (window.AdminBtnLoading) window.AdminBtnLoading.start(btn);
            
            try {
                const response = await fetch(`{{ url('adminPanel/inventory') }}/${variantId}/stock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(payload)
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    const newQty = parseInt(payload.stock_quantity);
                    // Update UI directly without reload for better UX
                    document.getElementById(`qty-val-${variantId}`).textContent = newQty;
                    
                    // Update status badge
                    const statusCol = document.getElementById(`status-col-${variantId}`);
                    let badgeClass, dotClass, label;
                    
                    if (newQty <= 0) {
                        badgeClass = 'bg-rose-50 text-rose-700 ring-1 ring-rose-200';
                        dotClass = 'bg-rose-500';
                        label = 'OUT OF STOCK';
                    } else if (newQty <= 10) {
                        badgeClass = 'bg-amber-50 text-amber-700 ring-1 ring-amber-200';
                        dotClass = 'bg-amber-500';
                        label = 'LOW STOCK';
                    } else {
                        badgeClass = 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200';
                        dotClass = 'bg-emerald-500';
                        label = 'AVAILABLE';
                    }
                    
                    statusCol.innerHTML = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest ${badgeClass}">
                        <span class="h-1.5 w-1.5 rounded-full ${dotClass}"></span>
                        ${label}
                    </span>`;
                    
                    closeStockModal();
                    
                    if (window.AdminToast) window.AdminToast.show('Stock updated successfully', 'success');
                    
                    // Note: Optional to update KPI cards, but usually acceptable to wait for page reload
                } else {
                    throw new Error(result.message || 'Failed to update stock');
                }
            } catch (error) {
                if (window.AdminToast) window.AdminToast.show(error.message, 'error');
                else alert(error.message);
            } finally {
                if (window.AdminBtnLoading) window.AdminBtnLoading.stop(btn);
            }
        }
    </script>
@endsection

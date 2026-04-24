@extends('admin.layout')

@section('title', 'Product Management - Biogenix')

@section('admin_content')
            


            <!-- Welcome Header -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Products</h1>
                    <p class="text-sm text-[var(--ui-text-muted)] mt-1">Manage your biogenix inventory and product listings.</p>
                </div>
                
                <a href="{{ route('admin.products.create') }}" class="ajax-link bg-primary-600 hover:bg-primary-700 transition text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary-600/20 flex items-center gap-2 shrink-0 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Product
                </a>
            </div>

            <!-- Products Table -->
            <div class="bg-[var(--ui-surface)] rounded-2xl shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] overflow-hidden flex flex-col relative">

                <!-- Filter Bar -->
                <div class="px-5 lg:px-6 py-4 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    
                    <!-- Search -->
                    <div class="relative w-full lg:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="productSearchInput" placeholder="Search product name, SKU, or category..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                    </div>

                    <!-- Category Pills -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 lg:pb-0 scrollbar-hide" id="product-filter-pills">
                        <button type="button" data-filter="all" class="product-pill inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-primary-600 text-white shadow-sm transition-all duration-200 active:scale-95">All Products</button>
                        @foreach($categories as $category)
                            <button type="button" data-filter="{{ strtolower($category->name) }}" class="product-pill inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-[var(--ui-surface-subtle)] text-slate-600 border border-slate-200/60 hover:bg-slate-100 transition shadow-sm active:scale-95">{{ $category->name }}</button>
                        @endforeach
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Product</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">SKU</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Price</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Stock</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($products as $product)
                                <tr class="hover:bg-slate-50/50 transition-colors group cursor-pointer" data-product-category="{{ strtolower($product['categoryName']) }}">
                                    <td class="px-5 lg:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-lg bg-slate-100 text-primary-800 flex items-center justify-center shrink-0">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[13px] font-bold text-slate-900">{{ $product['name'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 lg:px-6 py-4">
                                        <span class="text-[13px] font-semibold text-slate-600">{{ $product['sku'] }}</span>
                                    </td>
                                    <td class="px-5 lg:px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-primary-50 text-primary-700 border border-primary-200/60 text-[11px] font-bold rounded-full">{{ $product['categoryName'] }}</span>
                                    </td>
                                    <td class="px-5 lg:px-6 py-4">
                                        <span class="text-[13px] font-bold text-slate-900">{{ $product['price'] ? '₹' . number_format((float)$product['price'], 2) : 'N/A' }}</span>
                                    </td>
                                    <td class="px-5 lg:px-6 py-4">
                                        <span class="text-[13px] font-semibold text-slate-600">{{ $product['stock'] }}</span>
                                    </td>
                                    <td class="px-5 lg:px-6 py-4">
                                        @php
                                            $status = $product['status'];
                                            $statusClass = match($status) {
                                                'Low Stock' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                                'Out of Stock' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                                default => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 {{ $statusClass }} border text-[11px] font-bold rounded-full">{{ $status }}</span>
                                    </td>
                                    <td class="px-5 lg:px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.products.edit', ['productId' => $product['id']]) }}" class="ajax-link p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                            </a>
                                            <button type="button" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" onclick="confirmDeleteProduct({{ $product['id'] }}, '{{ $product['name'] }}')">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 lg:px-6 py-8 text-center text-sm text-slate-500">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $products->links('admin.partials.pagination') }}
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteProductForm" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

<script>
    function confirmDeleteProduct(id, name) {
        if (confirm('Are you sure you want to delete product "' + name + '"? This action cannot be undone.')) {
            const form = document.getElementById('deleteProductForm');
            form.action = '/adminPanel/products/' + id;
            form.submit();
        }
    }

(function () {
    function getRows() {
        return document.querySelectorAll('tbody.divide-y > tr[data-product-category]');
    }

    var activeFilter = 'all';
    var activeSearch = '';

    function applyFilters() {
        getRows().forEach(function (row) {
            var category = (row.dataset.productCategory || '').toLowerCase();
            var text     = row.textContent.toLowerCase();
            var matchCat = activeFilter === 'all' || category === activeFilter;
            var matchSrc = !activeSearch || text.includes(activeSearch);
            row.style.display = (matchCat && matchSrc) ? '' : 'none';
        });
    }

    // ─── Search ───────────────────────────────────────────────────────────────
    var searchInput = document.getElementById('productSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            activeSearch = this.value.toLowerCase();
            applyFilters();
        });
    }

    // ─── Category pills ───────────────────────────────────────────────────────
    var pillContainer = document.getElementById('product-filter-pills');
    if (pillContainer) {
        pillContainer.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-filter]');
            if (!btn) return;
            activeFilter = btn.dataset.filter;

            // Update pill active styles
            pillContainer.querySelectorAll('.product-pill').forEach(function (p) {
                p.classList.remove('bg-primary-600', 'text-white', 'border-0', 'shadow-sm');
                p.classList.add('bg-[var(--ui-surface-subtle)]', 'text-slate-600', 'border', 'border-slate-200/60');
            });
            btn.classList.remove('bg-[var(--ui-surface-subtle)]', 'text-slate-600', 'border', 'border-slate-200/60');
            btn.classList.add('bg-primary-600', 'text-white', 'border-0', 'shadow-sm');

            applyFilters();
        });
    }
})();
</script>
@endsection

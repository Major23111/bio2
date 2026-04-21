@extends('admin.layout')

@section('title', 'Edit Product - Biogenix Admin')

@section('admin_content')

<div class="space-y-6">

    <!-- Back Arrow + Breadcrumb -->
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('admin.products') }}" class="ajax-link h-8 w-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition shrink-0 cursor-pointer" title="Back to Products">
            <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <nav class="flex text-[13px] text-slate-500 font-medium">
        <a href="#" class="hover:text-slate-900 transition flex items-center gap-1.5">
            Catalog
        </a>
        <span class="mx-2 text-slate-300">›</span>
        <a href="{{ route('admin.products') }}" class="ajax-link hover:text-slate-900 transition flex items-center gap-1.5 cursor-pointer">
            Products
        </a>
        <span class="mx-2 text-slate-300">›</span>
        <span class="text-slate-900 font-semibold cursor-pointer">Edit Product</span>
    </nav>
    </div>

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Product</h1>
            <p class="text-sm text-slate-500 mt-1">Update details for #BGX-7700.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products') }}" class="ajax-link px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition shadow-sm cursor-pointer">
                Cancel
            </a>
            <button id="saveProductBtn" type="button" class="bg-primary-600 hover:bg-primary-700 transition text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary-600/20 cursor-pointer">
                Save Changes
            </button>
        </div>
    </div>

    <!-- Form Sections -->
    <form id="productForm" class="space-y-6 pb-12" novalidate>

        <!-- 1. Product Information -->
        <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-slate-100 text-primary-800 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Product Information</h3>
            </div>
            <div class="p-6 space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Product Name -->
                    <div class="space-y-2">
                        <label class="block text-[13px] font-bold text-slate-700">Product Name <span class="text-rose-500">*</span></label>
                        <input id="productName" type="text" required class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                    </div>
                    
                    <!-- SKU -->
                    <div class="space-y-2">
                        <label class="block text-[13px] font-bold text-slate-700">CAT No <span class="text-rose-500">*</span></label>
                        <input id="productSku" type="text" required class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="block text-[13px] font-bold text-slate-700">Description <span class="text-rose-500">*</span></label>
                    <textarea id="productDesc" rows="4" required class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium resize-y">High-quality molecular grade reagent kit suitable for PCR and other sensitive experiments. Provides consistent results with minimal background noise.</textarea>
                </div>

                <!-- Specifics -> Product Overview -->
                <div class="space-y-2">
                    <label class="block text-[13px] font-bold text-slate-700">Product Overview</label>
                    <textarea rows="2" placeholder="Provide a brief overview for quick summary..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium resize-y"></textarea>
                </div>

            </div>
        </div>

        <!-- 2. Logistics -->
        <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-slate-100 text-primary-800 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Logistics</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-[13px] font-bold text-slate-700">GST Rate (%) <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="number" placeholder="18" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[13px] font-bold text-slate-700">Stock Qty <span class="text-rose-500">*</span></label>
                        <input id="productStock" type="number" required class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Category Mapping -->
            <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-visible">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-slate-100 text-primary-800 flex items-center justify-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Category Mapping</h3>
                </div>
                <div class="p-6 space-y-5">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-[13px] font-bold text-slate-700">Select Categories <span class="text-rose-500">*</span></label>
                            <select id="productCategory" name="category_id" required
                                class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium cursor-pointer">
                                <option value="1" selected>Biotechnology</option>
                                <option value="2">Research Tools</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-[13px] font-bold text-slate-700">Sub Category</label>
                            <input id="productSubCategory" type="text" placeholder="e.g. Molecular Biology" value="Molecular Biology" 
                                class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                        </div>
                    </div>

                    <div id="categoryTagsContainer" class="flex flex-wrap gap-2 pt-2">
                        <!-- Tags will be managed dynamically -->
                    </div>

                </div>
            </div>





        <!-- 6. Media Assets -->
        <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-slate-100 text-primary-800 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Media Assets</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left: Upload Box -->
                <div class="lg:col-span-2 space-y-4">
                    <label class="block text-[13px] font-bold text-slate-700">Product Images</label>
                    <input id="imageUploadInput" type="file" accept="image/png,image/jpeg,image/webp" multiple class="hidden">
                    <div id="imageDropZone" class="border-2 border-dashed border-slate-300 rounded-2xl bg-slate-50 p-10 flex flex-col items-center justify-center text-center transition hover:bg-slate-50 cursor-pointer" onclick="document.getElementById('imageUploadInput').click()">
                        <div class="h-12 w-12 rounded-full bg-slate-100 text-primary-800 flex items-center justify-center mb-4">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        </div>
                        <p class="text-[15px] font-bold text-slate-900 mb-1">Drag and drop images here</p>
                        <p class="text-[12px] font-medium text-slate-500 mb-4">PNG, JPG or WEBP up to 10MB each (max 3 images)</p>
                        <button type="button" class="px-5 py-2.5 rounded-xl text-[13px] font-bold text-primary-800 bg-white border border-slate-200 hover:border-primary-600 shadow-sm transition cursor-pointer">
                            Browse Files
                        </button>
                    </div>

                    <!-- Thumbnails -->
                    <div id="imagePreviewGrid" class="flex flex-wrap gap-4 mt-2">
                        <!-- Image 1 placeholder style -->
                        <div class="h-24 w-24 rounded-xl border-2 border-slate-200 bg-slate-100 flex items-center justify-center overflow-hidden relative group">
                            <!-- Dummy box object -->
                            <div class="w-8 h-12 bg-white rounded shadow-sm flex items-center justify-center text-[5px] font-bold">Biogenix</div>
                            <button class="absolute top-1.5 right-1.5 h-6 w-6 rounded bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-sm cursor-pointer"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </div>
                        
                        <!-- Add more square -->
                        <div class="h-24 w-24 rounded-xl border-2 border-dashed border-slate-300 bg-white flex items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-slate-400 transition text-primary-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    </div>
                </div>

                {{-- External Video Links section hidden per business requirement --}}
            </div>

            <!-- Bottom: Documents -->
            <div class="px-6 py-6 border-t border-slate-100 flex flex-col lg:flex-row gap-6">
                <!-- Dropzone for Docs -->
                <div class="flex-1 space-y-3">
                    <label class="block text-[13px] font-bold text-slate-700">Documents & Brochures</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl bg-white p-8 flex flex-col items-center text-center transition hover:bg-slate-50 cursor-pointer">
                        <div class="h-8 w-8 rounded-lg bg-slate-100 text-primary-800 flex items-center justify-center mb-3">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <p class="text-[13px] font-bold text-slate-900 mb-1">Upload Technical Datasheets</p>
                        <p class="text-[11px] font-medium text-slate-500 mb-3">PDF, DOCX or PPT up to 20MB each</p>
                        <button type="button" class="px-4 py-2 rounded-lg text-[12px] font-bold text-slate-700 bg-white border border-slate-200 hover:border-primary-600 shadow-sm transition cursor-pointer">
                            Browse Documents
                        </button>
                    </div>
                </div>

                <!-- Attached list -->
                <div class="flex-1 lg:max-w-md space-y-3 mt-[26px]">
                    
                    <div class="flex items-center justify-between px-4 py-3 bg-white border border-slate-200 shadow-sm rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded bg-rose-50 text-rose-600 flex items-center justify-center text-[9px] font-black tracking-widest uppercase">
                                PDF
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-slate-900 truncate">Technical_Specs_v2.pdf</p>
                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">1.2 MB</p>
                            </div>
                        </div>
                        <button class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                    </div>

                    <div class="flex items-center justify-between px-4 py-3 bg-white border border-slate-200 shadow-sm rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded bg-primary-50 text-primary-600 flex items-center justify-center text-[9px] font-black tracking-widest uppercase">
                                DOC
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-slate-900 truncate">Usage_Guidelines.docx</p>
                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">850 KB</p>
                            </div>
                        </div>
                        <button class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                    </div>

                </div>
            </div>

        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
// ─── Dynamic Data Fetching (from URL) ───
(function populateEditForm() {
    const params = new URLSearchParams(window.location.search);
    const fields = {
        'productName': 'name',
        'productSku': 'sku',
        'productStock': 'stock'
    };

    for (const [id, param] of Object.entries(fields)) {
        const el = document.getElementById(id);
        if (el && params.has(param)) {
            el.value = params.get(param);
        }
    }
    
    const subTitle = document.querySelector('p.text-sm.text-slate-500.mt-1');
    if (subTitle && params.has('sku')) {
        subTitle.textContent = `Update details for #${params.get('sku')}.`;
    }
})();

// ─── Form Validation ───
const requiredFields = [
    {id:'productName', label:'Product Name'},
    {id:'productSku', label:'CAT No'},
    {id:'productDesc', label:'Description'},
    {id:'productStock', label:'Stock Qty'}
];

function validateProductForm() {
    let valid = true;
    let firstInvalid = null;
    document.querySelectorAll('.field-error').forEach(e => e.remove());
    document.querySelectorAll('.border-rose-400').forEach(e => e.classList.remove('border-rose-400','ring-1','ring-rose-200'));
    requiredFields.forEach(f => {
        const el = document.getElementById(f.id);
        if (!el) return;
        const val = el.value.trim();
        if (!val) {
            valid = false;
            el.classList.add('border-rose-400','ring-1','ring-rose-200');
            const err = document.createElement('p');
            err.className = 'field-error text-[11px] font-semibold text-rose-500 mt-1';
            err.textContent = f.label + ' is required';
            el.parentElement.appendChild(err);
            if (!firstInvalid) firstInvalid = el;
        }
    });
    if (firstInvalid) firstInvalid.scrollIntoView({behavior:'smooth', block:'center'});
    return valid;
}

// Clear error on input
requiredFields.forEach(f => {
    const el = document.getElementById(f.id);
    if (!el) return;
    el.addEventListener('input', () => {
        el.classList.remove('border-rose-400','ring-1','ring-rose-200');
        const err = el.parentElement.querySelector('.field-error');
        if (err) err.remove();
    });
});

document.getElementById('saveProductBtn')?.addEventListener('click', function() {
    if (!validateProductForm()) {
        AdminToast.show('Please fill in all required fields', 'error');
        return;
    }
    AdminBtnLoading.start(this);
    setTimeout(() => {
        AdminBtnLoading.stop(this);
        AdminToast.show('Product updated successfully!', 'success');
    }, 1500);
});

// ─── Add Specification Row ───
document.getElementById('addSpecBtn')?.addEventListener('click', () => {
    const container = document.getElementById('specRows');
    const row = document.createElement('div');
    row.className = 'flex items-end gap-4';
    row.innerHTML = `
        <div class="flex-1 space-y-2">
            <label class="block text-[10px] uppercase tracking-widest font-bold text-slate-500">Attribute Key</label>
            <input type="text" placeholder="e.g. Shelf Life" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
        </div>
        <div class="flex-1 space-y-2">
            <label class="block text-[10px] uppercase tracking-widest font-bold text-slate-500">Attribute Value</label>
            <input type="text" placeholder="12 months" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="h-11 w-11 flex-shrink-0 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-100 hover:text-rose-600 transition flex items-center justify-center border border-rose-100 cursor-pointer">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        </button>
    `;
    container.appendChild(row);
    row.querySelector('input').focus();
    AdminToast.show('New specification row added', 'info');
});

// ─── Image Upload with Preview ───
const imageInput = document.getElementById('imageUploadInput');
const previewGrid = document.getElementById('imagePreviewGrid');
const dropZone = document.getElementById('imageDropZone');

if (imageInput) {
    imageInput.addEventListener('change', handleImageFiles);
    // Drag and drop
    if (dropZone) {
        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-primary-600','bg-primary-50/50'); });
        dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('border-primary-600','bg-primary-50/50'); });
        dropZone.addEventListener('drop', e => {
            e.preventDefault(); dropZone.classList.remove('border-primary-600','bg-primary-50/50');
            if (e.dataTransfer.files.length) { imageInput.files = e.dataTransfer.files; handleImageFiles(); }
        });
    }
}

function handleImageFiles() {
    const files = imageInput.files;
    Array.from(files).forEach(file => {
        if (!file.type.match('image.*')) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            // Remove any existing "add more" placeholder before appending
            const addMore = previewGrid.querySelector('.add-more-img');
            const thumb = document.createElement('div');
            thumb.className = 'h-24 w-24 rounded-xl border-2 border-slate-200 overflow-hidden relative group';
            thumb.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover" alt="preview">
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-1.5 right-1.5 h-6 w-6 rounded bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-sm cursor-pointer">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            `;
            if (previewGrid.querySelectorAll('.h-24.w-24').length >= 3) {
                AdminToast.show('Maximum 3 images allowed', 'warning');
                return;
            }
            if (addMore) previewGrid.insertBefore(thumb, addMore);
            else previewGrid.appendChild(thumb);
            
            // Disable upload if 3 images reached
            if (previewGrid.querySelectorAll('.h-24.w-24').length >= 3) {
                if (dropZone) dropZone.classList.add('opacity-50', 'pointer-events-none');
                if (addMore) addMore.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    });
    AdminToast.show(`${files.length} image(s) added`, 'success');
}
</script>
@endpush

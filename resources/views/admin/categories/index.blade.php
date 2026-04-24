@extends('admin.layout')

@section('title', 'Category Management - Biogenix')

@section('admin_content')

<div class="max-w-5xl">

    <!-- Header Area matching Admin Dashboard -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[var(--ui-border)] pb-4">
        <div>
            <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Category Management</h2>
            <p class="text-sm text-[var(--ui-text-muted)] mt-1">Manage product categories, HSM codes, and GST rates.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" placeholder="Search database..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-9 pr-4 py-2.5 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-[var(--ui-text-muted)]">
            </div>
        </div>
    </div>

    <!-- Main Card Header -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Category Workspace</h3>
        <button onclick="toggleModal('addCategoryModal', true)" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-md hover:-translate-y-0.5 transition flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Add New Category
        </button>
    </div>

    <!-- Interface Card -->
    <div class="bg-[var(--ui-surface)] rounded-2xl shadow-[var(--ui-shadow-soft)] border border-[var(--ui-card-border)] p-6 lg:p-8">
        <form id="categoryDetailsForm" action="{{ route('admin.categories.update') }}" method="POST">
            @csrf

            <div class="mb-8">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">System Navigation</label>
                <select id="categoryNavigation" name="category_id" class="w-full border border-[var(--ui-border)] bg-[var(--ui-input-bg)] text-[var(--ui-text)] font-medium rounded-lg text-sm py-2.5 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition">
                    <option value="">Select Category</option>
                    @foreach($categoryList as $category)
                        <option
                            value="{{ $category->id }}"
                            data-category-hsm-code="{{ $category->hsm_code ?? '' }}"
                            data-category-application="{{ $category->application ?? '' }}"
                            data-category-gst-rate="{{ $category->gst_rate !== null ? number_format((float) $category->gst_rate, 2, '.', '') : '' }}"
                            {{ (int) old('category_id', $selectedCategory?->id) === $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-[1fr_1.5fr] gap-x-8 gap-y-6 mb-8">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">HSM Code</label>
                    <div class="relative">
                        <input id="categoryHsmCode" name="hsm_code" type="text" value="{{ old('hsm_code', $selectedCategory?->hsm_code ?? '') }}" placeholder="e.g. 2801.10.00" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] text-[var(--ui-text)] text-sm rounded-lg py-2.5 pl-3 pr-10 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none font-medium placeholder:text-[var(--ui-text-muted)] transition">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 5h2v14H4V5zm4 0h1v14H8V5zm2 0h3v14h-3V5zm4 0h1v14h-1V5zm2 0h2v14h-2V5zm3 0h1v14h-1V5z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-[11px] text-[var(--ui-text-muted)] mt-2 font-medium italic">Harmonized System of Nomenclature code for international classification.</p>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Global Specifications Per Category</label>
                    <input id="categoryApplicationInput" name="application" type="text" value="{{ old('application', $selectedCategory?->application ?? '') }}" placeholder="Enter single-line technical requirement summary..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] text-[var(--ui-text)] text-sm rounded-lg py-2.5 px-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none font-medium placeholder:text-[var(--ui-text-muted)] transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-[1fr_1.5fr] gap-x-8 mb-10">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">GST Rate (%)</label>
                    <div class="relative">
                        <input id="categoryGstRateInput" name="gst_rate" type="text" value="{{ old('gst_rate', $selectedCategory?->gst_rate !== null ? number_format((float) $selectedCategory->gst_rate, 2, '.', '') : '') }}" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] text-[var(--ui-text)] text-sm rounded-lg py-2.5 pl-3 pr-8 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none font-medium transition">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-slate-400 text-sm font-medium">%</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-[var(--ui-text-muted)] mt-2 font-medium italic">Applicable tax rate for the selected category region.</p>
                </div>
            </div>

            <div class="flex justify-end pt-5 border-t border-[var(--ui-border)]">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold py-2.5 px-6 rounded-lg shadow-md flex items-center gap-2 hover:-translate-y-0.5 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Overlay for Add New Category -->
<div id="addCategoryModal" class="fixed inset-0 z-[1000] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0 duration-300">
    <div id="addCategoryModal-content" class="relative flex w-full max-w-[640px] flex-col rounded-2xl bg-[var(--ui-surface)] shadow-2xl scale-95 transition-transform duration-300 pointer-events-auto overflow-hidden mx-4">

        <form id="addCategoryForm" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Modal Header -->
            <div class="flex items-start justify-between px-7 py-5 border-b border-[var(--ui-border)] relative bg-[var(--ui-surface)] z-10">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">ARCHITECTURE ENGINE</p>
                    <h3 class="text-xl font-extrabold text-[var(--ui-text)] tracking-tight">Add New Category</h3>
                </div>
                <button data-modal-close="addCategoryModal" class="text-slate-400 hover:text-rose-600 bg-transparent hover:bg-rose-50 rounded-lg p-1.5 transition cursor-pointer -mt-1 -mr-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-7 py-7 bg-[var(--ui-surface)]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Category Name <span class="text-rose-500">*</span></label>
                            <input name="name" type="text" value="{{ old('name') }}" placeholder="e.g. Molecular Reagents" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-lg text-sm py-2.5 px-3 outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600 font-medium text-[var(--ui-text)] placeholder:text-[var(--ui-text-muted)] transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Category Description</label>
                            <textarea name="description" placeholder="Describe the utility and storage requirements..." rows="4" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] rounded-lg text-sm py-2.5 px-3 outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600 font-medium text-[var(--ui-text)] placeholder:text-[var(--ui-text-muted)] transition resize-none h-[110px]">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Upload Category Image</label>
                        <input id="categoryImageInput" name="category_image" type="file" accept="image/png,image/jpeg,image/webp" class="hidden">
                        <div id="categoryImageDropZone" class="group border-[2px] border-dashed border-[var(--ui-border)] rounded-xl bg-[var(--ui-surface-subtle)] flex flex-col items-center justify-center p-6 h-[190px] hover:bg-primary-50/50 hover:border-primary-300 transition-colors cursor-pointer">
                            <div class="w-12 h-12 rounded-xl bg-[var(--ui-surface)] text-primary-600 shadow-sm border border-[var(--ui-border)] flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.36 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                            </div>
                            <p class="text-[13px] font-bold text-[var(--ui-text)]">Drop your image here</p>
                            <p class="text-[11px] font-medium text-[var(--ui-text-muted)] mt-1">PNG, JPG or WebP up to 5MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-7 py-4 flex items-center justify-end gap-4 bg-[var(--ui-surface-subtle)] border-t border-[var(--ui-border)] rounded-b-2xl z-10">
                <button data-modal-close="addCategoryModal" type="button" class="text-sm font-bold text-[var(--ui-text-muted)] hover:text-slate-800 transition cursor-pointer">Cancel</button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold py-2.5 px-6 rounded-lg shadow-[var(--ui-shadow-soft)] hover:-translate-y-0.5 transition cursor-pointer">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>


<script>
(function () {
    // Get the category workspace fields.
    const categoryDetailsForm = document.getElementById('categoryDetailsForm');
    const categoryNavigation = document.getElementById('categoryNavigation');
    const categoryHsmCode = document.getElementById('categoryHsmCode');
    const categoryApplicationInput = document.getElementById('categoryApplicationInput');
    const categoryGstRateInput = document.getElementById('categoryGstRateInput');
    const addCategoryForm = document.getElementById('addCategoryForm');
    const categoryImageInput = document.getElementById('categoryImageInput');
    const categoryImageDropZone = document.getElementById('categoryImageDropZone');

    // Show the selected category details in the current fields.
    function showSelectedCategoryDetails() {
        const selectedOption = categoryNavigation.options[categoryNavigation.selectedIndex];

        if (!selectedOption || selectedOption.value === '') {
            categoryHsmCode.value = '';
            categoryApplicationInput.value = '';
            categoryGstRateInput.value = '';

            return;
        }

        // Read the saved HSM value.
        const categoryHsmValue = selectedOption.dataset.categoryHsmCode || '';

        // Read the saved application value.
        const categoryApplicationValue = selectedOption.dataset.categoryApplication || '';

        // Read the saved GST value.
        const categoryGstRateValue = selectedOption.dataset.categoryGstRate || '';

        // Fill the current workspace fields.
        categoryHsmCode.value = categoryHsmValue;
        categoryApplicationInput.value = categoryApplicationValue;
        categoryGstRateInput.value = categoryGstRateValue;
    }

    // Open and close the modal.
    window.toggleModal = function (id, open) {
        const modal = document.getElementById(id);

        if (!modal) {
            return;
        }

        if (open) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');

                const modalContent = document.getElementById(id + '-content');

                if (modalContent) {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }
            });

            document.body.classList.add('overflow-hidden');
        } else {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');

            const modalContent = document.getElementById(id + '-content');

            if (modalContent) {
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }, 250);
        }
    };

    // Close the modal from its buttons or backdrop.
    document.addEventListener('click', function (e) {
        const closeButton = e.target.closest('[data-modal-close]');

        if (closeButton) {
            const modalId = closeButton.getAttribute('data-modal-close');
            window.toggleModal(modalId, false);
        }

        const modal = e.target.closest('.fixed.inset-0');

        if (modal && e.target === modal) {
            window.toggleModal(modal.id, false);
        }
    });

    // Close the modal with the Escape key.
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            window.toggleModal('addCategoryModal', false);
        }
    });

    // Filter the category list from the search field.
    const searchInput = document.querySelector('input[placeholder="Search database..."]');

    if (searchInput && categoryNavigation) {
        searchInput.addEventListener('input', function () {
            const searchText = this.value.toLowerCase();

            categoryNavigation.querySelectorAll('option').forEach(option => {
                if (option.value === '') {
                    option.hidden = false;

                    return;
                }

                const optionName = option.textContent.toLowerCase();
                option.hidden = searchText ? !optionName.includes(searchText) : false;
            });
        });
    }

    // Update the current fields when the category changes.
    if (categoryNavigation) {
        categoryNavigation.addEventListener('change', function () {
            showSelectedCategoryDetails();
        });

        showSelectedCategoryDetails();
    }

    // Open the image picker from the upload card.
    if (categoryImageDropZone && categoryImageInput) {
        categoryImageDropZone.addEventListener('click', function () {
            categoryImageInput.click();
        });
    }

    // Submit only when one category is selected.
    if (categoryDetailsForm) {
        categoryDetailsForm.addEventListener('submit', function (event) {
            if (!categoryNavigation || categoryNavigation.value === '') {
                event.preventDefault();
                AdminToast.show('Please select a category first.', 'error');
            }
        });
    }

    // Submit only when the new category has a name.
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', function (event) {
            const categoryNameInput = addCategoryForm.querySelector('input[name="name"]');

            if (!categoryNameInput || categoryNameInput.value.trim() === '') {
                event.preventDefault();
                AdminToast.show('Please enter the category name.', 'error');
            }
        });
    }

    // Reopen the modal when the create form returns with previous values.
    @if($errors->has('name') || $errors->has('description') || $errors->has('category_image') || old('name') || old('description'))
        window.toggleModal('addCategoryModal', true);
    @endif
})();
</script>

@endsection

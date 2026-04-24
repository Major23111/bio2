@extends('admin.layout')

@section('title', 'FAQ Management - Biogenix Admin')

@section('admin_content')



    <!-- Welcome Header -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">FAQ Management</h1>
            <p class="text-sm text-[var(--ui-text-muted)] mt-1">Manage public FAQ content, display order, and status.</p>
        </div>
        <a href="{{ route('faq') }}" target="_blank" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition cursor-pointer flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            Public FAQ Page
        </a>
    </div>

    <div class="space-y-6">
        <!-- Stats Sidebar Grid -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[var(--ui-shadow-soft)] md:p-8">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total FAQs</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $faqSummary['total_faqs'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Active FAQs</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $faqSummary['active_faqs'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Categories</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $faqSummary['categories'] }}</p>
                </div>
            </div>
        </section>

        @if (session('status'))
            <div class="rounded-xl border border-primary-200 bg-primary-50 px-4 py-3 text-[13px] font-bold text-primary-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-[13px] text-rose-700">
                <p class="font-bold uppercase tracking-widest text-[10px] mb-2 text-rose-500">Validation Errors</p>
                <ul class="space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <span class="h-1 w-1 rounded-full bg-rose-400"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FAQ Form -->
        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">{{ $editingFaq ? 'Update FAQ' : 'Add New FAQ' }}</h2>
                <p class="text-sm text-slate-500 mt-1">Keep questions clear and concise for better customer experience.</p>
            </div>

            <form method="POST" action="{{ $editingFaq ? route('admin.faqs.update', $editingFaq->id) : route('admin.faqs.store') }}" class="space-y-4">
                @csrf
                @if ($editingFaq) @method('PUT') @endif

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Category</label>
                        <select name="category" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                            <option value="">Select category</option>
                            @foreach ($faqCategoryOptions as $faqCategoryOption)
                                <option value="{{ $faqCategoryOption }}" @selected(old('category', $editingFaq->category ?? '') === $faqCategoryOption)>
                                    {{ $faqCategoryOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Sort Order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $editingFaq->sort_order ?? 0) }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Question</label>
                    <input name="question" value="{{ old('question', $editingFaq->question ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Answer</label>
                    <textarea name="answer" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 min-h-[140px] focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 font-medium" required>{{ old('answer', $editingFaq->answer ?? '') }}</textarea>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/50 p-4 transition cursor-pointer hover:bg-white">
                        <input type="hidden" name="is_default_open" value="0">
                        <input type="checkbox" name="is_default_open" value="1" @checked(old('is_default_open', $editingFaq?->is_default_open ?? false)) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
                        <div class="flex flex-col">
                            <span class="text-[13px] font-bold text-slate-900">Default Expanded</span>
                            <span class="text-[11px] text-slate-400 font-medium mt-1">Make this FAQ open by default on the public page.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/50 p-4 transition cursor-pointer hover:bg-white">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingFaq?->is_active ?? true)) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
                        <div class="flex flex-col">
                            <span class="text-[13px] font-bold text-slate-900">Active</span>
                            <span class="text-[11px] text-slate-400 font-medium mt-1">Visible on the public FAQ page.</span>
                        </div>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <button class="px-6 py-2.5 rounded-xl text-sm font-extrabold text-white bg-primary-600 hover:bg-primary-700 transition shadow-lg shadow-primary-600/20 cursor-pointer" type="submit">
                        {{ $editingFaq ? 'Update FAQ' : 'Add FAQ' }}
                    </button>
                    @if ($editingFaq)
                        <a class="ajax-link px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition cursor-pointer" href="{{ route('admin.faqs.index') }}">Cancel</a>
                    @endif
                </div>
            </form>
        </section>

        <!-- FAQs Table -->
        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[var(--ui-shadow-soft)]">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Existing FAQs</h2>
                    <p class="text-sm text-slate-500 mt-1">Review wording and display order.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-[11px] font-bold text-primary-700 border border-primary-200/60">{{ $faqs->count() }} FAQs</span>
            </div>

            @if ($faqs->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-16">Sr.</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Question</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($faqs as $faq)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <span class="text-[13px] font-bold text-slate-400">#{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-700 border border-slate-200/60 text-[10px] font-black uppercase tracking-widest rounded-full">{{ $faq->category }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col max-w-sm truncate">
                                            <span class="text-[13px] font-bold text-slate-900 truncate">{{ $faq->question }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium truncate mt-0.5">{{ $faq->answer }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5">
                                            @if ($faq->is_active)
                                                <span class="inline-flex items-center w-fit px-2 py-0.5 bg-primary-50 text-primary-600 text-[10px] font-black uppercase tracking-widest rounded border border-primary-200/60">Active</span>
                                            @else
                                                <span class="inline-flex items-center w-fit px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded border border-rose-200/60">Hidden</span>
                                            @endif

                                            @if ($faq->is_default_open)
                                                <span class="inline-flex items-center w-fit px-2 py-0.5 bg-secondary-50 text-secondary-700 text-[9px] font-black uppercase tracking-widest rounded border border-secondary-200/60">Expanded</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a class="ajax-link text-primary-600 hover:text-primary-700 transition font-bold text-xs uppercase tracking-widest" href="{{ route('admin.faqs.show', $faq->id) }}">Edit</a>
                                            <form method="POST" action="{{ route('admin.faqs.delete', $faq->id) }}" class="faq-delete-form">
                                                @csrf @method('DELETE')
                                                <button class="text-rose-600 hover:text-rose-700 transition font-bold text-xs uppercase tracking-widest cursor-pointer" type="button"
                                                    onclick="confirmDeleteFaq(this)">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 px-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <p class="text-[13px] font-bold text-slate-600 uppercase tracking-widest">No FAQs Found</p>
                    <p class="text-xs text-slate-400 mt-1">Start by adding your first FAQ question.</p>
                </div>
            @endif
        </section>
    </div>

<script>
(function () {
    window.confirmDeleteFaq = function (btn) {
        var form = btn.closest('form');
        if (!form) return;
        if (window.AdminConfirm) {
            window.AdminConfirm.show({
                title: 'Delete FAQ',
                message: 'Are you sure you want to permanently delete this FAQ? This action cannot be undone.',
                confirmText: 'Delete',
                danger: true
            }).then(function (confirmed) {
                if (confirmed) form.submit();
            });
        } else {
            if (confirm('Delete this FAQ?')) form.submit();
        }
    };
})();
</script>

@endsection

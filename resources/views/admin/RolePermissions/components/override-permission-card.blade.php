@props([
    'title' => '',
    'description' => '',
    'checked' => false,
    'id' => uniqid('perm_')
])

<label for="{{ $id }}" class="group relative flex flex-col p-5 rounded-[20px] border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:shadow-md hover:border-primary-100 cursor-pointer active:scale-[0.98]">
    <div class="flex items-start justify-between gap-4 mb-2">
        <div class="flex-1">
            <h4 class="text-[13px] font-extrabold text-slate-800 tracking-tight group-hover:text-primary-600 transition-colors">{{ $title }}</h4>
        </div>
        <div class="relative flex items-center justify-center h-5 w-5 shrink-0">
            <input type="checkbox" id="{{ $id }}" {{ $checked ? 'checked' : '' }} class="peer h-5 w-5 rounded-md border-slate-200 text-primary-600 focus:ring-primary-600/20 transition-all cursor-pointer">
            <div class="absolute inset-0 rounded-md bg-primary-600 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-200 flex items-center justify-center pointer-events-none">
                <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            </div>
        </div>
    </div>
    <p class="text-[11px] font-medium text-slate-400 leading-snug group-hover:text-slate-500 transition-colors">{{ $description }}</p>
</label>

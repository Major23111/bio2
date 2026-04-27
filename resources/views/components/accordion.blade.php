@props([
    'title',
    'open' => false,
])

<details {{ $attributes->class(['group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition']) }} @if($open) open @endif>
    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 text-left text-[1.1rem] font-semibold text-slate-900 marker:hidden [&::-webkit-details-marker]:hidden">
        <span>{{ $title }}</span>
        <svg class="h-5 w-5 shrink-0 text-slate-400 transition duration-200 group-open:rotate-180 group-open:text-primary-700" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </summary>
    <div class="grid grid-rows-[0fr] transition-all duration-200 group-open:grid-rows-[1fr]">
        <div class="overflow-hidden">
            <div class="border-t border-slate-100 px-6 py-5 text-base leading-relaxed text-slate-700">
            {{ $slot }}
            </div>
        </div>
    </div>
</details>

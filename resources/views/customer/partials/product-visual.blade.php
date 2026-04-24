<div class="{{ $class ?? '' }} bg-slate-100/50 flex flex-col items-center justify-center text-slate-400 border border-slate-200/50 relative overflow-hidden">
    <svg class="w-12 h-12 mb-3 relative z-10 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <span class="text-xs font-bold uppercase tracking-widest relative z-10 opacity-70">{{ $variant ?? 'Product' }} Placeholder</span>
    
    <!-- Subtle background pattern to make it look nicer -->
    <div class="absolute inset-0 z-0 opacity-20">
        <svg fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full text-slate-300">
            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
            </pattern>
            <rect width="100" height="100" fill="url(#grid)" />
        </svg>
    </div>
</div>

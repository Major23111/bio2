<div id="globalPageLoader" aria-hidden="true" class="fixed inset-0 z-[9999] opacity-100 visible transition-opacity duration-200">
    <div class="h-full w-full overflow-hidden relative bg-[#051009]">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(26,77,46,0.4),transparent_50%),radial-gradient(circle_at_bottom,rgba(253,224,71,0.12),transparent_40%)]"></div>

        {{-- Top Left Logo --}}
        <div class="absolute left-6 top-6 z-20 flex items-center gap-3 md:left-10 md:top-10">
            <img src="{{ $loaderLogoSrc }}" alt="Biogenix" class="h-12 w-auto md:h-[60px]" decoding="sync">
        </div>

        <div class="relative flex h-full w-full flex-col items-center justify-center px-4">
            <div class="relative flex flex-col items-center text-center">
                {{-- Main Heading --}}
                <h1 class="font-display text-2xl font-bold tracking-tight text-white md:text-3xl lg:text-4xl">
                    Preparing Your Biogenix Workspace
                </h1>

                {{-- Rotating Subtext Container --}}
                <div class="mt-6 h-8 text-center sm:mt-8">
                    <p id="rotatingLoaderText" class="text-sm font-medium tracking-wide text-primary-200/80 transition-all duration-500 md:text-base">
                        Loading relevant diagnostics and solutions
                    </p>
                </div>

                {{-- Loading Bar --}}
                <div class="mt-8 w-64 max-w-[80vw] sm:mt-10">
                    <div class="h-[2px] w-full overflow-hidden rounded-full bg-white/10">
                        <div class="loader-bar h-full rounded-full bg-gradient-to-r from-emerald-500 via-green-600 to-secondary-500"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Line --}}
        <div class="absolute bottom-10 left-0 w-full text-center">
            <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-white/30 md:text-[11px]">
                Biogenix — Precision. Reliability. Scale
            </p>
        </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const rotatingTexts = [
            "Loading relevant diagnostics and solutions",
            "Syncing real-time product availability",
            "Aligning pricing and procurement data",
            "Preparing your experience",
            "Optimizing your workflow"
        ];
        
        let textIndex = 0;
        const textEl = document.getElementById('rotatingLoaderText');
        
        if (textEl) {
            setInterval(() => {
                textIndex = (textIndex + 1) % rotatingTexts.length;
                
                // Fade out
                textEl.style.opacity = '0';
                textEl.style.transform = 'translateY(5px)';
                
                setTimeout(() => {
                    textEl.textContent = rotatingTexts[textIndex];
                    // Fade in
                    textEl.style.opacity = '1';
                    textEl.style.transform = 'translateY(0)';
                }, 500);
            }, 3000);
        }
    })();
</script>

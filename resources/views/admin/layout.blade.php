@extends('layouts.app')

@section('customer_minimal', 'minimal')

@section('content')
<div class="min-h-screen bg-[var(--ui-page-bg)] py-4 lg:py-8">
    <div class="mx-auto flex w-full max-w-[96rem] gap-0 lg:gap-8 px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
        
        {{-- Mobile sidebar toggle --}}
        <button id="mobile-sidebar-toggle" class="fixed bottom-6 left-6 z-[999] lg:hidden h-12 w-12 rounded-full bg-primary-600 text-white shadow-lg flex items-center justify-center hover:bg-primary-700 transition cursor-pointer">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        {{-- Mobile sidebar overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-[997] bg-slate-900/50 backdrop-blur-sm hidden lg:hidden transition-opacity opacity-0"></div>

        <!-- Sidebar Navigation -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <main id="admin-main-content" class="flex-1 min-w-0 space-y-6 pb-12 transition-opacity duration-200">
            @yield('admin_content')
            @stack('scripts')
        </main>
    </div>
</div>

{{-- Global partials --}}
@include('admin.partials.toast')
@include('admin.partials.confirm-modal')

{{-- Loading skeleton overlay --}}
<div id="admin-loading-skeleton" class="hidden absolute inset-0 z-10">
    <div class="space-y-6 animate-pulse p-1">
        <div class="h-8 bg-[var(--ui-skeleton-bg)]/60 rounded-xl w-2/5"></div>
        <div class="h-5 bg-[var(--ui-skeleton-bg)]/40 rounded-lg w-3/5"></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
            <div class="h-[130px] bg-[var(--ui-skeleton-bg)]/50 rounded-2xl"></div>
            <div class="h-[130px] bg-[var(--ui-skeleton-bg)]/50 rounded-2xl"></div>
            <div class="h-[130px] bg-[var(--ui-skeleton-bg)]/50 rounded-2xl hidden sm:block"></div>
            <div class="h-[130px] bg-[var(--ui-skeleton-bg)]/50 rounded-2xl hidden sm:block"></div>
        </div>
        <div class="h-[300px] bg-[var(--ui-skeleton-bg)]/40 rounded-2xl mt-4"></div>
    </div>
</div>

{{-- Scroll-to-top button (Admin specific green one) --}}
<button id="admin-scroll-top" style="bottom: 7.5rem;" class="fixed right-6 z-[990] h-10 w-10 rounded-full bg-primary-600 text-white shadow-lg flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:bg-primary-700 translate-y-4 cursor-pointer">
    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
</button>

<style>
    /* Hide the global storefront white back-to-top button to prevent duplicates */
    #backToTopBtn { display: none !important; }

    /* Button loading state utility */
    .btn-loading { position: relative; color: transparent !important; pointer-events: none; }
    .btn-loading::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,0.3); border-top-color: white;
        border-radius: 50%; animation: btnSpin 0.6s linear infinite;
    }
    .btn-loading-dark::after { border-color: rgba(15,23,42,0.2); border-top-color: #0f172a; }
    @keyframes btnSpin { to { transform: translate(-50%, -50%) rotate(360deg); } }

    /* Sidebar mobile transitions */
    @media (max-width: 1023px) {
        aside#admin-sidebar {
            position: fixed !important; top: 0; left: 0; bottom: 0; z-index: 998;
            transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            width: 260px; overflow-y: auto; height: 100vh;
            background: white; border-radius: 0; margin: 0; padding: 0;
        }
        aside#admin-sidebar > nav {
            border-radius: 0; border: none; box-shadow: none;
            height: 100%; overflow-y: auto;
        }
        aside#admin-sidebar.sidebar-open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,0.12); }
    }

    /* Skeleton shimmer */
    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    .skeleton-shimmer {
        background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
        background-size: 200% 100%; animation: shimmer 1.5s ease-in-out infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mainContent = document.getElementById('admin-main-content');
        const loadingSkeleton = document.getElementById('admin-loading-skeleton');
        const scrollTopBtn = document.getElementById('admin-scroll-top');
        
        // ─── Scroll-to-top button visibility ───
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                scrollTopBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                scrollTopBtn.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
            } else {
                scrollTopBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                scrollTopBtn.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
            }
        });
        scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        // ─── Mobile sidebar toggle ───
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileToggle = document.getElementById('mobile-sidebar-toggle');

        function openSidebar() {
            sidebar.classList.add('sidebar-open');
            sidebarOverlay.classList.remove('hidden');
            requestAnimationFrame(() => sidebarOverlay.classList.replace('opacity-0', 'opacity-100'));
        }
        function closeSidebar() {
            sidebar.classList.remove('sidebar-open');
            sidebarOverlay.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => sidebarOverlay.classList.add('hidden'), 300);
        }

        mobileToggle.addEventListener('click', () => {
            sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
        });
        sidebarOverlay.addEventListener('click', closeSidebar);

        // ─── Reusable AJAX page loader ───
        const loadPage = async (url) => {
            // Show skeleton
            mainContent.style.position = 'relative';
            mainContent.classList.add('opacity-40');
            loadingSkeleton.classList.remove('hidden');
            mainContent.prepend(loadingSkeleton);
            
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newContent = doc.getElementById('admin-main-content');
                if (newContent) {
                    mainContent.innerHTML = newContent.innerHTML;
                    
                    // Force execution of inline scripts
                    const scripts = mainContent.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });
                    
                    if(window.initCustomSelects) setTimeout(window.initCustomSelects, 50);

                    window.history.pushState({}, '', url);
                    document.title = doc.title;
                    
                    // Scroll to top after content swap
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Failed to load page:', error);
                window.location.href = url;
            } finally {
                loadingSkeleton.classList.add('hidden');
                mainContent.classList.remove('opacity-40');
                mainContent.style.position = '';
            }
        };

        // ─── Click interceptor for sidebar links AND .ajax-link elements ───
        document.body.addEventListener('click', async (e) => {
            const link = e.target.closest('#admin-sidebar a, .ajax-link');
            if (!link || !link.href || link.getAttribute('href') === '#') return;
            
            const url = new URL(link.href);
            if (url.origin !== window.location.origin) return;

            e.preventDefault();
            
            // 1. Determine which sidebar item should be active
            const destPath = url.pathname;
            const sidebarLinks = document.querySelectorAll('#admin-sidebar a');
            let targetSidebarLink = null;

            sidebarLinks.forEach(sLink => {
                const href = sLink.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;

                const sUrl = new URL(sLink.href);
                const sPath = sUrl.pathname;

                // Priority 1: Exact match
                if (destPath === sPath) {
                    targetSidebarLink = sLink;
                } 
                // Priority 2: Sub-path match (if no exact match found yet)
                else if (!targetSidebarLink && destPath.startsWith(sPath) && sPath !== '/adminPanel/dashboard' && sPath !== '/adminPanel') {
                    targetSidebarLink = sLink;
                }
            });

            // 2. Update sidebar highlight
            sidebarLinks.forEach(aEl => {
                aEl.classList.remove('bg-primary-600', 'text-white', 'font-bold');
                aEl.classList.add('text-[var(--ui-text-muted)]', 'hover:bg-[var(--ui-surface-subtle)]', 'hover:text-[var(--ui-text)]');
                const indicator = aEl.querySelector('div.bg-white.rounded-r-md');
                if (indicator) indicator.remove();
                const svg = aEl.querySelector('svg');
                if (svg) { svg.classList.remove('text-indigo-200'); svg.classList.add('text-[var(--ui-text-muted)]'); }
            });

            if (targetSidebarLink) {
                targetSidebarLink.classList.remove('text-[var(--ui-text-muted)]', 'hover:bg-[var(--ui-surface-subtle)]', 'hover:text-[var(--ui-text)]');
                targetSidebarLink.classList.add('bg-primary-600', 'text-white', 'font-bold');
                
                const indicatorDiv = document.createElement('div');
                indicatorDiv.className = 'absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r-md';
                targetSidebarLink.prepend(indicatorDiv);
                
                const linkSvg = targetSidebarLink.querySelector('svg');
                if (linkSvg) { linkSvg.classList.remove('text-[var(--ui-text-muted)]'); linkSvg.classList.add('text-indigo-200'); }
            }

            // 3. Close mobile sidebar if applicable
            if (sidebar.classList.contains('sidebar-open')) {
                closeSidebar();
            }

            await loadPage(link.href);
        });

        // ─── Handle back/forward with AJAX ───
        window.addEventListener('popstate', async () => {
            await loadPage(window.location.href);
        });

        // ─── Button loading state helpers ───
        window.AdminBtnLoading = {
            start(btn) { btn.classList.add('btn-loading'); btn.disabled = true; },
            stop(btn) { btn.classList.remove('btn-loading'); btn.disabled = false; }
        };

        // ─── Click-outside to close dropdown menus ───
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.relative.inline-block')) {
                document.querySelectorAll('.relative.inline-block > div.absolute').forEach(d => d.classList.add('hidden'));
            }
        });

        // ─── Global Custom Select Initializer ───
        window.initCustomSelects = function() {
            document.querySelectorAll('select:not([multiple]):not(.no-custom)').forEach(function(select) {
                if(select.dataset.customized === 'true') return;
                select.dataset.customized = 'true';
                select.style.display = 'none';

                var wrapper = document.createElement('div');
                wrapper.className = 'relative w-full text-left inline-block';

                var trigger = document.createElement('div');
                var baseClasses = select.className.replace(/appearance-none/g, '').replace(/cursor-pointer/g, '');
                if(!baseClasses.includes('py-') && !baseClasses.includes('h-')) baseClasses += ' py-2.5';
                if(!baseClasses.includes('border')) baseClasses += ' border border-slate-200';
                trigger.className = baseClasses + " flex items-center justify-between cursor-pointer focus-within:ring-1 focus-within:ring-primary-600 focus-within:border-primary-600";
                
                var textSpan = document.createElement('span');
                textSpan.className = "block truncate pointer-events-none outline-none";
                trigger.appendChild(textSpan);

                var caret = document.createElement('div');
                caret.className = "pointer-events-none flex items-center pl-2 shrink-0";
                caret.innerHTML = `<svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>`;
                trigger.appendChild(caret);

                var menu = document.createElement('div');
                menu.className = "custom-select-menu hidden absolute left-0 z-[100] mt-1 w-full max-h-60 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-[var(--ui-shadow-card)] p-1";

                function updateText() {
                    var selectedOpt = select.options[select.selectedIndex];
                    textSpan.textContent = selectedOpt ? selectedOpt.text : 'Select...';
                    if (selectedOpt && selectedOpt.disabled) {
                        textSpan.classList.add('text-slate-400');
                    } else {
                        textSpan.classList.remove('text-slate-400');
                    }
                }
                
                Array.from(select.options).forEach(function(opt, index) {
                    var item = document.createElement('div');
                    item.className = "px-3 py-2 text-[14px] font-medium rounded-lg cursor-pointer transition select-none " + 
                                     (opt.disabled ? "text-slate-400 cursor-not-allowed bg-slate-50" : "text-slate-700 hover:bg-primary-50 hover:text-primary-700");
                    item.textContent = opt.text;
                    if(!opt.disabled) {
                        item.addEventListener('click', function(e) {
                            e.stopPropagation();
                            select.selectedIndex = index;
                            // Dispatch with bubbles:true so document-level listeners (e.g. role switch) receive it.
                            select.dispatchEvent(new Event('change', { bubbles: true }));
                            updateText();
                            menu.classList.add('hidden');
                        });
                    }
                    menu.appendChild(item);
                });

                updateText();

                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var hiding = !menu.classList.contains('hidden');
                    document.querySelectorAll('.custom-select-menu').forEach(function(m) { m.classList.add('hidden'); });
                    if(!hiding) menu.classList.remove('hidden');
                });

                select.addEventListener('change', updateText);

                wrapper.appendChild(trigger);
                wrapper.appendChild(menu);
                select.parentNode.insertBefore(wrapper, select);
                wrapper.appendChild(select);
            });
        };

        // Close on outside click
        document.addEventListener('click', function() {
            document.querySelectorAll('.custom-select-menu').forEach(function(m) { m.classList.add('hidden'); });
        });

        window.initCustomSelects();

    });
</script>
@endsection

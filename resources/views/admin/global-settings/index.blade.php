@extends('admin.layout')

@section('title', 'Global Settings - Biogenix Admin')

@section('admin_content')

@php
    $currentMode   = $settings['theme.mode'] ?? 'light';
    $currentPreset = $settings['theme.color_preset'] ?? 'biogenix-green';

    $themeModes = [
        ['title' => 'Light Mode',      'id' => 'light',  'icon_type' => 'sun'],
        ['title' => 'Dark Mode',       'id' => 'dark',   'icon_type' => 'moon'],
        ['title' => 'System Default',  'id' => 'system', 'icon_type' => 'system'],
    ];

    $colorPresets = [
        ['id' => 'biogenix-green', 'label' => 'Biogenix Green',  'hex' => '#1A4D2E'],
        ['id' => 'forest-green',   'label' => 'Forest Green',    'hex' => '#16a34a'],
        ['id' => 'modern-indigo',  'label' => 'Modern Indigo',   'hex' => '#4f46e5'],
        ['id' => 'midnight-black', 'label' => 'Midnight Black',  'hex' => '#0f172a'],
    ];
@endphp

<div class="min-h-[calc(100vh-8rem)] space-y-8 pb-24">
    <div>
        <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">General Configuration</h2>
        <p class="mt-1 text-sm text-[var(--ui-text-muted)]">Manage your personal theme preferences and portal appearance.</p>
    </div>

    {{-- ─── Theme Mode ─── --}}
    <section class="space-y-4">
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-primary-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a1 1 0 011 1v1.05a7.002 7.002 0 015.95 5.95H20a1 1 0 110 2h-1.05a7.002 7.002 0 01-5.95 5.95V20a1 1 0 11-2 0v-1.05a7.002 7.002 0 01-5.95-5.95H4a1 1 0 110-2h1.05a7.002 7.002 0 015.95-5.95V4a1 1 0 011-1zm0 6a3 3 0 100 6 3 3 0 000-6z" />
            </svg>
            <h3 class="text-base font-bold text-[var(--ui-text)]">Theme Mode</h3>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:gap-6">
            @foreach ($themeModes as $mode)
                <button
                    type="button"
                    data-theme-mode="{{ $mode['id'] }}"
                    onclick="selectThemeMode('{{ $mode['id'] }}')"
                    class="cursor-pointer flex min-h-[128px] flex-col items-center justify-center gap-4 rounded-2xl p-6 text-center transition theme-mode-btn
                        {{ $currentMode === $mode['id']
                            ? 'border-2 border-primary-600 bg-[var(--ui-surface-subtle)] shadow-[0_0_0_1px_var(--color-primary-600)]'
                            : 'border border-[var(--ui-border)] bg-[var(--ui-surface)] shadow-[var(--ui-shadow-soft)] hover:border-[var(--ui-text-muted)] hover:bg-[var(--ui-surface-subtle)]' }}">
                    
                    @if ($mode['icon_type'] === 'sun')
                        <svg class="h-8 w-8 text-[var(--ui-text)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364 6.364-1.06-1.06M6.696 6.696 5.636 5.636m12.728 0-1.06 1.06M6.696 17.304l-1.06 1.06M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    @elseif ($mode['icon_type'] === 'moon')
                        <svg class="h-8 w-8 text-[var(--ui-text)]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    @else
                        <svg class="h-8 w-8 text-[var(--ui-text)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25h-13.5A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z" />
                        </svg>
                    @endif

                    <span class="text-[13px] font-bold text-[var(--ui-text)]">{{ $mode['title'] }}</span>
                </button>
            @endforeach
        </div>
    </section>

    {{-- ─── Portal Color Theme ─── --}}
    <section class="space-y-4">
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 -rotate-45 text-primary-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            <h3 class="text-base font-bold text-[var(--ui-text)]">Portal Color Theme</h3>
        </div>

        <div class="rounded-2xl border border-[var(--ui-border)] bg-[var(--ui-surface)] p-6 lg:p-7 shadow-[var(--ui-shadow-soft)]">
            <p class="mb-6 text-[11px] font-bold uppercase tracking-widest text-[var(--ui-text-muted)]">Select a preset palette</p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 lg:gap-6">
                @foreach ($colorPresets as $preset)
                    <button
                        type="button"
                        data-color-preset="{{ $preset['id'] }}"
                        onclick="selectColorPreset('{{ $preset['id'] }}')"
                        class="cursor-pointer flex min-h-[66px] items-center gap-3 rounded-xl p-3.5 text-left transition color-preset-btn
                            {{ $currentPreset === $preset['id']
                                ? 'border-2 border-primary-600 bg-[var(--ui-surface)] shadow-sm'
                                : 'border border-transparent bg-[var(--ui-surface)] hover:border-[var(--ui-border)] hover:bg-[var(--ui-surface-subtle)]' }}">
                        
                        <div class="relative h-8 w-8 flex-shrink-0 rounded-full" style="background:{{ $preset['hex'] }}">
                            @if ($currentPreset === $preset['id'])
                                <span class="absolute inset-[-5px] rounded-full border-2 border-primary-600"></span>
                                <span class="absolute inset-[-9px] rounded-full border border-[var(--ui-border)]"></span>
                            @endif
                        </div>
                        <span class="text-[13px] font-bold {{ $currentPreset === $preset['id'] ? 'text-primary-800' : 'text-[var(--ui-text)]' }}">{{ $preset['label'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── Sticky Save Bar ─── --}}
    <div class="sticky bottom-0 z-10 border-t border-[var(--ui-border)] bg-[var(--ui-surface)]/95 px-2 py-4 backdrop-blur rounded-b-2xl">
        <div class="flex items-center justify-between gap-4">
            <button type="button" id="resetDefaultsBtn" onclick="resetToDefaults()" class="text-[13px] font-bold text-rose-600 transition hover:text-rose-700 cursor-pointer">
                Reset to Defaults
            </button>
            <div class="flex items-center gap-4">
                <button type="button" id="discardBtn" onclick="discardChanges()" class="text-[13px] font-bold text-[var(--ui-text-muted)] transition hover:text-[var(--ui-text)] cursor-pointer hidden">
                    Discard Changes
                </button>
                <button type="button" id="saveBtn" onclick="saveSettings()" class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-8 py-3 text-[13px] font-bold text-white shadow-[0_6px_18px_-6px_rgba(9,27,63,0.45)] transition hover:bg-primary-700 cursor-pointer">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    {{-- ─── JavaScript ─── --}}
    <script>
    (function() {
        var selectedMode   = @json($currentMode);
        var selectedPreset = @json($currentPreset);
        var originalMode   = selectedMode;
        var originalPreset = selectedPreset;

        // ─── Theme Mode Selection ───
        window.selectThemeMode = function(mode) {
            selectedMode = mode;
            updateThemeModeUI(mode);
            applyThemeLive(mode);
            checkForChanges();
        };

        function updateThemeModeUI(activeMode) {
            document.querySelectorAll('.theme-mode-btn').forEach(function(btn) {
                var isActive = btn.getAttribute('data-theme-mode') === activeMode;
                // Remove active classes
                btn.classList.remove('border-2', 'border-primary-600', 'bg-[var(--ui-surface-subtle)]', 'shadow-[0_0_0_1px_var(--color-primary-600)]');
                btn.classList.remove('border', 'border-[var(--ui-border)]', 'bg-[var(--ui-surface)]', 'shadow-[var(--ui-shadow-soft)]');

                if (isActive) {
                    btn.classList.add('border-2', 'border-primary-600', 'bg-[var(--ui-surface-subtle)]', 'shadow-[0_0_0_1px_var(--color-primary-600)]');
                } else {
                    btn.classList.add('border', 'border-[var(--ui-border)]', 'bg-[var(--ui-surface)]', 'shadow-[var(--ui-shadow-soft)]');
                }
            });
        }

        function applyThemeLive(mode) {
            if (mode === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (mode === 'system') {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // ─── Color Preset Selection ───
        window.selectColorPreset = function(preset) {
            selectedPreset = preset;
            updateColorPresetUI(preset);
            applyColorPresetLive(preset);
            checkForChanges();
        };

        function updateColorPresetUI(activePreset) {
            document.querySelectorAll('.color-preset-btn').forEach(function(btn) {
                var isActive = btn.getAttribute('data-color-preset') === activePreset;
                var swatch = btn.querySelector('.relative');
                var label = btn.querySelector('span:last-child');

                btn.classList.remove('border-2', 'border-primary-600', 'shadow-sm', 'border', 'border-transparent');

                if (isActive) {
                    btn.classList.add('border-2', 'border-primary-600', 'shadow-sm');
                    if (label) { label.classList.remove('text-[var(--ui-text)]'); label.classList.add('text-primary-800'); }
                    // Add ring indicators on swatch
                    if (swatch) {
                        swatch.innerHTML = '<span class="absolute inset-[-5px] rounded-full border-2 border-primary-600"></span><span class="absolute inset-[-9px] rounded-full border border-[var(--ui-border)]"></span>';
                    }
                } else {
                    btn.classList.add('border', 'border-transparent');
                    if (label) { label.classList.remove('text-primary-800'); label.classList.add('text-[var(--ui-text)]'); }
                    if (swatch) { swatch.innerHTML = ''; }
                }
            });
        }

        function applyColorPresetLive(preset) {
            if (preset === 'biogenix-green') {
                document.documentElement.removeAttribute('data-theme-color');
            } else {
                document.documentElement.setAttribute('data-theme-color', preset);
            }
        }

        // ─── Change Detection ───
        function checkForChanges() {
            var changed = (selectedMode !== originalMode) || (selectedPreset !== originalPreset);
            var discardBtn = document.getElementById('discardBtn');
            if (discardBtn) {
                discardBtn.classList.toggle('hidden', !changed);
            }
        }

        // ─── Save ───
        window.saveSettings = function() {
            var btn = document.getElementById('saveBtn');
            if (window.AdminBtnLoading) window.AdminBtnLoading.start(btn);

            fetch('{{ route("admin.global-settings.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    theme_mode: selectedMode,
                    theme_color_preset: selectedPreset
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (window.AdminBtnLoading) window.AdminBtnLoading.stop(btn);

                if (data.success) {
                    originalMode = selectedMode;
                    originalPreset = selectedPreset;
                    checkForChanges();

                    if (window.BiogenixToast) {
                        window.BiogenixToast.show(data.message || 'Settings saved!', 'success');
                    }
                } else {
                    if (window.BiogenixToast) {
                        window.BiogenixToast.show(data.message || 'Failed to save.', 'error');
                    }
                }
            })
            .catch(function(err) {
                if (window.AdminBtnLoading) window.AdminBtnLoading.stop(btn);
                console.error(err);
                if (window.BiogenixToast) {
                    window.BiogenixToast.show('Network error. Please try again.', 'error');
                }
            });
        };

        // ─── Discard ───
        window.discardChanges = function() {
            selectedMode = originalMode;
            selectedPreset = originalPreset;
            updateThemeModeUI(originalMode);
            updateColorPresetUI(originalPreset);
            applyThemeLive(originalMode);
            applyColorPresetLive(originalPreset);
            checkForChanges();

            if (window.BiogenixToast) {
                window.BiogenixToast.show('Changes discarded.', 'info');
            }
        };

        // ─── Reset to Defaults ───
        window.resetToDefaults = function() {
            if (!confirm('Reset all settings to factory defaults?')) return;

            var btn = document.getElementById('resetDefaultsBtn');
            btn.style.opacity = '0.5';
            btn.style.pointerEvents = 'none';

            fetch('{{ route("admin.global-settings.reset") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.style.opacity = '';
                btn.style.pointerEvents = '';

                if (data.success) {
                    selectedMode = 'light';
                    selectedPreset = 'biogenix-green';
                    originalMode = 'light';
                    originalPreset = 'biogenix-green';

                    updateThemeModeUI('light');
                    updateColorPresetUI('biogenix-green');
                    applyThemeLive('light');
                    applyColorPresetLive('biogenix-green');
                    checkForChanges();

                    if (window.BiogenixToast) {
                        window.BiogenixToast.show(data.message || 'Settings reset!', 'success');
                    }
                }
            })
            .catch(function(err) {
                btn.style.opacity = '';
                btn.style.pointerEvents = '';
                console.error(err);
                if (window.BiogenixToast) {
                    window.BiogenixToast.show('Network error. Please try again.', 'error');
                }
            });
        };

        // ─── Initialize ───
        updateThemeModeUI(selectedMode);
        updateColorPresetUI(selectedPreset);
    })();
    </script>
</div>
@endsection
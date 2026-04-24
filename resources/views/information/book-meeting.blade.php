@extends('layouts.app')

@section('title', 'Book a Meeting')

@section('content')
@php
    $sectionCardClass = 'rounded-[var(--ui-radius-card)] border border-slate-200/80 bg-white/95 p-6 shadow-[var(--ui-shadow-card)] backdrop-blur md:p-8';
    $accentCardClass = 'relative overflow-hidden rounded-[var(--ui-radius-card)] border border-primary-100/70 bg-gradient-to-br from-white via-primary-50/55 to-white p-6 shadow-[var(--ui-shadow-panel)] backdrop-blur md:p-8';
    $inputClass = 'block min-h-12 w-full rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 text-sm text-slate-900 shadow-[var(--ui-shadow-soft)] transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $primaryButtonClass = 'inline-flex min-h-12 items-center justify-center rounded-2xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-600/20 transition hover:-translate-y-px hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $meetingUser = auth()->user();

    // Business step: keep old values visible after validation so the user can correct only the fields that failed.
    $minimumMeetingDate = $minimumMeetingDate instanceof \Carbon\Carbon ? $minimumMeetingDate->toDateString() : ($minimumMeetingDate ?? now()->toDateString());
    $preferredDateValue = old('preferred_date', $minimumMeetingDate);
    $startTimeValue = old('start_time', '09:00');
    $endTimeValue = old('end_time', '10:00');
    $fullNameValue = old('full_name', $meetingUser?->name ?? '');
    $emailValue = old('email', $meetingUser?->email ?? '');
    $phoneValue = preg_replace('/[^0-9]/', '', (string) old('phone', $meetingUser?->phone ?? ''));
    $phoneValue = strlen($phoneValue) > 10 ? substr($phoneValue, -10) : $phoneValue;
    $organizationNameValue = old('organization_name', '');
@endphp

<div class="bg-gradient-to-b from-white via-primary-50/20 to-white">
    <section class="relative overflow-hidden bg-primary-800 py-16 text-white md:py-24">
        <img src="{{ asset('upload/backgrounds/meeting-bg.jpg') }}" alt="Biogenix Meeting" class="absolute inset-0 h-full w-full object-cover opacity-40" style="filter: blur(3.5px); transform: scale(1.03);" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-[#013b2a]/85 via-[#013b2a]/80 to-[#013b2a]/75"></div>
        <div class="relative z-10 mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
           
            <h1 class="mx-auto max-w-5xl font-display text-4xl font-bold tracking-tight text-white md:text-5xl lg:text-6xl">
                Accelerate your <span class="text-secondary-600">diagnostics operations</span> with Biogenix.
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-secondary-600 md:text-lg">
                Gain direct access to our team for product selection, procurement planning, and system-level optimization tailored to your laboratory, hospital, or institution.
            </p>
        </div>
    </section>

    <section class="bg-transparent py-12 md:py-20">
        <div class="mx-auto grid w-full px-4 sm:px-6 md:w-[90%] md:px-0 lg:w-[85%] xl:w-[80%] max-w-none grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10 xl:gap-12">
            <div class="lg:col-span-5 h-fit">
                <div class="{{ $accentCardClass }} flex h-fit flex-col justify-start">
                    <div class="pointer-events-none absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-primary-50 opacity-50 blur-3xl"></div>
                    
                    <div class="relative z-10 space-y-8">
                        <div>
                            <h2 class="font-display text-3xl font-semibold tracking-tight text-slate-950">Why book a meeting with Biogenix?</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-600">This isn’t just a discussion. It’s a focused session to align your diagnostic requirements with the right products, pricing structure, and operational approach.</p>
                        </div>
        
                        <div class="space-y-4">
                            <div class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-white/60 p-4 shadow-sm backdrop-blur">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Precision Product Alignment</h3>
                                    <p class="mt-1 text-xs leading-5 text-slate-600">We help you identify the exact diagnostic kits, reagents, and instruments suited to your workload, testing volume, and clinical requirements.</p>
                                </div>
                            </div>
        
                            <div class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-white/60 p-4 shadow-sm backdrop-blur">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-primary-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Smart Procurement Strategy</h3>
                                    <p class="mt-1 text-xs leading-5 text-slate-600">Move beyond basic purchasing—get clarity on pricing structures, bulk planning, and supply consistency designed for long-term efficiency.</p>
                                </div>
                            </div>
        
                            <div class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-white/60 p-4 shadow-sm backdrop-blur">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Operational Clarity & Support</h3>
                                    <p class="mt-1 text-xs leading-5 text-slate-600">Understand how Biogenix supports your lab beyond supply—with structured coordination, faster response cycles, and dependable execution.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 mt-8 rounded-2xl border border-slate-100 bg-white/80 p-5 shadow-sm backdrop-blur">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 shrink-0 overflow-hidden rounded-full bg-slate-200">
                                <img src="{{ asset('upload/icons/logo.jpg') }}" alt="Expert" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Enterprise Sales Team</p>
                                <p class="text-xs text-slate-500">Biogenix Corporate Office</p>
                            </div>
                        </div>
                        <p class="mt-4 text-xs italic leading-5 text-slate-700">"We aim to streamline your entire procurement process. A quick 15-minute chat lets us tailor a solution exactly for your scale and regional requirements."</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="{{ $accentCardClass }} h-full">
                    <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-primary-50 opacity-50 blur-3xl"></div>

                    <div class="mb-8 border-b border-slate-100 pb-5">
                        <h2 class="font-display text-3xl font-semibold tracking-tight text-slate-950">Schedule Your Session</h2>
                        <p class="mt-2 max-w-none text-base leading-8 text-slate-600">Choose a convenient time to connect with our team. We’ll review your requirements, understand your setup, and guide you with the right solutions and next steps.</p>
                    </div>

                    <form id="meetingForm" action="{{ route('book-meeting.store') }}" method="POST" class="relative z-10">
                        @csrf

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="preferred_date" class="mb-1 block text-sm font-medium text-slate-700">Preferred Date</label>
                                <input id="preferred_date" name="preferred_date" type="date" min="{{ $minimumMeetingDate }}" value="{{ $preferredDateValue }}" class="{{ $inputClass }} @error('preferred_date') border-rose-400 ring-4 ring-rose-500/10 @enderror" required>
                                @error('preferred_date')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-end">
                                <p class="w-full rounded-2xl border border-primary-100/80 bg-primary-50/70 px-4 py-3 text-sm font-medium text-slate-600">
                                    Meeting hours: <span class="font-semibold text-slate-900">09:00 to 18:00 IST</span>
                                </p>
                            </div>

                            <div>
                                <label for="start_time" class="mb-2 block text-sm font-medium text-slate-700">Start time</label>
                                <div class="relative">
                                    <input id="start_time" name="start_time" type="time" min="09:00" max="18:00" value="{{ $startTimeValue }}" class="{{ $inputClass }} @error('start_time') border-rose-400 ring-4 ring-rose-500/10 @enderror pr-11" required>
                                </div>
                                @error('start_time')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="mb-2 block text-sm font-medium text-slate-700">End time</label>
                                <div class="relative">
                                    <input id="end_time" name="end_time" type="time" min="09:00" max="18:00" value="{{ $endTimeValue }}" class="{{ $inputClass }} @error('end_time') border-rose-400 ring-4 ring-rose-500/10 @enderror pr-11" required>
                                </div>
                                @error('end_time')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                @error('time_range')
                                    <p class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="full_name" class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                                <input id="full_name" name="full_name" type="text" value="{{ $fullNameValue }}" class="{{ $inputClass }} @error('full_name') border-rose-400 ring-4 ring-rose-500/10 @enderror" placeholder="e.g. Dr. Jane Doe" maxlength="150" required>
                                @error('full_name')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Work Email</label>
                                <input id="email" name="email" type="email" value="{{ $emailValue }}" class="{{ $inputClass }} @error('email') border-rose-400 ring-4 ring-rose-500/10 @enderror" placeholder="you@hospital.com" maxlength="150" required>
                                @error('email')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Phone Number</label>
                                <div class="relative">
                                    <input id="phone" name="phone" type="text" value="{{ $phoneValue }}" class="{{ $inputClass }} @error('phone') border-rose-400 ring-4 ring-rose-500/10 @enderror pl-12" placeholder="9876543210" maxlength="10" inputmode="numeric" required>
                                </div>
                                @error('phone')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="organization_name" class="mb-1 flex items-center justify-between text-sm font-medium text-slate-700">
                                    <span>Organization / Company Name</span>
                                </label>
                                <input id="organization_name" name="organization_name" type="text" value="{{ $organizationNameValue }}" class="{{ $inputClass }} @error('organization_name') border-rose-400 ring-4 ring-rose-500/10 @enderror" placeholder="Name of your institution" maxlength="150">
                                @error('organization_name')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Custom Dropdown: What would you like to discuss? --}}
                            @php $oldMeetingOption = old('meeting_option', ''); @endphp
                            <div class="relative" id="dropdown_meeting_option">
                                <label class="mb-1 flex items-center justify-between text-sm font-medium text-slate-700">
                                    <span>What would you like to discuss?</span>
                                </label>
                                <button type="button" class="{{ $inputClass }} flex items-center justify-between cursor-pointer pr-4 hover:border-primary-400 focus:border-primary-500" onclick="toggleCustomDropdown('meeting_option')">
                                    <span id="label_meeting_option" class="block truncate {{ $oldMeetingOption ? 'text-slate-900' : 'text-slate-500' }}">
                                        {{ $oldMeetingOption ?: 'Select Option' }}
                                    </span>
                                    <svg class="h-4 w-4 text-slate-500 transition-transform duration-200 shrink-0" id="icon_meeting_option" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <input type="hidden" name="meeting_option" id="input_meeting_option" value="{{ $oldMeetingOption }}">
                                
                                <div class="absolute z-50 left-0 right-0 mt-2 max-h-60 overflow-y-auto rounded-xl border border-slate-100 bg-white py-1 shadow-[var(--ui-shadow-card)] opacity-0 pointer-events-none transition-all duration-200 origin-top scale-95 custom-dropdown-menu" id="menu_meeting_option">
                                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 transition hover:bg-primary-50 hover:text-primary-700" onclick="selectDropdownOption('meeting_option', '', 'Select Option')">Select Option</button>
                                    @foreach(['Product Inquiry', 'Bulk Procurement / Pricing', 'Instrument Setup / Lab Planning', 'Technical Support', 'Partnership / Distribution', 'General Discussion'] as $opt)
                                        <button type="button" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 transition hover:bg-primary-50 hover:text-primary-700 focus:bg-primary-50" onclick="selectDropdownOption('meeting_option', '{{ $opt }}', '{{ $opt }}')">{{ $opt }}</button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Custom Dropdown: Select Department --}}
                            @php $oldDepartment = old('department', ''); @endphp
                            <div class="relative" id="dropdown_department">
                                <label class="mb-1 flex items-center justify-between text-sm font-medium text-slate-700">
                                    <span>Select Department</span>
                                </label>
                                <button type="button" class="{{ $inputClass }} flex items-center justify-between cursor-pointer pr-4 hover:border-primary-400 focus:border-primary-500" onclick="toggleCustomDropdown('department')">
                                    <span id="label_department" class="block truncate {{ $oldDepartment ? 'text-slate-900' : 'text-slate-500' }}">
                                        {{ $oldDepartment ?: 'Select Department' }}
                                    </span>
                                    <svg class="h-4 w-4 text-slate-500 transition-transform duration-200 shrink-0" id="icon_department" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <input type="hidden" name="department" id="input_department" value="{{ $oldDepartment }}">
                                
                                <div class="absolute z-50 left-0 right-0 mt-2 max-h-60 overflow-y-auto rounded-xl border border-slate-100 bg-white py-1 shadow-[var(--ui-shadow-card)] opacity-0 pointer-events-none transition-all duration-200 origin-top scale-95 custom-dropdown-menu" id="menu_department">
                                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 transition hover:bg-primary-50 hover:text-primary-700" onclick="selectDropdownOption('department', '', 'Select Department')">Select Department</button>
                                    @foreach(['Diagnostics (ELISA / Rapid / Serology)', 'Biochemistry', 'Molecular Diagnostics', 'Instruments & Equipment', 'Technical Support', 'Sales & Procurement'] as $opt)
                                        <button type="button" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 transition hover:bg-primary-50 hover:text-primary-700 focus:bg-primary-50" onclick="selectDropdownOption('department', '{{ $opt }}', '{{ $opt }}')">{{ $opt }}</button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Custom Dropdown: Talk To --}}
                            @php $oldPreferredContact = old('preferred_contact', 'Assign Best Available Expert'); @endphp
                            <div class="relative md:col-span-2" id="dropdown_preferred_contact">
                                <label class="mb-1 flex items-center justify-between text-sm font-medium text-slate-700">
                                    <span>Talk To</span>
                                </label>
                                <button type="button" class="{{ $inputClass }} flex items-center justify-between cursor-pointer pr-4 hover:border-primary-400 focus:border-primary-500" onclick="toggleCustomDropdown('preferred_contact')">
                                    <span id="label_preferred_contact" class="block truncate text-slate-900">
                                        {{ $oldPreferredContact }}
                                    </span>
                                    <svg class="h-4 w-4 text-slate-500 transition-transform duration-200 shrink-0" id="icon_preferred_contact" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <input type="hidden" name="preferred_contact" id="input_preferred_contact" value="{{ $oldPreferredContact }}">
                                
                                <div class="absolute z-50 left-0 right-0 mt-2 max-h-60 overflow-y-auto rounded-xl border border-slate-100 bg-white py-1 shadow-[var(--ui-shadow-card)] opacity-0 pointer-events-none transition-all duration-200 origin-top scale-95 custom-dropdown-menu" id="menu_preferred_contact">
                                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 transition hover:bg-primary-50 hover:text-primary-700 focus:bg-primary-50" onclick="selectDropdownOption('preferred_contact', 'Assign Best Available Expert', 'Assign Best Available Expert')">Assign Best Available Expert</button>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="requirement_description" class="mb-1 flex items-center justify-between text-sm font-medium text-slate-700">
                                    <span>Tell us briefly about your requirement</span>
                                </label>
                                <textarea id="requirement_description" name="requirement_description" rows="4" class="{{ $inputClass }} min-h-32 resize-y" placeholder="Briefly describe what you'd like to discuss...">{{ old('requirement_description') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            <button type="submit" id="meetingSubmitBtn" class="{{ $primaryButtonClass }} w-full md:w-auto">Request Session with Biogenix</button>
                            <p class="text-sm font-medium text-slate-500">Business meetings are confirmed manually by our team.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gradient-to-b from-white via-primary-50/10 to-white py-14 md:py-20">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Choose Your Biogenix Session" subtitle="Select the type of session based on your requirement. Each discussion is structured to deliver clarity, speed, and actionable outcomes." />
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['title' => 'Product Fit Consultation (15-20 min)', 'copy' => 'A focused discussion to understand your testing requirements and recommend the most suitable Biogenix products for your workflow.'],
                    ['title' => 'Technical Evaluation Session (30-40 min)', 'copy' => 'Detailed walkthrough of product specifications, compatibility, and performance—ideal for labs evaluating systems, assays, or integration.'],
                    ['title' => 'Procurement & Commercial Planning (25-30 min)', 'copy' => 'Discuss pricing structures, bulk procurement strategies, and supply planning tailored to your operational scale and demand.'],
                ] as $format)
                    <article class="h-full rounded-[var(--ui-radius-card)] border border-slate-200/80 bg-white/95 p-6 shadow-[var(--ui-shadow-card)] backdrop-blur transition hover:-translate-y-1.5 hover:border-primary-100 hover:shadow-[var(--ui-shadow-panel)] md:p-8">
                        <h3 class="font-display text-lg xl:text-[1.15rem] font-semibold tracking-tight leading-snug text-slate-950">{{ $format['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $format['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    function toggleCustomDropdown(id) {
        document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
            if (menu.id !== 'menu_' + id) {
                menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                const icon = document.getElementById('icon_' + menu.id.replace('menu_', ''));
                if(icon) icon.classList.remove('rotate-180');
            }
        });
        
        const menu = document.getElementById('menu_' + id);
        const icon = document.getElementById('icon_' + id);
        
        if (menu.classList.contains('opacity-0')) {
            menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            menu.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
            if(icon) icon.classList.add('rotate-180');
        } else {
            menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            if(icon) icon.classList.remove('rotate-180');
        }
    }

    function selectDropdownOption(name, value, label) {
        document.getElementById('input_' + name).value = value;
        const mainLabel = document.getElementById('label_' + name);
        mainLabel.innerText = label || 'Select Option';
        if(value) {
            mainLabel.classList.remove('text-slate-500');
            mainLabel.classList.add('text-slate-900');
        } else {
            mainLabel.classList.add('text-slate-500');
            mainLabel.classList.remove('text-slate-900');
        }
        
        const menu = document.getElementById('menu_' + name);
        const icon = document.getElementById('icon_' + name);
        menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
        if(icon) icon.classList.remove('rotate-180');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('div.relative button')) {
            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                const icon = document.getElementById('icon_' + menu.id.replace('menu_', ''));
                if(icon) icon.classList.remove('rotate-180');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        // Script for syncing time window still active
    });
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Diagnostic Precision Quiz – Biogenix')
@section('meta_description', 'Test your diagnostic knowledge with the Biogenix Kits Mastery quiz.')

@push('styles')
    <style>
        .quiz-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .quiz-container.quiz-compact {
            min-height: auto;
        }

        @media (min-width: 768px) {
            .quiz-container {
                flex-direction: row;
                min-height: calc(100vh - 80px);
            }

            .quiz-container.quiz-compact {
                min-height: auto;
            }
        }

        .quiz-step {
            display: none;
            opacity: 0;
            transform: translateY(0.75rem);
            transition: opacity 0.35s var(--transition-premium), transform 0.35s var(--transition-premium);
        }

        .quiz-step.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .quiz-option {
            min-width: 0;
            transition:
                transform 0.2s var(--transition-premium),
                box-shadow 0.2s var(--transition-premium),
                border-color 0.2s var(--transition-premium),
                background-color 0.2s var(--transition-premium);
        }

        .quiz-option .option-title,
        .quiz-option .option-desc {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .quiz-step[data-step='2-b2c'] .quiz-option>.option-title {
            display: block;
            flex: 1 1 0%;
            min-width: 0;
            line-height: 1.25;
        }

        .quiz-option.selected,
        .quiz-option[aria-checked='true'] {
            background-color: var(--color-primary-50) !important;
            border-color: var(--color-primary-600) !important;
            box-shadow: 0 0 0 1px var(--color-primary-600), var(--ui-shadow-soft);
            transform: translateY(-2px);
        }

        .quiz-option.selected .check-mark,
        .quiz-option[aria-checked='true'] .check-mark {
            display: flex !important;
        }

        .check-mark {
            box-shadow: 0 0 0 2px white, var(--ui-shadow-soft);
        }

        .quiz-option.selected .icon-box,
        .quiz-option[aria-checked='true'] .icon-box {
            background-color: var(--color-secondary-100) !important;
            color: var(--color-primary-900) !important;
            border-color: transparent !important;
        }

        .quiz-option.selected [data-option-icon],
        .quiz-option[aria-checked='true'] [data-option-icon] {
            background-color: var(--color-secondary-600) !important;
            color: var(--color-primary-900) !important;
            border-color: var(--color-secondary-600) !important;
        }

        .quiz-option.selected [data-option-icon] svg,
        .quiz-option[aria-checked='true'] [data-option-icon] svg {
            color: var(--color-primary-900) !important;
            opacity: 1 !important;
        }

        .quiz-option:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-primary-600) 20%, transparent), var(--ui-shadow-soft);
        }

        .quiz-option[aria-invalid='true'] {
            border-color: var(--color-tertiary-600) !important;
            box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-tertiary-600) 18%, transparent), var(--ui-shadow-soft);
        }

        .quiz-option[data-disabled='true'] input {
            opacity: 0.72;
            pointer-events: none;
        }

        .quiz-step-feedback {
            border: 1px solid color-mix(in srgb, var(--color-tertiary-600) 18%, transparent);
            background-color: color-mix(in srgb, var(--color-tertiary-50) 80%, white);
            color: var(--color-tertiary-700);
            border-radius: var(--ui-radius-field);
        }

        .quiz-field-invalid {
            border-color: var(--color-tertiary-600) !important;
            box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-tertiary-600) 18%, transparent);
        }

        #scoreRingFill {
            transition: stroke-dashoffset 1.2s var(--transition-premium);
        }

        .left-panel-bg {
            background-image: url('{{ asset('upload/corousel/image3.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .left-panel-overlay {
            background: color-mix(in srgb, white 88%, transparent);
            backdrop-filter: grayscale(100%) opacity(30%);
        }

        @media (max-width: 480px) {
            .quiz-step[data-step='2-b2c'] .quiz-option {
                align-items: flex-start;
                gap: 0.625rem;
                padding: 0.875rem;
            }

            .quiz-step[data-step='2-b2c'] .quiz-option .icon-box {
                width: 2rem;
                height: 2rem;
            }

            .quiz-step[data-step='2-b2c'] .quiz-option>.option-title {
                font-size: 0.8125rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $quizQuestionsPayload = collect($quizQuestions ?? [])
            ->values()
            ->map(function ($quizQuestion): array {
                return [
                    'id' => (int) $quizQuestion->id,
                    'phase_title' => (string) $quizQuestion->phase_title,
                    'question_text' => (string) $quizQuestion->question_text,
                    'answer_options' => collect($quizQuestion->answerOptions ?? [])
                        ->values()
                        ->map(function ($answerOption): array {
                            return [
                                'id' => (int) $answerOption->id,
                                'option_label' => (string) $answerOption->option_label,
                                'option_text' => (string) $answerOption->option_text,
                            ];
                        })
                        ->all(),
                ];
            })
            ->all();
        $hasQuizData = count($quizQuestionsPayload) > 0;

        // We will inject our custom first step if needed or just use it as a wrapper
        $totalSteps = count($quizQuestionsPayload);
        $quizPrimaryButtonClasses = 'inline-flex items-center justify-center gap-2 rounded-[var(--ui-radius-button)] bg-primary-600 px-7 py-3.5 text-sm font-semibold tracking-tight text-white shadow-lg shadow-primary-900/10 transition hover:-translate-y-0.5 hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/30';
        $quizSecondaryButtonClasses = 'inline-flex items-center justify-center gap-1.5 rounded-[var(--ui-radius-button)] border border-primary-100 bg-primary-50 px-6 py-2.5 text-[14px] font-bold tracking-wide text-primary-900 transition hover:bg-primary-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/20';
        $quizFieldClasses = 'w-full rounded-[var(--ui-radius-field)] border border-primary-100 bg-white px-4 py-3.5 text-[14px] font-medium text-primary-900 outline-none transition placeholder:text-primary-800/50 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20';
        $quizCompactFieldClasses = 'w-full rounded-[var(--ui-radius-field)] border border-primary-100 bg-white px-4 py-3 text-[13px] font-medium text-primary-900 outline-none transition placeholder:text-primary-800/50 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20';
    @endphp

    <div class="mx-auto w-full max-w-[95%] lg:max-w-[90%] xl:max-w-[80%] py-4 md:py-6">
        <div
            class="quiz-container w-full overflow-hidden rounded-[var(--ui-radius-card)] border border-primary-100 bg-white shadow-[var(--ui-shadow-panel)]">

            {{-- LEFT VISUAL PANEL --}}
            <div
                class="relative w-full md:w-[40%] xl:w-[42%] flex flex-col p-8 md:p-10 left-panel-bg overflow-hidden border-r border-primary-100">
                <div id="leftPanelOverlay" class="absolute inset-0 left-panel-overlay z-0"></div>

                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div id="leftPanelIntro">
                        <p class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 mb-4 shadow-sm"
                            id="leftStepCounter">STEP 01 OF 04</p>
                        <h1 class="font-display text-2xl md:text-3xl lg:text-4xl font-bold leading-[1.15] text-primary-900 tracking-tight transition-all duration-300"
                            id="leftStepTitle">
                            Let's Understand<br>Your Setup
                        </h1>
                    </div>

                    <div id="quizVisualCaption" class="hidden mt-auto max-w-md text-white">
                        <h3 class="font-display text-xl font-bold text-white mb-3" id="quizVisualCaptionTitle">
                            Practice-Driven Diagnostics
                        </h3>
                        <p class="text-sm text-white/85 leading-relaxed" id="quizVisualCaptionDescription">
                            Each medical specialization has distinct diagnostic priorities. Choosing the right tests and
                            kits aligned with your practice can improve patient outcomes while creating additional in-clinic
                            value.
                        </p>
                    </div>
                </div>
            </div>

            {{-- RIGHT CONTENT PANEL --}}
            <div id="quizRightPanel" class="flex-1 flex flex-col p-6 md:p-10 xl:p-12 overflow-y-auto">

                <div id="quizFormArea" class="max-w-4xl w-full mx-auto">

                    {{-- HEADER ROW (Per image) --}}
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6 lg:gap-12 mb-6 md:mb-8">
                        <div class="lg:w-1/3">
                            <h3 class="font-display text-lg font-bold text-primary-900 leading-tight">
                                Personalized<br>Diagnostic<br>Pathways
                            </h3>
                        </div>
                        <div class="lg:w-2/3">
                            <p class="text-[13px] md:text-sm text-primary-800/60 leading-relaxed max-w-lg">
                                Different roles demand different diagnostic strategies. Whether you are delivering care,
                                managing operations, or driving distribution, aligning products with your workflow is the
                                first step toward efficiency and growth.
                            </p>
                        </div>
                    </div>

                    {{-- STEPS CONTAINER --}}
                    <div id="quizStepsWrapper">

                        {{-- STEP 01 (Image content hardcoded as first step) --}}
                        <div class="quiz-step active" data-step="1">
                            <section>
                                <h2 class="font-display text-xl md:text-2xl font-bold text-primary-900 mb-6 max-w-2xl">
                                    Which of the following best describes you?
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-4">
                                    <!-- Card 1 -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'doctor', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Doctor / Clinic Owner</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">Focus on
                                                    patient care and history</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Card 2 -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'lab', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Laboratory / Hospital</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">
                                                    High-volume sample processing</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Card 3 (Dealer - Selected in Image) -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'dealer', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Dealer / Distributor</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">Inventory
                                                    and logistics flow</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Card 4 -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'other', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Other</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">Custom
                                                    workflow definition</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div id="roleOtherField" class="mb-6 hidden">
                                    <label for="roleOtherInput"
                                        class="block text-xs font-bold text-primary-800/60 mb-2 ml-1">Please specify your
                                        role or organization type</label>
                                    <input type="text" id="roleOtherInput" class="{{ $quizFieldClasses }}"
                                        placeholder="Enter details here..." maxlength="100">
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-end mt-6 gap-4">
                                    <button type="button" class="{{ $quizPrimaryButtonClasses }} w-full sm:w-auto" onclick="nextStep(2)">
                                        Continue
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                </div>
                            </section>
                        </div>

                        <!-- B2C Layout (Step 2) -->
                        <div class="quiz-step" data-step="2-b2c">
                            <div class="w-full">
                                <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                                    <div class="flex-1 flex flex-col">
                                        <div class="mb-6">
                                            <span
                                                class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm">
                                                STEP 02 OF 04
                                            </span>
                                        </div>

                                        <h2
                                            class="font-display text-2xl md:text-3xl font-bold text-primary-900 leading-[1.15] tracking-tight mb-8">
                                            Tell Us About Your<br>Practice</h2>
                                        <div class="mb-8">
                                            <div class="w-full h-[3px] bg-primary-100 rounded-full overflow-hidden">
                                                <div class="h-full w-[40%] bg-primary-600 rounded-full"></div>
                                            </div>
                                        </div>
                                        <h3 class="font-display text-xl md:text-2xl font-bold text-primary-900 mb-6">
                                            What best defines your area of practice?</h3>
                                        <div class="grid grid-cols-2 gap-3 md:gap-4 mb-6">
                                            <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                onclick="handleSelection(2, 'general_physician', this)">
                                                <div
                                                    class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-700 flex-shrink-0 shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3" />
                                                    </svg>
                                                </div>
                                                <span class="option-title font-semibold text-sm text-primary-900">General
                                                    Physician</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                onclick="handleSelection(2, 'diabetologist', this)">
                                                <div
                                                    class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-700 flex-shrink-0 shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19h6" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="option-title font-semibold text-sm text-primary-900">Diabetologist</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                onclick="handleSelection(2, 'cardiologist', this)">
                                                <div
                                                    class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-700 flex-shrink-0 shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="option-title font-semibold text-sm text-primary-900">Cardiologist</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                onclick="handleSelection(2, 'pathologist', this)">
                                                <div
                                                    class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-700 flex-shrink-0 shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="option-title font-semibold text-sm text-primary-900">Pathologist</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="quiz-option flex items-center gap-3 mb-4 py-3 px-4 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer group transition hover:border-primary-200"
                                            onclick="handleSelection(2, 'other_spec', this)">
                                            <div
                                                class="w-5 h-5 rounded-full border-2 border-primary-200 bg-white flex items-center justify-center flex-shrink-0 transition-colors group-[.selected]:border-primary-600">
                                                <div
                                                    class="w-2.5 h-2.5 rounded-full bg-primary-600 scale-0 transition-transform group-[.selected]:scale-100">
                                                </div>
                                            </div>
                                            <span class="option-title font-semibold text-sm text-primary-900">Other</span>
                                        </div>
                                        <div id="practiceOtherField" class="mb-10 hidden"><input type="text"
                                                id="practiceOtherInput" class="{{ $quizFieldClasses }}"
                                                placeholder="Type your specialization" maxlength="100"></div>
                                        <div class="flex flex-col sm:flex-row items-center justify-between mt-auto pt-10 gap-4">
                                            <button type="button" class="{{ $quizSecondaryButtonClasses }} w-full sm:w-auto"
                                                onclick="prevStep(1)">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                </svg> Back
                                            </button>
                                            <button type="button" class="{{ $quizPrimaryButtonClasses }} w-full sm:w-auto"
                                                onclick="nextStep(3)">
                                                Continue
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- B2B Layout -->
                        <div class="quiz-step" data-step="2-b2b">
                            <div class="max-w-full w-full mx-auto py-2 relative">
                                <!-- Subtle background image for the B2B step (relative to inner container or full-bleed) -->
                                <img src="{{ asset('upload/corousel/image2.jpg') }}" alt="Background"
                                    class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-30 grayscale blur-[1px]">

                                <div class="relative z-10 h-full flex flex-col justify-between">
                                    <div class="mb-8">
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm">
                                            STEP 2 OF 4
                                        </span>
                                    </div>

                                    <!-- Header Area -->
                                    <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-12">
                                        <div class="md:w-[50%]">
                                            <h2
                                                class="font-display text-2xl md:text-3xl font-bold text-primary-900 leading-[1.15] tracking-tight mb-8">
                                                Tell Us About Your<br>Business</h2>
                                        </div>
                                        <div class="md:w-[45%] md:text-right mt-2">
                                            <h3 class="font-display text-lg md:text-xl font-bold text-primary-900 mb-3">
                                                Business Model Alignment</h3>
                                            <p
                                                class="text-sm text-primary-800/80 leading-relaxed md:ml-auto md:max-w-md font-medium">
                                                From distribution to procurement, every role in the supply chain
                                                requires a different product mix, pricing structure, and logistics
                                                strategy. Clarity here directly impacts margins and scalability.</p>
                                        </div>
                                    </div>

                                    <!-- Content Area -->
                                    <div class="mt-auto">
                                        <h3 class="font-display text-xl md:text-2xl font-bold text-primary-900 mb-6">
                                            What best defines your business role?</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 mb-8">
                                            <!-- Distributor -->
                                            <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl border border-primary-100 bg-primary-50/60 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md"
                                                onclick="handleSelection(2, 'distributor', this)">
                                                <div data-option-icon
                                                    class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-primary-800 flex-shrink-0 shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-primary-900 text-[15px]">Distributor</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Dealer / Trader -->
                                            <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl border border-primary-100 bg-primary-50/60 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md"
                                                onclick="handleSelection(2, 'dealer_trader', this)">
                                                <div data-option-icon
                                                    class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-primary-800 flex-shrink-0 shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-primary-900 text-[15px]">Dealer /
                                                    Trader</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Diagnostic Laboratory -->
                                            <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl border border-primary-100 bg-primary-50/60 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md"
                                                onclick="handleSelection(2, 'diagnostic_lab', this)">
                                                <div data-option-icon
                                                    class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-primary-800 flex-shrink-0 shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-primary-900 text-[15px]">Diagnostic
                                                    Laboratory</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Hospital Procurement -->
                                            <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl border border-primary-100 bg-primary-50/60 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md"
                                                onclick="handleSelection(2, 'hospital_procurement', this)">
                                                <div data-option-icon
                                                    class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-primary-800 flex-shrink-0 shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-primary-900 text-[15px]">Hospital
                                                    Procurement</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Wholesale Medical Supplier -->
                                            <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl border border-primary-100 bg-primary-50/60 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md"
                                                onclick="handleSelection(2, 'wholesale', this)">
                                                <div data-option-icon
                                                    class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-primary-800 flex-shrink-0 shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-primary-900 text-[15px]">Wholesale
                                                    Medical Supplier</span>
                                                <div
                                                    class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Other -->
                                            <div class="quiz-option flex flex-col justify-center p-4 rounded-2xl border border-primary-100 bg-primary-50/60 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md"
                                                onclick="handleSelection(2, 'other_b2b', this)">
                                                <div class="flex items-center gap-4 mb-3">
                                                    <div data-option-icon
                                                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-800 flex-shrink-0 shadow-sm">
                                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                        </svg>
                                                    </div>
                                                    <span class="font-bold text-primary-900 text-[15px]">Other</span>
                                                </div>
                                                <label for="businessOtherRole" class="sr-only">Specify your business
                                                    role</label>
                                                <input type="text" id="businessOtherRole" disabled
                                                    class="{{ $quizCompactFieldClasses }}"
                                                    placeholder="Specify your role..." maxlength="100"
                                                    onclick="event.stopPropagation()">
                                                <div
                                                    class="check-mark hidden absolute right-4 top-6 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                    <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="flex flex-col sm:flex-row justify-between items-center w-full mt-2 gap-4">
                                            <button type="button" class="{{ $quizSecondaryButtonClasses }} w-full sm:w-auto"
                                                onclick="prevStep(1)">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                </svg> Back
                                            </button>
                                            <button type="button" class="{{ $quizPrimaryButtonClasses }} w-full sm:w-auto ml-auto"
                                                onclick="nextStep(3)">
                                                Continue
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Step 3 B2B Layout -->
                        <div class="quiz-step" data-step="3-b2b">
                            <div class="max-w-full w-full mx-auto py-2">
                                <!-- Header / Progress Area -->
                                <div class="mb-12">
                                    <div class="flex justify-between items-end mb-3">
                                        <div>
                                            <span
                                                class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4"
                                                data-step-badge>STEP
                                                03 OF 05</span>
                                            <h2
                                                class="font-display text-2xl md:text-3xl font-bold text-primary-900 tracking-tight">
                                                Business Scale</h2>
                                        </div>
                                        <div class="text-[13px] font-bold text-primary-800/80">60% Complete</div>
                                    </div>
                                    <!-- Progress bar -->
                                    <div class="w-full h-2.5 bg-primary-100 rounded-full overflow-hidden">
                                        <div class="h-full w-[60%] bg-primary-900 rounded-full"></div>
                                    </div>
                                </div>

                                <!-- Content Area -->
                                <div class="flex flex-col md:flex-row gap-8 lg:gap-20">
                                    <!-- Left Info -->
                                    <div class="md:w-[40%] md:pt-4">
                                        <h3
                                            class="font-display text-2xl md:text-3xl font-bold text-primary-900 leading-[1.15] tracking-tight mb-5">
                                            Volume-Based<br>Optimization</h3>
                                        <p class="text-[15px] text-primary-800/80 leading-relaxed font-medium">As
                                            your monthly volume increases, procurement strategy becomes critical.
                                            Bulk alignment, pricing tiers, and supply consistency can significantly
                                            improve operational profitability.</p>
                                    </div>

                                    <!-- Right Card -->
                                    <div class="md:w-[60%]">
                                        <div class="relative z-10 w-full">
                                            <h3 class="font-display text-xl font-bold text-primary-900 mb-8 tracking-tight">
                                                What is your approximate monthly business volume?</h3>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 mb-10">
                                                <!-- TIER 1 -->
                                                <div class="quiz-option p-5 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative transition hover:border-primary-200 shadow-sm"
                                                    onclick="handleSelection(3, 'tier1', this)">
                                                    <p
                                                        class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                        TIER 1</p>
                                                    <h4 class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                        ₹50K - ₹2L</h4>
                                                    <p
                                                        class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg> Standard Procurement
                                                    </p>
                                                    <div
                                                        class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- TIER 2 -->
                                                <div class="quiz-option p-5 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative transition hover:border-primary-200 shadow-sm"
                                                    onclick="handleSelection(3, 'tier2', this)">
                                                    <p
                                                        class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                        TIER 2</p>
                                                    <h4 class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                        ₹2L - ₹5L</h4>
                                                    <p
                                                        class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg> Managed Supply
                                                    </p>
                                                    <div
                                                        class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- TIER 3 -->
                                                <div class="quiz-option p-5 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative transition hover:border-primary-200 shadow-sm"
                                                    onclick="handleSelection(3, 'tier3', this)">
                                                    <p
                                                        class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                        TIER 3</p>
                                                    <h4 class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                        ₹5L - ₹10L</h4>
                                                    <p
                                                        class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg> Volume Optimized
                                                    </p>
                                                    <div
                                                        class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- TIER 4 -->
                                                <div class="quiz-option p-5 rounded-2xl border border-primary-100 bg-primary-50/60 cursor-pointer relative transition hover:border-primary-200 shadow-sm"
                                                    onclick="handleSelection(3, 'tier4', this)">
                                                    <p
                                                        class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                        TIER 4</p>
                                                    <h4 class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                        ₹10L+</h4>
                                                    <p
                                                        class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg> Enterprise Scale
                                                    </p>
                                                    <div
                                                        class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:flex-row justify-between items-center w-full gap-4">
                                                <button type="button" class="{{ $quizSecondaryButtonClasses }} w-full sm:w-auto"
                                                    onclick="prevStep(2)">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                    </svg> Back
                                                </button>
                                                <button type="button" class="{{ $quizPrimaryButtonClasses }} w-full sm:w-auto ml-auto"
                                                    onclick="nextStep(4)">
                                                    Continue
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Step 3 B2C Layout -->
                        <div class="quiz-step" data-step="3-b2c">
                            <div class="w-full py-2">

                                <!-- Top Header part -->
                                <div class="flex flex-col md:flex-row justify-between mb-10 items-start gap-8">
                                    <div class="md:w-1/3">
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4">
                                            SETUP ANALYSIS</span>
                                        <div class="w-12 h-[3px] bg-primary-900 rounded-full"></div>
                                    </div>
                                    <div class="md:w-2/3 md:text-right">
                                        <h2
                                            class="font-display text-2xl md:text-3xl font-bold text-primary-900 leading-[1.05] mb-5 tracking-tight">
                                            In-House vs Outsourced<br>Diagnostics</h2>
                                        <p class="text-[16px] font-medium text-primary-800/80 leading-relaxed md:ml-auto">
                                            Moving from external dependency to in-house diagnostics can unlock
                                            better control, faster reporting, and additional revenue streams - if
                                            supported by the right product ecosystem.</p>
                                    </div>
                                </div>

                                <div class="relative z-10 w-full">
                                    <!-- Progress Row -->
                                    <div class="flex justify-between items-end mb-3">
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm"
                                            data-step-badge>
                                            STEP 3 OF 4</span>
                                    </div>
                                    <div class="w-full h-[3px] bg-primary-100 rounded-full mb-8">
                                        <div class="h-full w-[60%] bg-primary-900 rounded-full"></div>
                                    </div>

                                    <!-- Question -->
                                    <div class="text-center mb-8">
                                        <h2
                                            class="font-display text-2xl md:text-3xl font-bold text-primary-900 mb-3 tracking-tight">
                                            How do you currently manage diagnostics?</h2>
                                        <p class="text-[15px] font-medium text-primary-800/70 italic">Select the
                                            primary model for your clinical facility</p>
                                    </div>

                                    <!-- Options Stack -->
                                    <div class="flex flex-col gap-3 md:gap-4 mb-10 max-w-[42rem] mx-auto">
                                        <!-- Option 1 -->
                                        <div class="quiz-option flex items-center gap-4 px-5 py-4 rounded-2xl border border-primary-100 bg-primary-50/40 cursor-pointer transition hover:bg-primary-50 hover:border-primary-100 shadow-sm group"
                                            onclick="handleSelection(3, 'external_labs', this)">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary-900 shadow-sm flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <span class="font-semibold text-[15px] leading-snug text-primary-900">Fully
                                                dependent
                                                on external labs</span>
                                            <div
                                                class="check-mark hidden ml-auto w-8 h-8 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 2 -->
                                        <div class="quiz-option flex items-center gap-4 px-5 py-4 rounded-2xl border border-primary-100 bg-primary-50/40 cursor-pointer transition hover:bg-primary-50 hover:border-primary-100 shadow-sm group"
                                            onclick="handleSelection(3, 'hybrid', this)">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary-900 shadow-sm flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                                </svg>
                                            </div>
                                            <span class="font-semibold text-[15px] leading-snug text-primary-900">Partially
                                                in-house, partially outsourced</span>
                                            <div
                                                class="check-mark hidden ml-auto w-8 h-8 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 3 -->
                                        <div class="quiz-option flex items-center gap-4 px-5 py-4 rounded-2xl border border-primary-100 bg-primary-50/40 cursor-pointer transition hover:bg-primary-50 hover:border-primary-100 shadow-sm group"
                                            onclick="handleSelection(3, 'in_house', this)">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary-900 shadow-sm flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            </div>
                                            <span class="font-semibold text-[15px] leading-snug text-primary-900">Fully
                                                in-house
                                                setup</span>
                                            <div
                                                class="check-mark hidden ml-auto w-8 h-8 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-center w-full max-w-[42rem] mx-auto border-t border-primary-100 pt-6 gap-4">
                                        <button type="button" class="{{ $quizSecondaryButtonClasses }} w-full sm:w-auto"
                                            onclick="prevStep(2)">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg> Back
                                        </button>
                                        <button type="button" class="{{ $quizPrimaryButtonClasses }} w-full sm:w-auto ml-auto"
                                            onclick="nextStep(4)">
                                            Continue
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Step 4-b2b Container -->
                        <div class="quiz-step" data-step="4-b2b">
                            <div class="w-full">
                                <!-- Header Section -->
                                <div
                                    class="w-full mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">

                                    <div>
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4"
                                            data-step-badge>
                                            STEP 04 OF 05
                                        </span>

                                        <h2
                                            class="font-display text-2xl md:text-3xl font-bold text-primary-900 tracking-tight leading-[1.05]">
                                            Product Focus Areas</h2>
                                    </div>
                                    <div class="md:w-1/2 md:text-right">
                                        <h3 class="text-lg font-bold text-primary-900 mb-1.5">Portfolio
                                            Strength</h3>
                                        <p
                                            class="text-[14px] font-medium text-primary-800/70 leading-relaxed md:ml-auto max-w-[320px]">
                                            A well-balanced product portfolio across key diagnostic categories ensures
                                            operational resilience and clinical breadth.</p>
                                    </div>
                                </div>

                                <!-- Main Card Section -->
                                <div class="relative z-10 w-full mb-4">

                                    <!-- Question Badge & Title -->
                                    <div class="flex items-center gap-4 mb-5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-secondary-600 text-primary-900 flex items-center justify-center text-[11px] font-bold shadow-sm">
                                            4</div>
                                        <span
                                            class="text-[12px] font-bold uppercase tracking-[0.1em] text-primary-900">Question
                                            4</span>
                                    </div>

                                    <h3
                                        class="font-display text-2xl md:text-[28px] font-bold text-primary-900 mb-8 tracking-tight">
                                        Which product categories are you currently dealing in or exploring?</h3>

                                    <!-- Options Grid (Multi-Select) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 mb-10">
                                        <!-- Option 1 -->
                                        <div class="quiz-option group flex min-h-[110px] items-center gap-4 rounded-2xl border border-primary-100 bg-primary-50/40 p-5 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div data-option-icon
                                                class="w-5 h-5 rounded-[4px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold leading-snug text-[15px] text-primary-900">Rapid Test
                                                Kits</span>
                                        </div>

                                        <!-- Option 2 -->
                                        <div class="quiz-option group flex min-h-[110px] items-center gap-4 rounded-2xl border border-primary-100 bg-primary-50/40 p-5 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div data-option-icon
                                                class="w-5 h-5 rounded-[4px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold leading-snug text-[15px] text-primary-900">ELISA</span>
                                        </div>

                                        <!-- Option 3 -->
                                        <div class="quiz-option group flex min-h-[110px] items-center gap-4 rounded-2xl border border-primary-100 bg-primary-50/40 p-5 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div data-option-icon
                                                class="w-5 h-5 rounded-[4px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span
                                                class="font-bold leading-snug text-[15px] text-primary-900">Biochemistry</span>
                                        </div>

                                        <!-- Option 4 -->
                                        <div class="quiz-option group flex min-h-[110px] items-center gap-4 rounded-2xl border border-primary-100 bg-primary-50/40 p-5 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div data-option-icon
                                                class="w-5 h-5 rounded-[4px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold leading-snug text-[15px] text-primary-900">Molecular
                                                Diagnostics</span>
                                        </div>

                                        <!-- Option 5 -->
                                        <div class="quiz-option group flex min-h-[110px] items-center gap-4 rounded-2xl border border-primary-100 bg-primary-50/40 p-5 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div data-option-icon
                                                class="w-5 h-5 rounded-[4px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span
                                                class="font-bold leading-snug text-[15px] text-primary-900">Instruments</span>
                                        </div>

                                        <!-- Option 6 -->
                                        <div class="quiz-option group flex min-h-[110px] items-center gap-4 rounded-2xl border border-primary-100 bg-primary-50/40 p-5 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div data-option-icon
                                                class="w-5 h-5 rounded-[4px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span
                                                class="font-bold leading-snug text-[15px] text-primary-900">Consumables</span>
                                        </div>
                                    </div>

                                    <!-- Other Specify Input -->
                                    <div class="mb-10">
                                        <label for="b2bOtherSpecify"
                                            class="block font-bold text-[14px] text-primary-900 mb-2.5">Other
                                            (Specify)</label>
                                        <input type="text" id="b2bOtherSpecify"
                                            placeholder="Specify any specialized diagnostic niche..."
                                            class="{{ $quizFieldClasses }} xl:w-[70%]" maxlength="150">
                                    </div>

                                    <!-- Pagination -->
                                    <div class="flex flex-col sm:flex-row justify-between items-center w-full pt-2 gap-4">
                                        <button type="button" class="{{ $quizSecondaryButtonClasses }} w-full sm:w-auto"
                                            onclick="prevStep(3)">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg> Back
                                        </button>
                                        <button type="button" class="{{ $quizPrimaryButtonClasses }} w-full sm:w-auto ml-auto"
                                            onclick="nextStep(5)">
                                            Continue
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Common Layout for B2C Step 4 & B2B Step 5 -->
                        <div class="quiz-step" data-step="5-common">
                            <div class="w-full">

                                <!-- Header Section -->
                                <div class="w-full mb-10 flex flex-col md:flex-row justify-between items-start gap-8">
                                    <div class="md:w-[55%]">
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4">PHASE
                                            05</span>
                                        <h2
                                            class="font-display text-2xl md:text-3xl font-bold text-primary-900 tracking-tight leading-[1.05] mb-5">
                                            Identify Your Business<br>Growth Levers</h2>
                                    </div>
                                    <div class="md:w-[40%] rounded-2xl border border-primary-100 bg-primary-50 p-6 md:p-8">
                                        <h3
                                            class="text-[11px] font-bold uppercase tracking-widest text-primary-800/70 mb-3">
                                            Strategic Growth Focus</h3>
                                        <p class="text-[13px] md:text-[14px] font-medium text-primary-800/80 leading-loose">
                                            Improving pricing, supply reliability, or expanding your offerings are not
                                            isolated decisions. The right combination of these factors creates long-term
                                            operational advantage in diagnostics.</p>
                                    </div>
                                </div>

                                <!-- Main Card Section -->
                                <div class="relative z-10 w-full mb-4">

                                    <!-- Header / Progress Area -->
                                    <div class="mb-10">
                                        <div class="flex justify-between items-end mb-3">
                                            <span
                                                class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm"
                                                data-step-badge>STEP
                                                05 OF 05</span>
                                        </div>
                                        <div class="w-full h-2 bg-primary-100 rounded-full overflow-hidden">
                                            <div class="h-full w-[90%] bg-primary-900 rounded-full"></div>
                                        </div>
                                    </div>

                                    <!-- Question Badge & Title -->
                                    <div class="flex items-center gap-4 mb-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-secondary-600 text-primary-900 flex items-center justify-center text-[11px] font-bold shadow-sm flex-shrink-0">
                                            5</div>
                                        <h3 class="text-[19px] md:text-[21px] font-bold text-primary-900 tracking-tight">
                                            Select your primary focus areas:</h3>
                                    </div>
                                    <p class="text-[14px] font-medium text-primary-800/60 mb-8 ml-12 italic">Multi-select
                                        available. Choose all that apply to your current roadmap.</p>

                                    <!-- Options Grid (Multi-Select) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 lg:px-4">
                                        <!-- Option 1 -->
                                        <div class="quiz-option group flex items-center justify-between min-h-[72px] rounded-[14px] border border-primary-100 bg-primary-50/40 px-6 py-4 cursor-pointer transition shadow-sm hover:border-primary-200 hover:bg-primary-50"
                                            onclick="toggleMultiSelection(this)">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[15px] text-primary-900 mb-0.5">Better pricing
                                                    margins</span>
                                                <span class="text-[12px] text-primary-800/60 font-medium">Optimize
                                                    procurement
                                                    and revenue cycle</span>
                                            </div>
                                            <div data-option-icon
                                                class="w-6 h-6 rounded-[5px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0">
                                                <svg class="w-4 h-4 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 2 -->
                                        <div class="quiz-option group flex items-center justify-between min-h-[72px] rounded-[14px] border border-primary-100 bg-primary-50/40 px-6 py-4 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[15px] text-primary-900 mb-0.5">Reliable and
                                                    consistent supply</span>
                                                <span class="text-[12px] text-primary-800/60 font-medium">Ensure laboratory
                                                    uptime and material access</span>
                                            </div>
                                            <div data-option-icon
                                                class="w-6 h-6 rounded-[5px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0 shadow-sm group-[.selected]:shadow-none">
                                                <svg class="w-4 h-4 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 3 -->
                                        <div class="quiz-option group flex items-center justify-between min-h-[72px] rounded-[14px] border border-primary-100 bg-primary-50/40 px-6 py-4 cursor-pointer transition hover:border-primary-200 hover:bg-primary-50 shadow-sm"
                                            onclick="toggleMultiSelection(this)">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[15px] text-primary-900 mb-0.5">Expanding
                                                    product
                                                    portfolio</span>
                                                <span class="text-[12px] text-primary-800/60 font-medium">Introduce new
                                                    diagnostic capabilities</span>
                                            </div>
                                            <div data-option-icon
                                                class="w-6 h-6 rounded-[5px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0 shadow-sm group-[.selected]:shadow-none">
                                                <svg class="w-4 h-4 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 4 -->
                                        <div class="quiz-option group flex items-center justify-between min-h-[72px] rounded-[14px] border border-primary-100 bg-primary-50/40 px-6 py-4 cursor-pointer transition shadow-sm hover:border-primary-200 hover:bg-primary-50"
                                            onclick="toggleMultiSelection(this)">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[15px] text-primary-900 mb-0.5">Technical
                                                    guidance
                                                    and support</span>
                                                <span class="text-[12px] text-primary-800/60 font-medium">Direct access to
                                                    clinical specialist insights</span>
                                            </div>
                                            <div data-option-icon
                                                class="w-6 h-6 rounded-[5px] border-2 border-primary-200 bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0">
                                                <svg class="w-4 h-4 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="flex flex-col sm:flex-row justify-between items-center w-full mt-4 pt-6 gap-4">
                                        <button type="button" class="{{ $quizSecondaryButtonClasses }} w-full sm:w-auto"
                                            onclick="currentSelection.role === 'dealer' || currentSelection.role === 'lab' ? prevStep(4) : prevStep(3)">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg> Back
                                        </button>
                                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 ml-auto w-full sm:w-auto">
                                            <span class="italic text-[13px] text-primary-800/60 font-medium text-center sm:text-left"
                                                id="commonStepCounter"></span>
                                            <button type="button" class="{{ $quizPrimaryButtonClasses }} w-full sm:w-auto"
                                                onclick="nextStep(6)">
                                                Continue
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        {{-- ────────── STEP 6: ASSESSMENT COMPLETE (LEAD FORM) ────────── --}}
                        <div class="quiz-step" data-step="6-lead">
                            <div class="w-full">

                                <!-- Header Section - matches 5-common layout -->
                                <div class="w-full mb-10 flex flex-col md:flex-row justify-between items-start gap-8">
                                    <div class="md:w-[55%]">
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-6">ASSESSMENT
                                            COMPLETE</span>
                                        <h2
                                            class="font-display text-2xl md:text-3xl font-bold text-primary-900 tracking-tight leading-[1.05] mb-5">
                                            Digital Concierge Insights<br>Now Ready</h2>
                                    </div>
                                    <div class="md:w-[40%] rounded-2xl border border-primary-100 bg-primary-50 p-6 md:p-8">
                                        <h3
                                            class="text-[11px] font-bold uppercase tracking-widest text-primary-800/70 mb-3">
                                            Analysis Completion</h3>
                                        <div
                                            class="flex items-center justify-between text-[13px] font-bold text-primary-900 mb-3">
                                            <span class="text-[14px] font-medium text-primary-800/80">Your profile
                                                assessment is complete.</span>
                                            <span>100%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-primary-100 rounded-full overflow-hidden">
                                            <div class="h-full w-full bg-secondary-600 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Card Section - matches 5-common layout -->
                                <div class="relative z-10 w-full mb-4">

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                                        <!-- Left: Qualification Info -->
                                        <div>
                                            <h4 class="text-[17px] leading-snug font-bold text-primary-900 mb-2">Based on
                                                your inputs, we've identified the right product pathways and pricing
                                                opportunities for your profile.</h4>
                                            <p
                                                class="mt-3 text-[12px] font-bold uppercase tracking-wider text-primary-800/45 mb-5">
                                                You now qualify for:</p>
                                            <ul class="space-y-3">
                                                <li class="flex items-start gap-3 text-[14px] font-medium text-primary-800">
                                                    <div
                                                        class="w-5 h-5 mt-0.5 rounded-full bg-secondary-100 flex items-center justify-center flex-shrink-0 text-secondary-700">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    Priority access to relevant product categories
                                                </li>
                                                <li class="flex items-start gap-3 text-[14px] font-medium text-primary-800">
                                                    <div
                                                        class="w-5 h-5 mt-0.5 rounded-full bg-secondary-100 flex items-center justify-center flex-shrink-0 text-secondary-700">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    Preferential pricing aligned with your scale
                                                </li>
                                                <li class="flex items-start gap-3 text-[14px] font-medium text-primary-800">
                                                    <div
                                                        class="w-5 h-5 mt-0.5 rounded-full bg-secondary-100 flex items-center justify-center flex-shrink-0 text-secondary-700">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    Direct support from the Biogenix team
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Right: Lead Capture Form -->
                                        <div class="rounded-2xl border border-primary-100 bg-primary-50/40 p-6 md:p-8">
                                            <div class="grid grid-cols-2 gap-4 mb-5">
                                                <div>
                                                    <label for="quizFirstName"
                                                        class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-primary-800/60">First
                                                        Name <span class="text-tertiary-600">*</span></label>
                                                    <input type="text" id="quizFirstName" required
                                                        class="{{ $quizFieldClasses }}" placeholder="John" maxlength="100">
                                                </div>
                                                <div>
                                                    <label for="quizLastName"
                                                        class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-primary-800/60">Last
                                                        Name</label>
                                                    <input type="text" id="quizLastName" class="{{ $quizFieldClasses }}"
                                                        placeholder="Doe" maxlength="100">
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <label for="quizEmail"
                                                    class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-primary-800/60">Email
                                                    Address <span class="text-tertiary-600">*</span></label>
                                                <input type="email" id="quizEmail" required class="{{ $quizFieldClasses }}"
                                                    placeholder="john.doe@medical-cloud.com" maxlength="255">
                                            </div>
                                            <div class="mb-6">
                                                <label for="quizPhone"
                                                    class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-primary-800/60">Phone
                                                    Number</label>
                                                <input type="tel" id="quizPhone" class="{{ $quizFieldClasses }}"
                                                    placeholder="+91 98765 43210" maxlength="20">
                                            </div>
                                            <p id="leadFormError"
                                                class="hidden rounded-[var(--ui-radius-field)] border border-tertiary-100 bg-tertiary-50 px-4 py-3 text-[12px] font-bold text-tertiary-700"
                                                role="alert"></p>

                                            <button type="button" id="quizSubmitButton"
                                                class="{{ $quizPrimaryButtonClasses }} w-full disabled:opacity-60 disabled:cursor-not-allowed"
                                                onclick="submitLeadForm()">
                                                <span id="quizSubmitText">Unlock My Score & Reward</span>
                                                <svg id="quizSubmitSpinner" class="h-4 w-4 animate-spin hidden" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <svg id="quizSubmitArrow" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>

                                            <p
                                                class="mt-5 flex items-center justify-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-primary-800/45">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Secure clinical gateway
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ────────── STEP 7: RESULTS ────────── --}}
                        <div class="quiz-step" data-step="results">
                            <div class="w-full">

                                <!-- Results Header - matches 5-common layout -->
                                <div class="max-w-full w-full mx-auto mb-10">
                                    <span
                                        class="inline-block px-4 py-1.5 rounded-full bg-secondary-600 text-primary-900 text-[11px] font-bold tracking-widest uppercase shadow-sm mb-6">Assessment
                                        Complete</span>
                                    <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                                        <div class="lg:w-[55%]">
                                            <h2 class="font-display text-2xl md:text-3xl font-bold tracking-tight text-primary-900 leading-[1.05]"
                                                id="quizResultTitle">Advanced<br>Proficiency Level<br>Attained.</h2>
                                            <p class="mt-5 max-w-md text-[14px] leading-relaxed text-primary-800/60"
                                                id="quizResultDescription">Your technical precision in diagnostic protocols
                                                demonstrates exceptional mastery of Biogenix standards and laboratory
                                                compliance.</p>
                                        </div>
                                        <div class="lg:w-[40%] flex items-center justify-center">
                                            <div class="relative h-48 w-48">
                                                <svg viewBox="0 0 200 200" class="h-full w-full">
                                                    <circle class="stroke-primary-100" cx="100" cy="100" r="85" fill="none"
                                                        stroke-width="8" />
                                                    <circle
                                                        class="stroke-secondary-600 transition-[stroke-dashoffset] duration-[1200ms] ease-in-out"
                                                        id="scoreRingFill" cx="100" cy="100" r="85" fill="none"
                                                        stroke-width="10" stroke-linecap="round" stroke-dasharray="534"
                                                        stroke-dashoffset="134" transform="rotate(-90 100 100)" />
                                                </svg>
                                                <div
                                                    class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                                                    <span
                                                        class="font-display text-[24px] leading-tight font-extrabold text-primary-900 mt-2"
                                                        id="scoreValue">Strong<br>Match</span>
                                                    <span
                                                        class="mt-2 text-[9px] font-bold uppercase tracking-[0.15em] text-primary-800/45">Profile
                                                        Alignment Score</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Insight Summary Card - matches 5-common layout -->
                                <div class="relative z-10 w-full mb-6">
                                    <h3 class="flex items-center gap-3 text-[20px] font-bold text-primary-900">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-900">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        Profile Insight Summary
                                    </h3>

                                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                                        {{-- 1. Diagnostic Fit --}}
                                        <div
                                            class="rounded-2xl border-2 border-primary-50 hover:border-primary-100 bg-primary-50/40 p-6 transition-all duration-300">
                                            <div
                                                class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-900 mb-4 font-bold text-[13px]">
                                                1</div>
                                            <p class="text-[15px] font-bold text-primary-900 mb-2">Diagnostic Fit</p>
                                            <p class="text-[13px] leading-relaxed text-primary-800/60 min-h-[60px]">How well
                                                your
                                                current setup aligns with scalable diagnostic workflows.</p>
                                            <div
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-primary-700 shadow-sm border border-primary-100 w-full">
                                                <span class="h-2 w-2 rounded-full bg-secondary-600 animate-pulse"></span>
                                                <span id="insightOutcome1">Optimal Alignment</span>
                                            </div>
                                        </div>

                                        {{-- 2. Procurement Efficiency --}}
                                        <div
                                            class="rounded-2xl border-2 border-secondary-100 hover:border-secondary-200 bg-secondary-50/60 p-6 transition-all duration-300">
                                            <div
                                                class="w-8 h-8 rounded-full bg-secondary-100 flex items-center justify-center text-primary-900 mb-4 font-bold text-[13px]">
                                                2</div>
                                            <p class="text-[15px] font-bold text-primary-900 mb-2">Procurement Efficiency
                                            </p>
                                            <p class="text-[13px] leading-relaxed text-primary-800/60 min-h-[60px]">How
                                                optimized
                                                your pricing strategy currently is.</p>
                                            <div
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-primary-700 shadow-sm border border-primary-100 w-full">
                                                <span class="h-2 w-2 rounded-full bg-secondary-600"></span>
                                                <span id="insightOutcome2">High Potential</span>
                                            </div>
                                        </div>

                                        {{-- 3. Strategy Alignment --}}
                                        <div
                                            class="rounded-2xl border-2 border-primary-50 hover:border-primary-100 bg-primary-50/40 p-6 transition-all duration-300">
                                            <div
                                                class="w-8 h-8 rounded-full bg-secondary-100 flex items-center justify-center text-primary-900 mb-4 font-bold text-[13px]">
                                                3</div>
                                            <p class="text-[15px] font-bold text-primary-900 mb-2">Platform Strategy</p>
                                            <p class="text-[13px] leading-relaxed text-primary-800/60 min-h-[60px]">How well
                                                your
                                                focus matches high-demand categories.</p>
                                            <div
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-primary-700 shadow-sm border border-primary-100 w-full">
                                                <span class="h-2 w-2 rounded-full bg-secondary-600"></span>
                                                <span id="insightOutcome3">Strong Match</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-10 flex flex-col md:flex-row border-t border-primary-100 pt-8 items-center justify-between gap-6">
                                        <p class="text-[13px] font-medium text-primary-800/60 text-center md:text-left">
                                            Our specialized team will reach out with your custom portfolio shortly.
                                        </p>
                                        <a href="/" class="{{ $quizPrimaryButtonClasses }} w-full md:w-auto">
                                            Return to Home
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        let currentSelection = {
            role: '',
            practiceType: '',
            businessType: '',
            firstName: '',
            lastName: '',
            email: '',
            phone: '',
        };

        function getStepOptions(stepEl) {
            return stepEl ? Array.from(stepEl.querySelectorAll('.quiz-option')) : [];
        }

        function isMultiSelectStep(stepEl) {
            return Boolean(stepEl) && ['4-b2b', '5-common'].includes(stepEl.dataset.step);
        }

        function moveOptionFocus(options, currentIndex, direction) {
            if (!options.length) {
                return null;
            }

            const nextIndex = (currentIndex + direction + options.length) % options.length;
            options[nextIndex].focus();
            return options[nextIndex];
        }

        function getStepLabel(stepEl) {
            const heading = stepEl?.querySelector('h2, h3');
            return heading ? heading.textContent.replace(/\s+/g, ' ').trim() : 'Quiz options';
        }

        function getStepErrorId(stepEl) {
            return stepEl ? `quiz-step-error-${stepEl.dataset.step}` : '';
        }

        function syncOptionAccessibility(stepEl) {
            const options = getStepOptions(stepEl);
            if (!options.length) {
                return;
            }

            const multiple = isMultiSelectStep(stepEl);
            const group = options[0].parentElement;

            if (group) {
                group.setAttribute('role', multiple ? 'group' : 'radiogroup');
                group.setAttribute('aria-label', getStepLabel(stepEl));
            }

            const selectedIndex = options.findIndex((option) => option.classList.contains('selected'));
            const focusableIndex = selectedIndex >= 0 ? selectedIndex : 0;
            const errorId = getStepErrorId(stepEl);

            options.forEach((option, index) => {
                const isSelected = option.classList.contains('selected');
                const optionLabel = option.querySelector('.option-title')?.textContent?.replace(/\s+/g, ' ').trim() ||
                    option.querySelector('h4')?.textContent?.replace(/\s+/g, ' ').trim() ||
                    option.querySelector('span.font-bold')?.textContent?.replace(/\s+/g, ' ').trim() ||
                    option.querySelector('p.font-bold')?.textContent?.replace(/\s+/g, ' ').trim() ||
                    option.textContent.replace(/\s+/g, ' ').trim();

                option.setAttribute('role', multiple ? 'checkbox' : 'radio');
                option.setAttribute('aria-checked', isSelected ? 'true' : 'false');
                option.setAttribute('aria-disabled', option.dataset.disabled === 'true' ? 'true' : 'false');
                option.setAttribute('aria-label', optionLabel);
                option.setAttribute('aria-describedby', errorId);
                option.setAttribute('tabindex', multiple ? '0' : (index === focusableIndex ? '0' : '-1'));

                if (!option.dataset.keyboardReady) {
                    option.dataset.keyboardReady = 'true';
                    option.addEventListener('keydown', function (event) {
                        if (option.dataset.disabled === 'true') {
                            return;
                        }

                        if (event.key === ' ' || event.key === 'Enter') {
                            event.preventDefault();
                            option.click();
                            return;
                        }

                        if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                            event.preventDefault();
                            const nextOption = moveOptionFocus(options, index, 1);
                            if (!multiple) {
                                nextOption?.click();
                            }
                        }

                        if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                            event.preventDefault();
                            const previousOption = moveOptionFocus(options, index, -1);
                            if (!multiple) {
                                previousOption?.click();
                            }
                        }

                        if (event.key === 'Home') {
                            event.preventDefault();
                            options[0]?.focus();
                            if (!multiple) {
                                options[0]?.click();
                            }
                        }

                        if (event.key === 'End') {
                            event.preventDefault();
                            const lastOption = options[options.length - 1];
                            lastOption?.focus();
                            if (!multiple) {
                                lastOption?.click();
                            }
                        }
                    });
                }
            });
        }

        function ensureStepErrorElement(stepEl) {
            const contentRoot = stepEl?.firstElementChild || stepEl;
            if (!contentRoot) {
                return null;
            }

            let error = contentRoot.querySelector('[data-quiz-step-error]');

            if (!error) {
                error = document.createElement('p');
                error.dataset.quizStepError = 'true';
                error.className = 'quiz-step-feedback mt-6 hidden px-4 py-3 text-[13px] font-semibold';
                error.id = getStepErrorId(stepEl);
                error.setAttribute('role', 'alert');
                error.setAttribute('aria-live', 'polite');
                contentRoot.appendChild(error);
            }

            return error;
        }

        function clearOptionInvalidState(stepEl) {
            getStepOptions(stepEl).forEach((option) => option.setAttribute('aria-invalid', 'false'));
        }

        function setOptionInvalidState(stepEl, isInvalid) {
            getStepOptions(stepEl).forEach((option) => option.setAttribute('aria-invalid', isInvalid ? 'true' : 'false'));
        }

        function clearStepError(stepEl) {
            if (!stepEl) {
                return;
            }

            const error = (stepEl.firstElementChild || stepEl).querySelector('[data-quiz-step-error]');

            if (error) {
                error.textContent = '';
                error.classList.add('hidden');
            }

            clearOptionInvalidState(stepEl);
        }

        function showStepError(stepEl, message, markOptionsInvalid = true) {
            const error = ensureStepErrorElement(stepEl);
            if (!error) {
                return;
            }

            error.textContent = message;
            error.classList.remove('hidden');
            if (markOptionsInvalid) {
                setOptionInvalidState(stepEl, true);
            } else {
                clearOptionInvalidState(stepEl);
            }
        }

        function syncConditionalFields() {
            const roleOtherField = document.getElementById('roleOtherField');
            const roleOtherInput = roleOtherField ? roleOtherField.querySelector('input') : null;
            const practiceOtherField = document.getElementById('practiceOtherField');
            const practiceOtherInput = practiceOtherField ? practiceOtherField.querySelector('input') : null;
            const businessOtherInput = document.getElementById('businessOtherRole');
            const businessOtherCard = businessOtherInput ? businessOtherInput.closest('.quiz-option') : null;

            if (roleOtherField) {
                const showRoleOtherField = currentSelection.role === 'other';
                roleOtherField.classList.toggle('hidden', !showRoleOtherField);
                roleOtherField.setAttribute('aria-hidden', showRoleOtherField ? 'false' : 'true');
                if (roleOtherInput && !showRoleOtherField) {
                    roleOtherInput.value = '';
                    setFieldValidity(roleOtherInput, true);
                }
            }

            if (practiceOtherField) {
                const showPracticeOtherField = currentSelection.practiceType === 'other_spec';
                practiceOtherField.classList.toggle('hidden', !showPracticeOtherField);
                practiceOtherField.setAttribute('aria-hidden', showPracticeOtherField ? 'false' : 'true');
                if (practiceOtherInput && !showPracticeOtherField) {
                    practiceOtherInput.value = '';
                    setFieldValidity(practiceOtherInput, true);
                }
            }

            if (businessOtherInput) {
                const enableBusinessOtherInput = currentSelection.businessType === 'other_b2b';
                businessOtherInput.disabled = !enableBusinessOtherInput;
                businessOtherInput.setAttribute('aria-disabled', enableBusinessOtherInput ? 'false' : 'true');
                if (!enableBusinessOtherInput) {
                    businessOtherInput.value = '';
                    setFieldValidity(businessOtherInput, true);
                }

                if (businessOtherCard) {
                    businessOtherCard.setAttribute('data-disabled', enableBusinessOtherInput ? 'false' : 'true');
                }
            }
        }

        function getConditionalFieldValidation(stepEl) {
            if (!stepEl) {
                return { isValid: true, input: null, message: '' };
            }

            if (stepEl.dataset.step === '1' && currentSelection.role === 'other') {
                const input = document.querySelector('#roleOtherField input');
                const value = input ? input.value.trim() : '';
                return {
                    isValid: Boolean(value),
                    input,
                    message: 'Please tell us your role or organization type to continue.',
                };
            }

            if (stepEl.dataset.step === '2-b2c' && currentSelection.practiceType === 'other_spec') {
                const input = document.querySelector('#practiceOtherField input');
                const value = input ? input.value.trim() : '';
                return {
                    isValid: Boolean(value),
                    input,
                    message: 'Please describe your practice area to continue.',
                };
            }

            if (stepEl.dataset.step === '2-b2b' && currentSelection.businessType === 'other_b2b') {
                const input = document.getElementById('businessOtherRole');
                const value = input ? input.value.trim() : '';
                return {
                    isValid: Boolean(value),
                    input,
                    message: 'Please specify your business type to continue.',
                };
            }

            return { isValid: true, input: null, message: '' };
        }

        function handleSelection(step, value, el) {
            const parentStep = el.closest('.quiz-step');
            if (!parentStep) {
                return;
            }

            getStepOptions(parentStep).forEach((option) => option.classList.remove('selected'));
            el.classList.add('selected');

            if (step === 1) {
                currentSelection.role = value;
                currentSelection.practiceType = '';
                currentSelection.businessType = '';
            }

            if (step === 2 && parentStep.dataset.step === '2-b2c') {
                currentSelection.practiceType = value;
            }

            if (step === 2 && parentStep.dataset.step === '2-b2b') {
                currentSelection.businessType = value;
            }

            clearStepError(parentStep);
            syncConditionalFields();
            const conditionalFieldState = getConditionalFieldValidation(parentStep);
            if (conditionalFieldState.input) {
                setFieldValidity(conditionalFieldState.input, true);
            }
            syncOptionAccessibility(parentStep);
        }

        function toggleMultiSelection(el) {
            const parentStep = el.closest('.quiz-step');
            if (!parentStep) {
                return;
            }

            el.classList.toggle('selected');
            clearStepError(parentStep);
            syncOptionAccessibility(parentStep);
        }

        function getActualStep(step) {
            if (step === 2 || step === 3) {
                if (currentSelection.role === 'dealer' || currentSelection.role === 'lab') {
                    return step + '-b2b';
                }

                return step + '-b2c';
            }

            if (step === 4) {
                if (currentSelection.role === 'dealer' || currentSelection.role === 'lab') {
                    return '4-b2b';
                }

                return '5-common';
            }

            if (step === 5) {
                return '5-common';
            }

            if (step === 6) {
                return '6-lead';
            }

            if (step === 7) {
                return 'results';
            }

            return step;
        }

        function updateLayoutForStep(actualStep) {
            const quizContainer = document.querySelector('.quiz-container');
            const leftPanel = document.querySelector('.left-panel-bg');
            const leftPanelOverlay = document.getElementById('leftPanelOverlay');
            const leftPanelIntro = document.getElementById('leftPanelIntro');
            const quizVisualCaption = document.getElementById('quizVisualCaption');
            const quizVisualCaptionTitle = document.getElementById('quizVisualCaptionTitle');
            const quizVisualCaptionDescription = document.getElementById('quizVisualCaptionDescription');
            const formArea = document.getElementById('quizFormArea');
            const headerRow = formArea ? formArea.querySelector('.flex.flex-col.lg\\:flex-row') : null;
            const rightPanel = document.getElementById('quizRightPanel');
            const counter = document.getElementById('leftStepCounter');
            const title = document.getElementById('leftStepTitle');
            const isStepTwoPractice = actualStep === '2-b2c';

            if (actualStep === 1) {
                if (quizContainer) {
                    quizContainer.classList.remove('quiz-compact');
                }

                if (leftPanel) {
                    leftPanel.style.display = '';
                    leftPanel.classList.remove('order-2', 'md:order-2', 'border-l');
                    leftPanel.classList.add('border-r');
                }

                if (rightPanel) {
                    rightPanel.classList.remove('order-1', 'md:order-1');
                    rightPanel.className = 'flex-1 flex flex-col p-6 md:p-12 xl:p-20 overflow-y-auto';
                }

                if (leftPanelOverlay) {
                    leftPanelOverlay.className = 'absolute inset-0 left-panel-overlay z-0';
                }

                if (leftPanelIntro) {
                    leftPanelIntro.classList.remove('hidden');
                }

                if (quizVisualCaption) {
                    quizVisualCaption.classList.add('hidden');
                }

                if (headerRow) {
                    headerRow.style.display = '';
                }

                if (formArea) {
                    formArea.classList.add('max-w-4xl');
                    formArea.classList.remove('max-w-full', 'lg:max-w-[80%]');
                }

                if (counter) {
                    counter.innerText = 'STEP 01 OF 04';
                    counter.className = 'inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 mb-4 shadow-sm';
                }

                if (title) {
                    title.innerHTML = 'Let\'s Understand<br>Your Setup';
                }
            } else if (isStepTwoPractice) {
                if (quizContainer) {
                    quizContainer.classList.remove('quiz-compact');
                }

                if (leftPanel) {
                    leftPanel.style.display = '';
                    leftPanel.classList.remove('border-r');
                    leftPanel.classList.add('order-2', 'md:order-2', 'border-l');
                }

                if (rightPanel) {
                    rightPanel.className = 'order-1 md:order-1 flex-1 flex flex-col p-6 md:p-10 xl:p-12 overflow-y-auto';
                }

                if (leftPanelOverlay) {
                    leftPanelOverlay.className = 'absolute inset-0 bg-gradient-to-t from-primary-900 via-primary-900/60 to-transparent z-0';
                }

                if (leftPanelIntro) {
                    leftPanelIntro.classList.add('hidden');
                }

                if (quizVisualCaption) {
                    quizVisualCaption.classList.remove('hidden');
                }

                if (quizVisualCaptionTitle) {
                    quizVisualCaptionTitle.textContent = 'Practice-Driven Diagnostics';
                }

                if (quizVisualCaptionDescription) {
                    quizVisualCaptionDescription.textContent = 'Each medical specialization has distinct diagnostic priorities. Choosing the right tests and kits aligned with your practice can improve patient outcomes while creating additional in-clinic value.';
                }

                if (headerRow) {
                    headerRow.style.display = 'none';
                }

                if (formArea) {
                    formArea.classList.add('max-w-4xl');
                    formArea.classList.remove('max-w-full', 'lg:max-w-[80%]');
                }
            } else {
                if (quizContainer) {
                    quizContainer.classList.add('quiz-compact');
                }

                if (leftPanel) {
                    leftPanel.style.display = 'none';
                    leftPanel.classList.remove('order-2', 'md:order-2', 'border-l');
                    leftPanel.classList.add('border-r');
                }

                if (rightPanel) {
                    rightPanel.classList.remove('order-1', 'md:order-1');
                    rightPanel.className = 'flex-1 flex flex-col px-6 py-8 md:px-10 md:py-10 xl:px-12 xl:py-12 overflow-y-auto';
                }

                if (leftPanelOverlay) {
                    leftPanelOverlay.className = 'absolute inset-0 left-panel-overlay z-0';
                }

                if (leftPanelIntro) {
                    leftPanelIntro.classList.remove('hidden');
                }

                if (quizVisualCaption) {
                    quizVisualCaption.classList.add('hidden');
                }

                if (headerRow) {
                    headerRow.style.display = 'none';
                }

                if (formArea) {
                    formArea.classList.remove('max-w-4xl');
                    formArea.classList.add('max-w-full', 'lg:max-w-[80%]');
                }

                if (actualStep === '5-common') {
                    const counter = document.getElementById('commonStepCounter');
                    const badge = document.querySelector('[data-step="5-common"] [data-step-badge]');

                    if (currentSelection.role === 'dealer' || currentSelection.role === 'lab') {
                        if (counter) counter.textContent = '';
                        if (badge) badge.textContent = 'STEP 05 OF 05';
                    } else {
                        if (counter) counter.textContent = '';
                        if (badge) badge.textContent = 'STEP 04 OF 04';
                    }
                }

                if (actualStep === '6-lead') {
                    const badge = document.querySelector('[data-step="6-lead"] .inline-flex.rounded-full.bg-secondary-600');
                    if (badge) {
                        badge.textContent = 'ASSESSMENT COMPLETE';
                    }
                }
            }
        }

        function resetQuizViewport() {
            const rightPanel = document.getElementById('quizRightPanel');
            const quizContainer = document.querySelector('.quiz-container');

            if (rightPanel) {
                rightPanel.scrollTop = 0;
            }

            if (quizContainer) {
                window.requestAnimationFrame(() => {
                    quizContainer.scrollIntoView({ block: 'start', behavior: 'auto' });
                });
            }
        }

        function activateStep(targetEl, actualStep) {
            if (!targetEl) {
                return;
            }

            targetEl.style.display = 'block';
            window.requestAnimationFrame(() => {
                window.requestAnimationFrame(() => {
                    targetEl.classList.add('active');
                    syncConditionalFields();
                    syncOptionAccessibility(targetEl);

                    const focusTarget = targetEl.querySelector('h2, h3, .quiz-option');
                    if (focusTarget) {
                        if (!focusTarget.getAttribute('tabindex')) {
                            focusTarget.setAttribute('tabindex', '-1');
                        }
                        focusTarget.focus({ preventScroll: true });
                    }
                });
            });

            updateLayoutForStep(actualStep);
            clearStepError(targetEl);
            resetQuizViewport();
        }

        function getSelectionValidationMessage(stepEl) {
            if (!stepEl) {
                return 'Please select an option to continue.';
            }

            if (stepEl.dataset.step === '4-b2b') {
                return 'Select at least one product focus area to continue.';
            }

            if (stepEl.dataset.step === '5-common') {
                return 'Select at least one growth priority to continue.';
            }

            return 'Please select an option to continue.';
        }

        function canContinueWithoutSelection(stepEl) {
            return stepEl.dataset.step === '6-lead' || stepEl.dataset.step === 'results';
        }

        function nextStep(step) {
            const active = document.querySelector('.quiz-step.active');
            const actualStep = getActualStep(step);
            const nextEl = document.querySelector('.quiz-step[data-step="' + actualStep + '"]');

            if (!active || !nextEl) {
                console.warn('Quiz step transition failed.', { requestedStep: step, actualStep });
                return;
            }

            if (!canContinueWithoutSelection(active) && !active.querySelector('.quiz-option.selected')) {
                const message = getSelectionValidationMessage(active);
                showStepError(active, message);
                active.querySelector('.quiz-option')?.focus();

                if (window.BiogenixToast && typeof window.BiogenixToast.show === 'function') {
                    window.BiogenixToast.show(message, 'warning', 3000);
                }

                return;
            }

            const conditionalFieldState = getConditionalFieldValidation(active);
            if (!conditionalFieldState.isValid) {
                showStepError(active, conditionalFieldState.message, false);
                setFieldValidity(conditionalFieldState.input, false);
                conditionalFieldState.input?.focus();

                if (window.BiogenixToast && typeof window.BiogenixToast.show === 'function') {
                    window.BiogenixToast.show(conditionalFieldState.message, 'warning', 3000);
                }

                return;
            }

            active.classList.remove('active');
            active.style.display = 'none';

            activateStep(nextEl, actualStep);
        }

        function prevStep(stepTarget) {
            const active = document.querySelector('.quiz-step.active');

            if (stepTarget === undefined || !stepTarget) {
                window.history.back();
                return;
            }

            const actualStep = getActualStep(stepTarget);
            const prevEl = document.querySelector('.quiz-step[data-step="' + actualStep + '"]');

            if (!active || !prevEl) {
                console.warn('Quiz step transition failed.', { requestedStep: stepTarget, actualStep });
                return;
            }

            active.classList.remove('active');
            active.style.display = 'none';

            activateStep(prevEl, actualStep);
        }

        function setFieldValidity(input, isValid) {
            if (!input) {
                return;
            }

            input.setAttribute('aria-invalid', isValid ? 'false' : 'true');
            input.classList.toggle('quiz-field-invalid', !isValid);
        }

        function hideLeadError() {
            const leadError = document.getElementById('leadFormError');
            if (!leadError) {
                return;
            }

            leadError.textContent = '';
            leadError.classList.add('hidden');
        }

        function showLeadError(message) {
            const leadError = document.getElementById('leadFormError');
            if (!leadError) {
                return;
            }

            leadError.textContent = message;
            leadError.classList.remove('hidden');
        }

        function setSubmitLoading(isLoading) {
            const button = document.getElementById('quizSubmitButton');
            const text = document.getElementById('quizSubmitText');
            const spinner = document.getElementById('quizSubmitSpinner');
            const arrow = document.getElementById('quizSubmitArrow');

            if (button) {
                button.disabled = isLoading;
            }

            if (text) {
                text.textContent = isLoading ? 'Processing...' : 'Unlock My Score & Reward';
            }

            if (spinner) {
                spinner.classList.toggle('hidden', !isLoading);
            }

            if (arrow) {
                arrow.classList.toggle('hidden', isLoading);
            }
        }

        function submitLeadForm() {
            const firstNameInput = document.getElementById('quizFirstName');
            const lastNameInput = document.getElementById('quizLastName');
            const emailInput = document.getElementById('quizEmail');
            const phoneInput = document.getElementById('quizPhone');
            const firstName = firstNameInput ? firstNameInput.value.trim() : '';
            const lastName = lastNameInput ? lastNameInput.value.trim() : '';
            const email = emailInput ? emailInput.value.trim() : '';
            const phone = phoneInput ? phoneInput.value.trim() : '';

            hideLeadError();
            [firstNameInput, lastNameInput, emailInput, phoneInput].forEach((input) => setFieldValidity(input, true));

            if (!firstName) {
                showLeadError('Please enter your first name to continue.');
                setFieldValidity(firstNameInput, false);
                firstNameInput?.focus();
                return;
            }

            if (!emailInput || !emailInput.checkValidity()) {
                showLeadError('Please enter a valid email address to continue.');
                setFieldValidity(emailInput, false);
                emailInput?.focus();
                return;
            }

            currentSelection.firstName = firstName;
            currentSelection.lastName = lastName;
            currentSelection.email = email;
            currentSelection.phone = phone;

            setSubmitLoading(true);

            setTimeout(() => {
                setSubmitLoading(false);

                const active = document.querySelector('.quiz-step.active');
                if (active) {
                    active.classList.remove('active');
                    active.style.display = 'none';
                }

                const resultsEl = document.querySelector('.quiz-step[data-step="results"]');
                activateStep(resultsEl, 'results');
            }, 600);
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.quiz-step').forEach((stepEl) => syncOptionAccessibility(stepEl));
            syncConditionalFields();

            document.querySelectorAll('#roleOtherField input, #practiceOtherField input, #businessOtherRole').forEach((input) => {
                input?.addEventListener('input', function () {
                    setFieldValidity(input, true);
                    clearStepError(input.closest('.quiz-step'));
                });
            });

            document.querySelectorAll('#quizFirstName, #quizLastName, #quizEmail, #quizPhone').forEach((input) => {
                input?.addEventListener('input', function () {
                    setFieldValidity(input, true);
                    hideLeadError();
                });
            });
        });
    </script>
@endsection
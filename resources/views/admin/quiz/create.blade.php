@extends('admin.layout')

@section('title', 'Add New Questions - Quiz Management')

@section('admin_content')

            <div data-quiz-create-page>
            <!-- Header -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.quiz.index') }}" class="ajax-link inline-flex items-center gap-2 text-sm text-[var(--ui-text-muted)] hover:text-primary-600 transition mb-2 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Quiz Management
                    </a>
                    <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Question Bank Repository</h2>
                    <p class="text-sm text-[var(--ui-text-muted)] mt-1">Organize and manage MCQ sets for biological engineering certifications.</p>
                </div>
                
                <!-- Quick Search -->
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search questions..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-9 pr-4 py-2 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-[var(--ui-text-muted)]">
                </div>

                <!-- Total Questions KPI -->
                <div class="bg-primary-900 rounded-xl p-4 shadow-md border border-primary-800 min-w-[150px]">
                    <p class="text-[10px] font-bold text-primary-200 uppercase tracking-widest mb-1.5">Total Questions</p>
                    <h3 class="text-3xl font-black text-white tracking-tight">{{ number_format($questionGroups['total_count']) }}</h3>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mt-6 border-b border-[var(--ui-border)]">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" class="quiz-tab border-primary-600 text-[var(--ui-text)] whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm" data-target="common-questions">Common Questions</button>
                    <button type="button" class="quiz-tab border-transparent text-[var(--ui-text-muted)] hover:text-[var(--ui-text)] hover:border-slate-300 whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition" data-target="b2b-questions">B2B Questions</button>
                    <button type="button" class="quiz-tab border-transparent text-[var(--ui-text-muted)] hover:text-[var(--ui-text)] hover:border-slate-300 whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition" data-target="b2c-questions">B2C Questions</button>
                </nav>
            </div>

            <!-- TAB CONTENT: COMMON QUESTIONS -->
            <div id="common-questions" class="tab-pane block">
                <!-- Create New MCQ Form -->
                <div class="bg-[var(--ui-surface)] rounded-2xl p-6 lg:p-8 shadow-[var(--ui-shadow-soft)] border-2 border-dashed border-slate-200 mt-6 relative shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
                    <h3 class="text-base font-extrabold text-[var(--ui-text)] flex items-center gap-2 mb-6">
                        <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Create New MCQ (Common)
                    </h3>

                    <form method="POST" action="{{ route('admin.quiz.questions.store') }}">
                        @csrf
                        <input type="hidden" name="user_type" value="common">
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Question Prompt</label>
                            <textarea name="question_text" rows="3" placeholder="Enter the technical question here..." required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl px-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-400 font-medium"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Option A -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" name="option_a" placeholder="Option A" required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-medium font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select name="target_flow_a" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option value="common" selected>Continue Common</option>
                                        <option value="b2b">B2B Flow</option>
                                        <option value="b2c">B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option B -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" name="option_b" placeholder="Option B" required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select name="target_flow_b" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option value="common" selected>Continue Common</option>
                                        <option value="b2b">B2B Flow</option>
                                        <option value="b2c">B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option C -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" name="option_c" placeholder="Option C" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select name="target_flow_c" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option value="common" selected>Continue Common</option>
                                        <option value="b2b">B2B Flow</option>
                                        <option value="b2c">B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option D -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" name="option_d" placeholder="Option D" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select name="target_flow_d" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option value="common" selected>Continue Common</option>
                                        <option value="b2b">B2B Flow</option>
                                        <option value="b2c">B2C Flow</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Correct Answer</label>
                            <select name="correct_option" required class="bg-[var(--ui-input-bg)] border border-[var(--ui-border)] text-sm rounded-xl px-4 py-2.5 outline-none">
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-primary-900 border border-primary-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-800 transition shadow-md">Save Question</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Questions List -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest">Existing Questions ({{ $questionGroups['common_count'] }})</h3>
                        <div class="flex gap-2 text-slate-400">
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg></button>
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m0 0v8m0-8h8"></path></svg></button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse ($questionGroups['common'] as $question)
                            @php
                                $questionPrefix = 'Q-' . str_pad($question->id, 3, '0', STR_PAD_LEFT);
                                $activeClass   = $question->is_active ? '' : 'opacity-60';
                                $toggleBg      = $question->is_active ? 'bg-primary-900' : 'bg-slate-200';
                                $toggleLabel   = $question->is_active ? 'Active' : 'Disabled';
                                $toggleTranslate = $question->is_active ? 'transform translate-x-4' : '';
                            @endphp
                            <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative {{ $activeClass }}">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="bg-primary-900 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: {{ $questionPrefix }}</span>
                                        @if ($question->phase_title)
                                            <span class="text-xs font-semibold text-slate-500">{{ $question->phase_title }}</span>
                                        @endif
                                    </div>
                                    <h4 class="text-base font-bold text-slate-800 mb-4">{{ $question->question_text }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                        @foreach ($question->answerOptions as $option)
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2 {{ $option->is_correct_answer ? 'font-bold text-slate-800' : 'text-slate-400' }}">
                                                    @if ($option->is_correct_answer)
                                                        <div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div>
                                                    @else
                                                        <div class="h-1 w-1 bg-slate-400 rounded-full"></div>
                                                    @endif
                                                    {{ $option->option_text }}
                                                </div>
                                                @if ($option->target_flow !== 'common')
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border flex items-center gap-1
                                                        {{ $option->target_flow === 'b2b' ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-emerald-600 bg-emerald-50 border-emerald-100' }}">
                                                        {{ strtoupper($option->target_flow) }} Flow
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Actions -->
                                <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="w-10 h-6 {{ $toggleBg }} rounded-full relative cursor-pointer shadow-inner"
                                            onclick="toggleQuestionStatus({{ $question->id }}, this)">
                                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md {{ $toggleTranslate }}"></div>
                                        </div>
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">{{ $toggleLabel }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <form method="POST" action="{{ route('admin.quiz.questions.destroy', $question->id) }}" onsubmit="return confirm('Delete this question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 text-center py-8">No common questions yet. Add one using the form above.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: B2B QUESTIONS -->
            <div id="b2b-questions" class="tab-pane hidden">
                <div class="bg-[var(--ui-surface)] rounded-2xl p-6 lg:p-8 shadow-[var(--ui-shadow-soft)] border-2 border-dashed border-slate-200 mt-6 relative shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
                    <h3 class="text-base font-extrabold text-[var(--ui-text)] flex items-center gap-2 mb-6">
                        <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Create New MCQ (B2B)
                    </h3>
                    <form method="POST" action="{{ route('admin.quiz.questions.store') }}">
                        @csrf
                        <input type="hidden" name="user_type" value="b2b">
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Question Prompt</label>
                            <textarea name="question_text" rows="3" placeholder="Enter the technical question here..." required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl px-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-400 font-medium"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_a" placeholder="Option A" required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_a" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b" selected>B2B Flow</option><option value="b2c">B2C Flow</option></select></div>
                            </div>
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_b" placeholder="Option B" required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_b" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b" selected>B2B Flow</option><option value="b2c">B2C Flow</option></select></div>
                            </div>
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_c" placeholder="Option C" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_c" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b" selected>B2B Flow</option><option value="b2c">B2C Flow</option></select></div>
                            </div>
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_d" placeholder="Option D" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_d" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b" selected>B2B Flow</option><option value="b2c">B2C Flow</option></select></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Correct Answer</label>
                            <select name="correct_option" required class="bg-[var(--ui-input-bg)] border border-[var(--ui-border)] text-sm rounded-xl px-4 py-2.5 outline-none"><option value="A">Option A</option><option value="B">Option B</option><option value="C">Option C</option><option value="D">Option D</option></select>
                        </div>
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-primary-900 border border-primary-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-800 transition shadow-md">Save Question</button>
                        </div>
                    </form>
                </div>

                <!-- Existing B2B Questions List -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest">Existing B2B Questions ({{ $questionGroups['b2b_count'] }})</h3>
                    </div>
                    <div class="space-y-4">
                        @forelse ($questionGroups['b2b'] as $question)
                            @php
                                $questionPrefix  = 'B2B-' . str_pad($question->id, 3, '0', STR_PAD_LEFT);
                                $activeClass     = $question->is_active ? '' : 'opacity-60';
                                $toggleBg        = $question->is_active ? 'bg-primary-900' : 'bg-slate-200';
                                $toggleLabel     = $question->is_active ? 'Active' : 'Disabled';
                                $toggleTranslate = $question->is_active ? 'transform translate-x-4' : '';
                            @endphp
                            <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative {{ $activeClass }}">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: {{ $questionPrefix }}</span>
                                        @if ($question->phase_title)<span class="text-xs font-semibold text-slate-500">{{ $question->phase_title }}</span>@endif
                                    </div>
                                    <h4 class="text-base font-bold text-slate-800 mb-4">{{ $question->question_text }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                        @foreach ($question->answerOptions as $option)
                                            <div class="flex items-center gap-2 {{ $option->is_correct_answer ? 'font-bold text-slate-800' : 'text-slate-400' }}">
                                                @if ($option->is_correct_answer)<div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div>@else<div class="h-1 w-1 bg-slate-400 rounded-full"></div>@endif
                                                {{ $option->option_text }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="w-10 h-6 {{ $toggleBg }} rounded-full relative cursor-pointer shadow-inner" onclick="toggleQuestionStatus({{ $question->id }}, this)">
                                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md {{ $toggleTranslate }}"></div>
                                        </div>
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">{{ $toggleLabel }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.quiz.questions.destroy', $question->id) }}" onsubmit="return confirm('Delete this question?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 text-center py-8">No B2B questions yet. Add one using the form above.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: B2C QUESTIONS -->
            <div id="b2c-questions" class="tab-pane hidden">
                <div class="bg-[var(--ui-surface)] rounded-2xl p-6 lg:p-8 shadow-[var(--ui-shadow-soft)] border-2 border-dashed border-slate-200 mt-6 relative shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
                    <h3 class="text-base font-extrabold text-[var(--ui-text)] flex items-center gap-2 mb-6">
                        <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Create New MCQ (B2C)
                    </h3>
                    <form method="POST" action="{{ route('admin.quiz.questions.store') }}">
                        @csrf
                        <input type="hidden" name="user_type" value="b2c">
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Question Prompt</label>
                            <textarea name="question_text" rows="3" placeholder="Enter the technical question here..." required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl px-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-400 font-medium"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_a" placeholder="Option A" required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_a" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b">B2B Flow</option><option value="b2c" selected>B2C Flow</option></select></div>
                            </div>
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_b" placeholder="Option B" required class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_b" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b">B2B Flow</option><option value="b2c" selected>B2C Flow</option></select></div>
                            </div>
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_c" placeholder="Option C" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_c" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b">B2B Flow</option><option value="b2c" selected>B2C Flow</option></select></div>
                            </div>
                            <div>
                                <div class="relative flex items-center mb-2"><div class="absolute left-4 top-1/2 -translate-y-1/2"><div class="h-4 w-4 rounded-full border-2 border-slate-300"></div></div><input type="text" name="option_d" placeholder="Option D" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold"></div>
                                <div class="flex items-center gap-2"><span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span><select name="target_flow_d" class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer"><option value="common">Continue Common</option><option value="b2b">B2B Flow</option><option value="b2c" selected>B2C Flow</option></select></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Correct Answer</label>
                            <select name="correct_option" required class="bg-[var(--ui-input-bg)] border border-[var(--ui-border)] text-sm rounded-xl px-4 py-2.5 outline-none"><option value="A">Option A</option><option value="B">Option B</option><option value="C">Option C</option><option value="D">Option D</option></select>
                        </div>
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-primary-900 border border-primary-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-800 transition shadow-md">Save Question</button>
                        </div>
                    </form>
                </div>

                <!-- Existing B2C Questions List -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest">Existing B2C Questions ({{ $questionGroups['b2c_count'] }})</h3>
                    </div>
                    <div class="space-y-4">
                        @forelse ($questionGroups['b2c'] as $question)
                            @php
                                $questionPrefix  = 'B2C-' . str_pad($question->id, 3, '0', STR_PAD_LEFT);
                                $activeClass     = $question->is_active ? '' : 'opacity-60';
                                $toggleBg        = $question->is_active ? 'bg-primary-900' : 'bg-slate-200';
                                $toggleLabel     = $question->is_active ? 'Active' : 'Disabled';
                                $toggleTranslate = $question->is_active ? 'transform translate-x-4' : '';
                            @endphp
                            <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative {{ $activeClass }}">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: {{ $questionPrefix }}</span>
                                        @if ($question->phase_title)<span class="text-xs font-semibold text-slate-500">{{ $question->phase_title }}</span>@endif
                                    </div>
                                    <h4 class="text-base font-bold text-slate-800 mb-4">{{ $question->question_text }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                        @foreach ($question->answerOptions as $option)
                                            <div class="flex items-center gap-2 {{ $option->is_correct_answer ? 'font-bold text-slate-800' : 'text-slate-400' }}">
                                                @if ($option->is_correct_answer)<div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div>@else<div class="h-1 w-1 bg-slate-400 rounded-full"></div>@endif
                                                {{ $option->option_text }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="w-10 h-6 {{ $toggleBg }} rounded-full relative cursor-pointer shadow-inner" onclick="toggleQuestionStatus({{ $question->id }}, this)">
                                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md {{ $toggleTranslate }}"></div>
                                        </div>
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">{{ $toggleLabel }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.quiz.questions.destroy', $question->id) }}" onsubmit="return confirm('Delete this question?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 text-center py-8">No B2C questions yet. Add one using the form above.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Floating FAB -->
            <button class="fixed bottom-12 right-12 h-14 w-14 rounded-2xl bg-primary-900 border border-primary-800 text-white flex items-center justify-center shadow-lg hover:bg-primary-800 transition shadow-[0_0_30px_rgba(15,23,42,0.3)] hidden md:flex">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            </button>
            </div>

@endsection

@push('scripts')
<script>
    (function () {
        function initQuizCreatePage(root) {
            const scope = root || document;
            const page = scope.querySelector('[data-quiz-create-page]');
            if (!page || page.dataset.quizTabsReady === 'true') {
                return;
            }

            page.dataset.quizTabsReady = 'true';

            const tabs = page.querySelectorAll('.quiz-tab');
            const panes = page.querySelectorAll('.tab-pane');

            function activateTab(tab) {
                tabs.forEach((item) => {
                    item.classList.remove('border-primary-600', 'text-[var(--ui-text)]', 'font-bold');
                    item.classList.add('border-transparent', 'text-[var(--ui-text-muted)]', 'font-semibold');
                });

                panes.forEach((pane) => {
                    pane.classList.add('hidden');
                    pane.classList.remove('block');
                });

                tab.classList.remove('border-transparent', 'text-[var(--ui-text-muted)]', 'font-semibold');
                tab.classList.add('border-primary-600', 'text-[var(--ui-text)]', 'font-bold');

                const target = page.querySelector('#' + tab.dataset.target);
                if (target) {
                    target.classList.remove('hidden');
                    target.classList.add('block');
                }
            }

            tabs.forEach((tab) => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();
                    activateTab(tab);
                });
            });

            const activeTab = Array.from(tabs).find((tab) => tab.classList.contains('border-primary-600')) || tabs[0];
            if (activeTab) {
                activateTab(activeTab);
            }
        }

        window.initQuizCreatePage = initQuizCreatePage;
        initQuizCreatePage(document);

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                initQuizCreatePage(document);
            }, { once: true });
        }
    })();

    // Toggle a question active/disabled status without a full page reload.
    window.toggleQuestionStatus = function (questionId, toggleEl) {
        var toggleUrl = '/adminPanel/quiz/questions/' + questionId + '/toggle';
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var wrapper   = toggleEl.parentElement;
        var labelEl   = wrapper.querySelector('span');
        var dotEl     = toggleEl.querySelector('div');

        // Send the toggle request to the server.
        fetch(toggleUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (!data.success) { return; }

            // Update toggle appearance and label.
            if (data.is_active) {
                toggleEl.classList.remove('bg-slate-200');
                toggleEl.classList.add('bg-primary-900');
                dotEl.classList.add('transform', 'translate-x-4');
                labelEl.textContent = 'Active';
            } else {
                toggleEl.classList.remove('bg-primary-900');
                toggleEl.classList.add('bg-slate-200');
                dotEl.classList.remove('transform', 'translate-x-4');
                labelEl.textContent = 'Disabled';
            }
        })
        .catch(function () {
            window.AdminToast && window.AdminToast.show('Unable to update question status.', 'error');
        });
    };
</script>
@endpush


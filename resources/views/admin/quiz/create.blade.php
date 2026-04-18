@extends('admin.layout')

@section('title', 'Add New Questions - Quiz Management')

@section('admin_content')

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
                    <h3 class="text-3xl font-black text-white tracking-tight">1,284</h3>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mt-6 border-b border-[var(--ui-border)]">
                <nav class="-mb-px flex space-x-8">
                    <button class="quiz-tab border-primary-600 text-[var(--ui-text)] whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm" data-target="common-questions">Common Questions</button>
                    <button class="quiz-tab border-transparent text-[var(--ui-text-muted)] hover:text-[var(--ui-text)] hover:border-slate-300 whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition" data-target="b2b-questions">B2B Questions</button>
                    <button class="quiz-tab border-transparent text-[var(--ui-text-muted)] hover:text-[var(--ui-text)] hover:border-slate-300 whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition" data-target="b2c-questions">B2C Questions</button>
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

                    <form>
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Question Prompt</label>
                            <textarea rows="3" placeholder="Enter the technical question here..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl px-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-400 font-medium"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Option A -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option A" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-medium font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option selected>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option B -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option B" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option selected>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option C -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option C" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option selected>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option D (Selected) -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-4 border-primary-800 bg-white"></div>
                                    </div>
                                    <input type="text" value="Option D" class="w-full bg-[var(--ui-input-bg)] border-2 border-primary-800 shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-800 focus:ring-2 focus:ring-primary-800 transition outline-none text-slate-800 font-bold bg-primary-50">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option selected>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="button" class="bg-primary-900 border border-primary-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-800 transition shadow-md">Save Question</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Questions List -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest">Existing Questions (24)</h3>
                        <div class="flex gap-2 text-slate-400">
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg></button>
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m0 0v8m0-8h8"></path></svg></button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Q1 -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-primary-900 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: Q-001</span>
                                    <span class="text-xs font-semibold text-slate-500">Primary Logic Splitter</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">Please identify the primary sector for which you are seeking certification.</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2"><div class="h-1 w-1 bg-slate-400 rounded-full"></div> Enterprise Bio-Manufacturing</div>
                                        <span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100 flex items-center gap-1"><svg class="h-2 w-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> B2B Flow</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2"><div class="h-1 w-1 bg-slate-400 rounded-full"></div> Individual Research Practice</div>
                                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 flex items-center gap-1"><svg class="h-2 w-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> B2C Flow</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2"><div class="h-1 w-1 bg-slate-400 rounded-full"></div> Academic Institution</div>
                                        <span class="text-[9px] font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 flex items-center gap-1"><svg class="h-2 w-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> Continue Common</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                 <div class="flex flex-col items-center gap-1">
                                    <!-- Active Toggle -->
                                    <div class="w-10 h-6 bg-primary-900 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transform translate-x-4"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Active</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>

                        <!-- Q2 -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative opacity-90">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: Q-882</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">Which metabolic process occurs primarily in the mitochondrial matrix of eukaryotic cells?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Glycolysis</div>
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> Citric Acid Cycle</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Fermentation</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Translation</div>
                                </div>
                            </div>

                             <!-- Actions -->
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                 <div class="flex flex-col items-center gap-1">
                                    <!-- Active Toggle -->
                                    <div class="w-10 h-6 bg-primary-900 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transform translate-x-4"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Active</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>

                        <!-- Q3 (Disabled) -->
                         <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative opacity-60">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: Q-901</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">What is the primary function of the BLAST algorithm in genetic research?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> Sequence alignment comparison</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Protein 3D modeling</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> CRISPR-Cas9 targeting</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Gel electrophoresis simulation</div>
                                </div>
                            </div>

                             <!-- Actions -->
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                 <div class="flex flex-col items-center gap-1">
                                    <!-- Active Toggle -->
                                    <div class="w-10 h-6 bg-slate-200 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Disabled</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>
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

                    <form>
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Question Prompt</label>
                            <textarea rows="3" placeholder="Enter the technical question here..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl px-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-400 font-medium"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Option A -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option A" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-medium font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option selected>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option B -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option B" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option selected>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option C -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option C" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option selected>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option D (Selected) -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-4 border-primary-800 bg-white"></div>
                                    </div>
                                    <input type="text" value="Option D" class="w-full bg-[var(--ui-input-bg)] border-2 border-primary-800 shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-800 focus:ring-2 focus:ring-primary-800 transition outline-none text-slate-800 font-bold bg-primary-50">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option selected>B2B Flow</option>
                                        <option>B2C Flow</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="button" class="bg-primary-900 border border-primary-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-800 transition shadow-md">Save Question</button>
                        </div>
                    </form>
                </div>

                <!-- Existing B2B Questions List -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest">Existing B2B Questions (18)</h3>
                        <div class="flex gap-2 text-slate-400">
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg></button>
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m0 0v8m0-8h8"></path></svg></button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- B2B Q1 -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: B2B-001</span>
                                    <span class="text-xs font-semibold text-slate-500">Enterprise Compliance</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">What is the required minimum batch documentation standard for GMP-certified bio-manufacturing facilities?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> ISO 22716 + Annex 15</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> FDA 21 CFR Part 11 only</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> No documentation required</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Internal SOPs only</div>
                                </div>
                            </div>
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-10 h-6 bg-primary-900 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transform translate-x-4"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Active</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>

                        <!-- B2B Q2 -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative opacity-90">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: B2B-014</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">Which scalability model is most appropriate for enterprise-level bioprocessing operations exceeding 10,000L batch volumes?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Linear scale-up</div>
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> Modular parallel processing</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Single-use bioreactor cascade</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Batch-fed sequential</div>
                                </div>
                            </div>
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-10 h-6 bg-primary-900 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transform translate-x-4"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Active</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>

                        <!-- B2B Q3 (Disabled) -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative opacity-60">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: B2B-009</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">What is the recommended cold-chain validation protocol for biologics distribution across multi-site enterprise networks?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> PDA Tech Report No. 39 + ISTA 7D</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> WHO TRS 961 Annex 9 only</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Internal temperature logging</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Carrier SLA compliance</div>
                                </div>
                            </div>
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-10 h-6 bg-slate-200 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Disabled</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>
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

                    <form>
                        <div class="mb-5">
                            <label class="block text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest mb-2">Question Prompt</label>
                            <textarea rows="3" placeholder="Enter the technical question here..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl px-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-400 font-medium"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Option A -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option A" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-medium font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option selected>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option B -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option B" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option selected>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option C -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-300"></div>
                                    </div>
                                    <input type="text" placeholder="Option C" class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-slate-500 font-semibold">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option selected>B2C Flow</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option D (Selected) -->
                            <div>
                                <div class="relative flex items-center mb-2">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                        <div class="h-4 w-4 rounded-full border-4 border-primary-800 bg-white"></div>
                                    </div>
                                    <input type="text" value="Option D" class="w-full bg-[var(--ui-input-bg)] border-2 border-primary-800 shadow-sm text-sm rounded-xl pl-10 pr-4 py-3 focus:border-primary-800 focus:ring-2 focus:ring-primary-800 transition outline-none text-slate-800 font-bold bg-primary-50">
                                </div>
                                 <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">Target Flow:</span>
                                    <select class="w-full bg-[var(--ui-input-bg)] border-none text-xs rounded-lg py-1.5 focus:ring-0 outline-none text-slate-600 font-medium cursor-pointer">
                                        <option>Continue Common</option>
                                        <option>B2B Flow</option>
                                        <option selected>B2C Flow</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="button" class="bg-primary-900 border border-primary-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-800 transition shadow-md">Save Question</button>
                        </div>
                    </form>
                </div>

                <!-- Existing B2C Questions List -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[11px] font-bold text-[var(--ui-text-muted)] uppercase tracking-widest">Existing B2C Questions (12)</h3>
                        <div class="flex gap-2 text-slate-400">
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg></button>
                            <button class="p-1 hover:text-slate-600 border border-slate-200 rounded shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m0 0v8m0-8h8"></path></svg></button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- B2C Q1 -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: B2C-001</span>
                                    <span class="text-xs font-semibold text-slate-500">Consumer Onboarding</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">What is your primary interest in biological engineering products for personal use?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> Health & wellness supplements</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Home lab research kits</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Educational materials</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> DIY bio experimentation</div>
                                </div>
                            </div>
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-10 h-6 bg-primary-900 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transform translate-x-4"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Active</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>

                        <!-- B2C Q2 -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative opacity-90">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: B2C-007</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">How would you rate your familiarity with probiotic and prebiotic product formulations?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> No prior knowledge</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Basic consumer awareness</div>
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> Intermediate understanding</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Expert-level knowledge</div>
                                </div>
                            </div>
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-10 h-6 bg-primary-900 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transform translate-x-4"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Active</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>

                        <!-- B2C Q3 (Disabled) -->
                        <div class="bg-[var(--ui-surface)] border border-slate-200 p-5 rounded-xl flex flex-col md:flex-row gap-4 relative opacity-60">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ID: B2C-011</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mb-4">Which delivery format do you prefer for receiving biological wellness products?</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Capsules & tablets</div>
                                    <div class="flex items-center gap-2 font-bold text-slate-800"><div class="h-4 w-4 rounded-full bg-primary-900 border-4 border-white shadow-[0_0_0_1px_rgba(15,23,42,0.2)]"></div> Liquid concentrates</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Powder sachets</div>
                                    <div class="flex items-center gap-2 text-slate-400"><div class="h-1 w-1 bg-slate-300 rounded-full"></div> Topical applications</div>
                                </div>
                            </div>
                            <div class="flex md:flex-col items-center gap-4 text-slate-400 shrink-0 self-start md:self-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-10 h-6 bg-slate-200 rounded-full relative cursor-pointer shadow-inner">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md"></div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Disabled</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="hover:text-primary-600 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button class="hover:text-rose-500 transition"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating FAB (based on screenshot) -->
            <button class="fixed bottom-12 right-12 h-14 w-14 rounded-2xl bg-primary-900 border border-primary-800 text-white flex items-center justify-center shadow-lg hover:bg-primary-800 transition shadow-[0_0_30px_rgba(15,23,42,0.3)] hidden md:flex">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            </button>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.quiz-tab');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Reset all tabs
                tabs.forEach(t => {
                    t.classList.remove('border-primary-600', 'text-[var(--ui-text)]', 'font-bold');
                    t.classList.add('border-transparent', 'text-[var(--ui-text-muted)]', 'font-semibold');
                });
                
                // Activate clicked tab
                tab.classList.remove('border-transparent', 'text-[var(--ui-text-muted)]', 'font-semibold');
                tab.classList.add('border-primary-600', 'text-[var(--ui-text)]', 'font-bold');
                
                // Hide all panes
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.add('hidden');
                    pane.classList.remove('block');
                });
                
                // Show target pane
                const target = document.getElementById(tab.dataset.target);
                if (target) {
                    target.classList.remove('hidden');
                    target.classList.add('block');
                }
            });
        });
    });
</script>
@endpush

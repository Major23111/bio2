@extends('admin.layout')

@section('title', 'Quiz Management - Biogenix')

@section('admin_content')

            <!-- Header -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-[var(--ui-text)] tracking-tight">Quiz Management</h2>
                    <p class="text-sm text-[var(--ui-text-muted)] mt-1">Manage leads and quiz configuration</p>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex items-center gap-3">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Search leads..." class="w-full bg-[var(--ui-input-bg)] border border-[var(--ui-border)] shadow-sm text-sm rounded-xl pl-9 pr-4 py-2 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-[var(--ui-text)] placeholder:text-[var(--ui-text-muted)]">
                    </div>

                    <a href="{{ route('admin.quiz.create') }}" class="ajax-link bg-primary-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md hover:bg-primary-700 transition whitespace-nowrap flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Add New Questions
                    </a>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mt-6">
                <!-- Total Leads -->
                <div class="bg-[var(--ui-surface)] rounded-2xl p-6 shadow-[var(--ui-shadow-soft)] border-l-4 border-l-primary-600 border-y border-y-[var(--ui-card-border)] border-r border-r-[var(--ui-card-border)] flex flex-col justify-center min-h-[140px]">
                    <p class="text-[12px] font-bold text-slate-500 uppercase tracking-wider mb-2">Total Leads</p>
                    <h3 class="text-4xl font-black text-[var(--ui-text)] tracking-tight mb-3">{{ number_format($quizeStats['total_leads']) }}</h3>

                </div>

                <!-- Segment Distribution (Dark Card) -->
                <div class="bg-primary-900 rounded-2xl p-6 shadow-md border border-primary-800 flex flex-col relative overflow-hidden min-h-[140px]">
                    <!-- Background decor -->
                    <svg class="absolute right-0 bottom-0 h-32 w-32 text-primary-800/50 -mr-6 -mb-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    
                    <p class="text-[12px] font-bold text-primary-200 uppercase tracking-wider mb-auto relative z-10">Segment Distribution</p>
                    
                    <div class="flex items-end justify-between mt-8 relative z-10">
                        <div class="text-center">
                            <span class="block text-white font-bold text-sm mb-1">B2B ({{ $quizeStats['b2b_percent'] }}%)</span>
                            <div class="w-32 sm:w-40 bg-primary-800 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: {{ $quizeStats['b2b_percent'] }}%"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="block text-white font-bold text-sm mb-1">B2C ({{ $quizeStats['b2c_percent'] }}%)</span>
                            <div class="w-20 sm:w-24 bg-primary-800 rounded-full h-2">
                                <div class="bg-blue-300 h-2 rounded-full" style="width: {{ $quizeStats['b2c_percent'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leads Activity Feed Table -->
            <div class="bg-[var(--ui-surface)] rounded-2xl shadow-[var(--ui-shadow-soft)] border border-[var(--ui-card-border)] overflow-hidden mt-6 pb-2">
                <div class="px-5 lg:px-7 py-5 lg:py-6 border-b border-[var(--ui-border)] flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-[var(--ui-text)]">Lead Activity Feed</h3>
                        <p class="text-xs font-semibold text-[var(--ui-text-muted)] mt-0.5">Real-time performance of quiz participants</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="window.AdminToast.show('Preparing CSV export...', 'success')" class="bg-white border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-50 transition shadow-sm">Export CSV</button>
                        <button class="bg-primary-900 border border-primary-800 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-primary-800 transition shadow-sm">Filter View</button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap min-w-[800px]">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Name</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Email</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Segment</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Score</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--ui-border)] text-sm font-semibold text-[var(--ui-text)]">
                            @forelse ($leadFeed as $lead)
                            @php
                                // Build two-letter avatar initials from the lead name.
                                $firstName    = $lead->participant_first_name ?? '';
                                $lastName     = $lead->participant_last_name ?? '';
                                $initials     = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                $fullName     = trim($firstName . ' ' . $lastName);
                                $segmentType  = strtoupper($lead->user_type ?? 'N/A');
                                $scoreDisplay = $lead->total_questions > 0
                                    ? $lead->total_correct_answers . '/' . $lead->total_questions
                                    : '—';

                                // Determine status label from score_percentage.
                                if ($lead->score_percentage >= 70) {
                                    $statusLabel = 'Converted';
                                    $statusDot   = 'bg-emerald-500';
                                    $statusText  = 'text-emerald-700';
                                } elseif ($lead->score_percentage >= 40) {
                                    $statusLabel = 'Contacted';
                                    $statusDot   = 'bg-blue-500';
                                    $statusText  = 'text-blue-700';
                                } else {
                                    $statusLabel = 'Pending';
                                    $statusDot   = 'bg-amber-500';
                                    $statusText  = 'text-amber-600';
                                }

                                // Segment badge colors.
                                $segmentBadge = $segmentType === 'B2B'
                                    ? 'bg-blue-50 text-blue-700'
                                    : 'bg-slate-100 text-slate-600';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">{{ $initials }}</div>
                                        <span class="text-slate-800 font-bold text-sm">{{ $fullName }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium text-sm">{{ $lead->participant_email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 {{ $segmentBadge }} rounded-md text-[10px] font-black tracking-wider uppercase">{{ $segmentType }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-800 font-black text-sm">{{ $scoreDisplay }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5">
                                        <div class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></div>
                                        <span class="{{ $statusText }} font-bold text-xs">{{ $statusLabel }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right relative">
                                    <button onclick="toggleRowActions(event, 'actions-{{ $lead->id }}')" class="text-slate-400 hover:text-slate-600 transition p-1 rounded-lg hover:bg-slate-100">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                    </button>
                                    <div id="actions-{{ $lead->id }}" class="hidden absolute right-6 top-10 mt-2 w-36 bg-white border border-slate-200 rounded-xl shadow-[var(--ui-shadow-card)] z-[50]">
                                        <div class="p-1 flex flex-col gap-0.5">
                                            <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition" onclick="window.AdminToast.show('Viewing lead details...', 'info')">View Details</button>
                                            <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition" onclick="window.AdminToast.show('Contacting lead...', 'info')">Contact Lead</button>
                                            <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-rose-600 transition" onclick="confirm('Delete this lead record?')">Delete</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400 font-medium">No quiz responses recorded yet.</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Showing {{ $leadFeed->firstItem() }}-{{ $leadFeed->lastItem() }} of {{ number_format($leadFeed->total()) }} Entries</span>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-400">
                        @if ($leadFeed->onFirstPage())
                            <span class="opacity-40">Previous</span>
                        @else
                            <a href="{{ $leadFeed->previousPageUrl() }}" class="hover:text-slate-700 transition">Previous</a>
                        @endif
                        <div class="flex gap-2">
                            @for ($page = 1; $page <= min($leadFeed->lastPage(), 3); $page++)
                                <a href="{{ $leadFeed->url($page) }}" class="{{ $page === $leadFeed->currentPage() ? 'text-primary-700' : 'hover:text-slate-700 transition' }}">{{ $page }}</a>
                            @endfor
                        </div>
                        @if ($leadFeed->hasMorePages())
                            <a href="{{ $leadFeed->nextPageUrl() }}" class="text-slate-800 hover:text-primary-600 transition">Next</a>
                        @else
                            <span class="opacity-40">Next</span>
                        @endif
                    </div>
                </div>
            </div>

@endsection

@push('scripts')
<script>
    window.toggleRowActions = function(e, id) {
        e.stopPropagation();
        const el = document.getElementById(id);
        const wasHidden = el.classList.contains('hidden');
        document.querySelectorAll('[id^="actions-"]').forEach(d => d.classList.add('hidden'));
        if (wasHidden) el.classList.remove('hidden');
    };

    document.addEventListener('click', () => {
        document.querySelectorAll('[id^="actions-"]').forEach(d => d.classList.add('hidden'));
    });
</script>
@endpush

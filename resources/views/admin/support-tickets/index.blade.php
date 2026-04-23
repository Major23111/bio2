@extends('admin.layout')

@section('title', 'Support Tickets - Biogenix Admin')

@section('admin_content')



    {{-- Page Header --}}
    <div class="mb-5 flex flex-col md:flex-row md:items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Support Ticket System</h1>
            <p class="text-sm text-slate-500 mt-1 max-w-lg">Centralized hub for managing, resolving, and tracking customer inquiries across Biogenix services.</p>
        </div>
    </div>

    {{-- ─── Active Ticket Inbox ─── --}}
    <div class="bg-[var(--ui-surface)] rounded-2xl shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] overflow-hidden mb-5">

        {{-- Toolbar --}}
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-sm font-extrabold text-slate-900">Active Ticket Inbox</h2>
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input id="ticket-search" type="text" placeholder="Search by Ticket ID, Customer, or Subject..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto pb-24">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Ticket ID</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Customer Name</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Subject</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Priority</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="ticket-table-body">

                    @php
                    $tickets = [
                        ['id' => 'TK-8821', 'customer' => 'Alice Henderson', 'subject' => 'Subscription Billing Issue',   'priority' => 'High',   'priority_color' => 'bg-rose-50 text-rose-700 border border-rose-200/60',    'status' => 'Open',        'status_color' => 'bg-amber-50 text-amber-700 border border-amber-200/60'],
                        ['id' => 'TK-8819', 'customer' => 'Marcus Thorne',   'subject' => 'Lab Report Access',           'priority' => 'High',   'priority_color' => 'bg-rose-50 text-rose-700 border border-rose-200/60',    'status' => 'In progress', 'status_color' => 'bg-blue-50 text-blue-700 border border-blue-200/60'],
                        ['id' => 'TK-8795', 'customer' => 'Elena Rossi',     'subject' => 'Technical Bug: Login Loop',   'priority' => 'Critical','priority_color' => 'bg-red-50 text-red-700 border border-red-200/60',        'status' => 'Close',       'status_color' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'],
                        ['id' => 'TK-8790', 'customer' => 'James Wilson',    'subject' => 'Feature Request: API Access', 'priority' => 'Low',      'priority_color' => 'bg-slate-50 text-slate-600 border border-slate-200/60', 'status' => 'Open',        'status_color' => 'bg-amber-50 text-amber-700 border border-amber-200/60'],
                        ['id' => 'TK-8787', 'customer' => 'Priya Anand',     'subject' => 'Order Not Received',          'priority' => 'High',   'priority_color' => 'bg-rose-50 text-rose-700 border border-rose-200/60',    'status' => 'Open',        'status_color' => 'bg-amber-50 text-amber-700 border border-amber-200/60'],
                        ['id' => 'TK-8771', 'customer' => 'Karl Messner',    'subject' => 'Refund Processing Delay',     'priority' => 'Low',    'priority_color' => 'bg-slate-50 text-slate-600 border border-slate-200/60',  'status' => 'Close',       'status_color' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'],
                    ];
                    @endphp

                    @if (count($tickets))
                        @foreach($tickets as $t)
                    <tr class="hover:bg-slate-50/60 transition-colors cursor-pointer ticket-row" onclick="selectTicket('{{ $t['id'] }}')" data-ticket="{{ $t['id'] }}" data-name="{{ strtolower($t['customer']) }}" data-subject="{{ strtolower($t['subject']) }}">
                        <td class="px-6 py-3.5">
                            <span class="text-[13px] font-extrabold text-primary-800">#{{ $t['id'] }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="text-[13px] font-semibold text-slate-800">{{ $t['customer'] }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="text-[13px] text-slate-600 font-medium">{{ $t['subject'] }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="relative">
                                <button onclick="toggleDropdown(event, 'priority-dropdown-{{ $t['id'] }}')" class="flex items-center gap-1 hover:opacity-80 transition cursor-pointer focus:outline-none">
                                    @if($t['priority'])
                                        <span class="inline-flex items-center px-2.5 py-1 {{ $t['priority_color'] }} text-[11px] font-bold rounded-full">{{ $t['priority'] }}</span>
                                    @else
                                        <span class="text-[11px] font-bold text-slate-400 border border-dashed border-slate-300 rounded-full px-2.5 py-1 hover:border-slate-400 hover:text-slate-600 transition">Add Priority</span>
                                    @endif
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div id="priority-dropdown-{{ $t['id'] }}" class="hidden absolute top-full left-0 mt-1 w-32 bg-white border border-slate-200 rounded-xl shadow-[20px_20px_50px_rgba(0,0,0,0.1)] z-[100]">
                                    <div class="p-1 flex flex-col gap-0.5">
                                        <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition cursor-pointer" onclick="updateDropdownVal(event, '{{ $t['id'] }}', 'priority', 'High')">High</button>
                                        <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition cursor-pointer" onclick="updateDropdownVal(event, '{{ $t['id'] }}', 'priority', 'Low')">Low</button>
                                        <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition cursor-pointer" onclick="updateDropdownVal(event, '{{ $t['id'] }}', 'priority', 'Critical')">Critical</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="relative">
                                <button onclick="toggleDropdown(event, 'status-dropdown-{{ $t['id'] }}')" class="flex items-center gap-1 hover:opacity-80 transition cursor-pointer focus:outline-none">
                                    <span class="inline-flex items-center px-2.5 py-1 {{ $t['status_color'] }} text-[11px] font-bold rounded-full">{{ $t['status'] }}</span>
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div id="status-dropdown-{{ $t['id'] }}" class="hidden absolute top-full left-0 mt-1 w-32 bg-white border border-slate-200 rounded-xl shadow-[20px_20px_50px_rgba(0,0,0,0.1)] z-[100]">
                                    <div class="p-1 flex flex-col gap-0.5">
                                        <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition cursor-pointer" onclick="updateDropdownVal(event, '{{ $t['id'] }}', 'status', 'Open')">Open</button>
                                        <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition cursor-pointer" onclick="updateDropdownVal(event, '{{ $t['id'] }}', 'status', 'In progress')">In progress</button>
                                        <button class="w-full text-left px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-700 transition cursor-pointer" onclick="updateDropdownVal(event, '{{ $t['id'] }}', 'status', 'Close')">Close</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" /></svg>
                                    </div>
                                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">No Tickets Found</h3>
                                    <p class="text-xs text-slate-400 mt-1">There are no support tickets matching your search or filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-[12px] font-medium text-slate-500">Showing <span class="font-bold text-slate-900">1</span> to <span class="font-bold text-slate-900">25</span> of <span class="font-bold text-slate-900">48</span> results</p>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-[12px] font-bold text-slate-400 cursor-not-allowed">Previous</button>
                <button class="px-3 py-1.5 rounded-lg border border-primary-600 bg-primary-600 text-white text-[12px] font-bold shadow-sm shadow-primary-600/20 cursor-pointer">1</button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-[12px] font-bold transition cursor-pointer">2</button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-[12px] font-bold transition cursor-pointer">3</button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-[12px] font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">Next</button>
            </div>
        </div>
    </div>

    {{-- ─── Ticket Detail + Right Panel ─── --}}
    <div class="grid grid-cols-1 gap-5">

        {{-- Left: Ticket Detail --}}
        <div id="ticket-detail-panel" class="bg-[var(--ui-surface)] rounded-2xl shadow-[var(--ui-shadow-soft)] border border-[var(--ui-border)] overflow-hidden flex flex-col">

            {{-- Ticket Header --}}
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-[15px] font-extrabold text-slate-900" id="detail-title">#TK-8821: Subscription Billing Issue</h2>

                        </div>
                        <p class="text-[12px] text-slate-500" id="detail-meta">Opened by Alice Henderson &bull; 3 hours ago</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200/60 text-[11px] font-bold rounded-full" id="detail-status-badge">Status: Open</span>
                        <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200/60 text-[11px] font-bold rounded-full" id="detail-priority-badge">Priority: High</span>
                    </div>
                </div>

                {{-- Meta Row --}}
                <div class="mt-4 grid grid-cols-2 gap-4 bg-slate-50 rounded-xl px-4 py-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Category</p>
                        <span class="text-[12px] font-semibold text-slate-800" id="detail-category">Billing</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Last Update</p>
                        <span class="text-[12px] font-semibold text-slate-800">14 mins ago</span>
                    </div>
                </div>
            </div>

            {{-- Conversation Thread --}}
            <div class="px-6 py-4 flex-1 overflow-y-auto max-h-[420px] custom-scrollbar" id="conversation-thread">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Conversation Thread</span>
                </div>
                <div class="space-y-4">

                    {{-- Customer message --}}
                    <div class="flex items-start gap-3">
                        <div class="h-7 w-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">AH</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="text-[12px] font-bold text-slate-900">Alice Henderson</span>
                                <span class="text-[10px] text-slate-400">09:12 AM</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl rounded-tl-sm px-4 py-3 text-[12px] text-slate-700 leading-relaxed">
                                "Hi, I noticed an extra charge of $49.99 on my account this morning that doesn't seem to match my current subscription tier. Can you please check why this happened?"
                            </div>
                        </div>
                    </div>



                    {{-- Customer followup --}}
                    <div class="flex items-start gap-3">
                        <div class="h-7 w-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">AH</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="text-[12px] font-bold text-slate-900">Alice Henderson</span>
                                <span class="text-[10px] text-slate-400">11:45 AM</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl rounded-tl-sm px-4 py-3 text-[12px] text-slate-700 leading-relaxed">
                                "Yes, I did receive two emails. One says 'Order Confirmed' and the other says 'Subscription Updated'."
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Ticket History removed per business requirement --}}

            {{-- Reply Box --}}
            <div class="px-6 py-4 border-t border-slate-100">
                <textarea id="reply-input" rows="3" placeholder="Type your response here..." class="w-full bg-slate-50 border border-slate-200 text-[13px] text-slate-800 rounded-xl px-4 py-3 outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition resize-none placeholder:text-slate-400 font-medium"></textarea>
                <div class="mt-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer" title="Attach file">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        </button>
                        <button class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer" title="Emoji">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                    </div>
                    <button id="btn-send-message" onclick="sendMessage()" class="bg-primary-600 hover:bg-primary-700 transition text-white px-5 py-2 rounded-lg text-[13px] font-bold shadow-sm shadow-primary-600/20 cursor-pointer">Send Message</button>
                </div>
            </div>
        </div>

        {{-- Right: Support Categories + Configuration --}}
        {{-- Support Categories and Configuration sections hidden per business requirement --}}
    </div>


<style>
    @keyframes ticket-fade { from { opacity: 0; transform: scale(0.96) translateY(8px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .animate-ticket-fade { animation: ticket-fade 0.2s ease-out forwards; }
    .ticket-row.active-ticket { background: #f0f3f8; }
    .ticket-row.active-ticket td:first-child { border-left: 3px solid var(--color-primary-600); }

    /* Custom Scrollbar for Chat */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<script>
(function() {

    // ─── Ticket inbox search ───
    document.getElementById('ticket-search')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.ticket-row').forEach(row => {
            const match = row.dataset.name.includes(q) || row.dataset.subject.includes(q) || row.dataset.ticket.toLowerCase().includes(q);
            row.style.display = match ? '' : 'none';
        });
    });

    // ─── Select ticket to view detail ───
    window.selectTicket = function(id) {
        document.querySelectorAll('.ticket-row').forEach(r => r.classList.remove('active-ticket'));
        const row = document.querySelector(`[data-ticket="${id}"]`);
        if (row) row.classList.add('active-ticket');
        // In a real app you'd fetch the ticket details via AJAX here
    };

    // ─── Mark first row as active on load ───
    const firstRow = document.querySelector('.ticket-row');
    if (firstRow) firstRow.classList.add('active-ticket');

    // ─── Send Message ───
    window.sendMessage = function() {
        const input = document.getElementById('reply-input');
        const msg = input?.value?.trim();
        if (!msg) return;
        const thread = document.getElementById('conversation-thread').querySelector('.space-y-4');
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        const bubble = document.createElement('div');
        bubble.className = 'flex items-start gap-3 flex-row-reverse';
        bubble.innerHTML = `
            <div class="h-7 w-7 rounded-full bg-primary-600 text-white flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">SA</div>
            <div class="flex-1 min-w-0">
                <div class="flex items-baseline gap-2 mb-1 justify-end">
                    <span class="text-[10px] text-slate-400">${timeStr}</span>
                    <span class="text-[12px] font-bold text-slate-900">Super Admin</span>
                </div>
                <div class="bg-primary-600 rounded-xl rounded-tr-sm px-4 py-3 text-[12px] text-white leading-relaxed">${msg.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</div>
            </div>`;
        thread.appendChild(bubble);
        input.value = '';
        bubble.scrollIntoView({ behavior: 'smooth', block: 'end' });
    };

    // ─── Cell Dropdown handlers ───
    window.toggleDropdown = function(e, id) {
        if (e) e.stopPropagation();
        document.querySelectorAll('[id^="priority-dropdown-"], [id^="status-dropdown-"]').forEach(el => {
            if (el.id !== id) el.classList.add('hidden');
        });
        const target = document.getElementById(id);
        if (target) target.classList.toggle('hidden');
    };

    document.addEventListener('click', function() {
        document.querySelectorAll('[id^="priority-dropdown-"], [id^="status-dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    });

    window.updateDropdownVal = function(e, id, type, val) {
        if (e) e.stopPropagation();
        if(window.AdminToast) window.AdminToast.show(`Ticket ${id} ${type} updated to ${val}`, "success");
        document.getElementById(`${type}-dropdown-${id}`).classList.add('hidden');
        
        // Update DOM
        const btn = document.querySelector(`button[onclick="toggleDropdown(event, '${type}-dropdown-${id}')"]`);
        if (btn) {
            const span = btn.querySelector('span');
            if (span) {
                span.textContent = val;
                span.className = `inline-flex items-center px-2.5 py-1 text-[11px] font-bold rounded-full`;
                if (type === 'priority') {
                    if (val === 'Critical') span.classList.add('bg-rose-50', 'text-rose-700', 'border', 'border-rose-200/60');
                    else if (val === 'High') span.classList.add('bg-amber-50', 'text-amber-700', 'border', 'border-amber-200/60');
                    else if (val === 'Low') span.classList.add('bg-slate-50', 'text-slate-600', 'border', 'border-slate-200/60');
                } else if (type === 'status') {
                    if (val === 'Open') span.classList.add('bg-amber-50', 'text-amber-700', 'border', 'border-amber-200/60');
                    else if (val === 'In progress') span.classList.add('bg-blue-50', 'text-blue-700', 'border', 'border-blue-200/60');
                    else if (val === 'Close') span.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200/60');
                }
            }
        }
    };

    // ─── Add Category — themed modal ───
    window.addCategory = function() {
        document.getElementById('add-category-modal').classList.remove('hidden');
        document.getElementById('add-category-modal').classList.add('flex');
        document.getElementById('new-category-input').focus();
    };

    window.closeNewTicketModal = function() {
        // Stub: no new-ticket modal currently rendered — keeping as a no-op to avoid JS errors
        const modal = document.getElementById('new-ticket-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        // Also close the add-category modal if open
        const catModal = document.getElementById('add-category-modal');
        if (catModal) {
            catModal.classList.add('hidden');
            catModal.classList.remove('flex');
        }
    };

    // ─── Enter to send message ───
    document.getElementById('reply-input')?.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { 
            e.preventDefault(); 
            sendMessage(); 
        }
    });
})();
</script>

{{-- ─── Add Category Modal ─── --}}
<div id="add-category-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/50 backdrop-blur-[2px]">
    <div class="relative w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-2xl p-6 mx-4">
        <div class="flex items-start justify-between mb-5">
            <h3 class="text-[15px] font-extrabold text-slate-900">Add Support Category</h3>
            <button onclick="closeNewTicketModal()" class="text-slate-400 hover:text-slate-600 transition cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <input id="new-category-input" type="text" placeholder="Category name..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-2.5 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none text-slate-800 font-medium placeholder:text-slate-400 mb-5">
        <div class="flex justify-end gap-3">
            <button onclick="closeNewTicketModal()" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition cursor-pointer">Cancel</button>
            <button onclick="submitNewCategory()" class="px-5 py-2 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition shadow-sm cursor-pointer">Add Category</button>
        </div>
    </div>
</div>

<script>
(function() {
    window.submitNewCategory = function() {
        const input = document.getElementById('new-category-input');
        const name = input?.value?.trim();
        if (!name) return;
        const list = document.getElementById('categories-list');
        if (list) {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between py-2 border-b border-slate-50';
            div.innerHTML = `<span class="text-[13px] font-semibold text-slate-800">${name}</span><span class="text-[11px] font-bold bg-slate-100 text-slate-600 rounded-full px-2 py-0.5">0</span>`;
            list.appendChild(div);
        }
        if (window.AdminToast) window.AdminToast.show(`Category "${name}" added`, 'success');
        input.value = '';
        closeNewTicketModal();
    };
})();
</script>

@endsection

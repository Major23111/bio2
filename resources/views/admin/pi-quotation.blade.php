@extends('admin.layout')

@section('title', 'PI Management - Biogenix Admin')

@section('admin_content')

<div class="space-y-6">



    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">PI Management</h1>
            <p class="text-sm text-slate-500 mt-1">Manage, convert, and track Proforma Invoices.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <!-- Search -->
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" placeholder="Search PI, client..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-[var(--ui-shadow-soft)] border border-slate-100 overflow-hidden flex flex-col relative pb-2 mt-6">
        
        <!-- Top Toolbar List -->
        <div class="px-5 lg:px-6 py-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Status Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 lg:pb-0 scrollbar-hide">
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-5 py-2 rounded-full text-[13px] font-bold bg-primary-600 text-white">All</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-5 py-2 rounded-full text-[13px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60 hover:bg-amber-100 transition">Requested</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-5 py-2 rounded-full text-[13px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 hover:bg-emerald-100 transition">Approved</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-5 py-2 rounded-full text-[13px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60 hover:bg-rose-100 transition">Rejected</a>
            </div>

            <!-- Add Button -->
            <a href="{{ route('admin.pi-quotation.create') }}" class="ajax-link inline-flex shrink-0 items-center justify-center px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition shadow-[0_2px_10px_-3px_rgba(26,77,46,0.2)] gap-2 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Create New PI
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">PI Number</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Customer Name</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    @forelse ($proformas as $pi)
                    @php
                        $statusLabel = ucfirst($pi['status']);
                        $statusClass = match(strtolower($pi['status'])) {
                            'approved'  => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
                            'rejected'  => 'bg-rose-50 text-rose-700 border border-rose-200/60',
                            'requested' => 'bg-amber-50 text-amber-700 border border-amber-200/60',
                            default     => 'bg-slate-100 text-slate-600 border border-slate-200/60',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group cursor-pointer"
                        onclick="window.location.href='{{ route('admin.pi-quotation.edit', $pi['id']) }}'">
                        <td class="px-5 lg:px-6 py-5">
                            <span class="text-[14px] font-bold text-slate-900">{{ $pi['piNumber'] }}</span>
                        </td>
                        <td class="px-5 lg:px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-[14px] font-bold text-slate-900">{{ $pi['targetName'] }}</span>
                                @if($pi['targetEmail'])
                                <span class="text-[12px] font-medium text-slate-400">{{ $pi['targetEmail'] }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 lg:px-6 py-5">
                            <span class="text-[14px] font-semibold text-slate-500">{{ $pi['createdDate'] }}</span>
                        </td>
                        <td class="px-5 lg:px-6 py-5">
                            <span class="text-[14px] font-extrabold text-slate-900 tracking-tight">{{ $pi['totalAmount'] }}</span>
                        </td>
                        <td class="px-5 lg:px-6 py-5 text-center">
                            <span class="inline-flex items-center px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 lg:px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.action-icon type="edit" onclick="event.stopPropagation()">
                                    <a href="{{ route('admin.pi-quotation.edit', $pi['id']) }}">
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                </x-ui.action-icon>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">No proforma invoices found.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Record count -->
        <div class="px-5 lg:px-6 py-4 border-t border-slate-100 flex items-center justify-between gap-4">
            <p class="text-[13px] text-slate-500 font-medium">
                @if($proformas->count() > 0)
                    Showing 1-25 of 124 results
                @else
                    Showing 0 results
                @endif
            </p>
            <div class="flex items-center gap-2">
                <button class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <div class="flex font-semibold text-[13px]">
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-primary-600 text-white cursor-pointer">1</button>
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-white text-slate-600 hover:bg-slate-50 transition border border-transparent hover:border-slate-200 cursor-pointer">2</button>
                </div>
                <button class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>

    </div>

</div>

@endsection

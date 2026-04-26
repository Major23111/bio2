@extends('layouts.app')

@section('title', 'Meet ' . $member['name'])

@section('content')
<div class="min-h-screen bg-slate-50 pt-6 md:pt-8 pb-12 md:pb-20">
    <div class="mx-auto w-[90%] md:w-[80%]">
        
        <!-- Back Navigation -->
        <a href="{{ route('meet-team') }}" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-semibold mb-5 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to All Team
        </a>

        @php
            $prevIdx = $idx > 0 ? $idx - 1 : count($teamData) - 1;
            $nextIdx = $idx < count($teamData) - 1 ? $idx + 1 : 0;
        @endphp

        <!-- Prev Button (Fixed to left edge) -->
        <a href="{{ route('meet-team.show', $prevIdx) }}" class="fixed top-1/2 left-2 md:left-6 -translate-y-1/2 w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full shadow-md flex items-center justify-center text-primary-600 hover:bg-primary-50 hover:text-primary-800 transition-all z-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <!-- Next Button (Fixed to right edge) -->
        <a href="{{ route('meet-team.show', $nextIdx) }}" class="fixed top-1/2 right-2 md:right-6 -translate-y-1/2 w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full shadow-md flex items-center justify-center text-primary-600 hover:bg-primary-50 hover:text-primary-800 transition-all z-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden flex flex-col md:flex-row">
                
                <!-- Left Side: Image -->
                <div class="w-full md:w-5/12 lg:w-[45%] aspect-[4/3] md:aspect-auto bg-slate-100 relative shrink-0">
                    <img src="{{ Str::startsWith($member['img'], 'http') ? $member['img'] : asset($member['img']) }}" alt="{{ $member['name'] }}" class="absolute inset-0 w-full h-full object-cover object-top">
                </div>

                <!-- Right Side: Details -->
                <div class="w-full md:w-7/12 lg:w-[55%] p-6 md:p-8 lg:p-10 flex flex-col">
                    <h1 class="font-display text-2xl md:text-[32px] leading-tight font-bold text-slate-900 mb-2">{{ $member['name'] }}</h1>
                    <p class="text-primary-600 font-bold mb-5 tracking-wide text-xs md:text-sm uppercase">{{ $member['role'] }}</p>
                    
                    <div class="w-12 h-1 bg-primary-200 mb-6 rounded-full"></div>
                    
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm md:text-[15px]">
                        {!! nl2br(e($member['copy'])) !!}
                    </div>
                </div>

        </div>

    </div>
</div>
@endsection

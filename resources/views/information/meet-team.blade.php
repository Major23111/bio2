@extends('layouts.app')

@section('title', 'Meet our Team')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header Section -->
    <section class="bg-white pt-10 md:pt-14 pb-4 md:pb-6 text-center">
        <div class="mx-auto max-w-[1300px] w-full px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl md:text-5xl font-bold tracking-tight text-slate-900 mb-3 mx-auto">Meet Your Partners in Diagnostics</h1>
            <p class="text-lg text-slate-500 max-w-3xl leading-relaxed mx-auto">
                Our experienced leadership team has a proven track record of delivering innovative solutions you and your patients can trust.
            </p>
        </div>
    </section>

    <!-- Team Grid Section -->
    <section class="bg-white pt-2 md:pt-4 pb-16 md:pb-24">
        <div class="mx-auto w-[85%] md:w-[50%]">
            <h2 class="text-lg font-medium text-primary-600 mb-6 text-center">Our Leadership Team</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
                @php
                    $teamData = config('team.members');
                @endphp

                @foreach ($teamData as $idx => $leader)
                    <!-- Card -->
                    <a href="{{ route('meet-team.show', $idx) }}" class="group block flex flex-col h-full overflow-hidden shadow-md hover:shadow-lg transition-all relative rounded-sm bg-primary-600">
                        <div class="sm:aspect-square bg-primary-50 overflow-hidden relative">
                            <!-- Image container -->
                            <img src="{{ Str::startsWith($leader['img'], 'http') ? $leader['img'] : asset($leader['img']) }}" alt="{{ $leader['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <div class="p-4 md:p-5 flex-grow flex items-end justify-between transition-colors bg-primary-600 group-hover:bg-primary-700">
                            <div class="text-left w-full pr-3">
                                <h3 class="text-white text-[16px] md:text-[18px] font-semibold tracking-wide mb-1 leading-snug">{{ $leader['name'] }}</h3>
                                <p class="text-slate-300 text-[11.5px] font-medium tracking-wide">{{ $leader['role'] }}</p>
                            </div>
                            <div class="rounded-sm w-5 h-5 flex items-center justify-center shrink-0 mb-1 transition-colors bg-white/15 hover:bg-white/30">
                                <span class="text-white text-[9px] font-bold font-serif">i</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

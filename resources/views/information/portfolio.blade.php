@extends('layouts.app')

@section('title', 'Portfolio')

@section('content')
<div class="bg-primary-50/10 min-h-[60vh] flex items-center justify-center">
    <section class="py-16 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-slate-800 mb-6 font-display">Our Portfolio</h1>
        <p class="text-lg text-slate-500 max-w-2xl mx-auto">
            Since 2016, we have backed over 110 startups across multiple sectors. This page will feature our comprehensive portfolio of successful ventures and partners.
        </p>
        <div class="mt-10 mb-8 inline-flex items-center justify-center p-6 border-2 border-dashed border-slate-300 rounded-2xl bg-white text-slate-500 font-semibold text-sm">
            Detailed case studies and portfolio gallery coming soon...
        </div>
        <div class="mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-primary-600 text-white font-bold transition hover:bg-primary-700">
                Back to Home
            </a>
        </div>
    </section>
</div>
@endsection

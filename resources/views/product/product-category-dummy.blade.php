@extends('layouts.app')

@section('title', $categoryTitle ?? 'Product Category')

@section('content')
<div class="bg-gradient-to-b from-white via-primary-50/20 to-white">
    <section class="relative overflow-hidden bg-slate-900 py-16 text-white md:py-24">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/35 to-black/45"></div>
        <div class="relative z-10 mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primary-100">Product Category</p>
            <h1 class="mx-auto mt-3 max-w-4xl font-display text-4xl font-bold tracking-tight md:text-5xl lg:text-6xl">
                {{ $categoryTitle ?? 'Product Category' }}
            </h1>
            <p class="mx-auto mt-6 max-w-3xl text-base leading-8 text-slate-100 md:text-lg">
                Explore product category details, applications, and key highlights.
            </p>
        </div>
    </section>

    <section class="py-10 md:py-14">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="mx-auto max-w-4xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Category Snapshot</h2>
                <ul class="mt-4 space-y-3">
                    <li class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-700">
                        Overview for {{ $categoryTitle ?? 'this category' }}.
                    </li>
                    <li class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-700">
                        Placeholder specification set for client review.
                    </li>
                    <li class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-700">
                        Sample use-cases and application notes can be listed here.
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>
@endsection

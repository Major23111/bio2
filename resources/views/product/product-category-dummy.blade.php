@extends('layouts.app')

@section('title', $categoryName)

@section('content')
<div class="min-h-screen bg-slate-50 py-12 md:py-16">
    <section class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-10">
            <p class="text-sm font-medium text-primary-700">Product Category</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">{{ $categoryName }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                This section can be used for category highlights, product range summary, and procurement details for
                {{ $categoryName }}.
            </p>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <h2 class="text-lg font-semibold text-slate-900">Portfolio Snapshot</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Ready-to-quote items with standardized details for lab, hospital, and institutional buyers.
                    </p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <h2 class="text-lg font-semibold text-slate-900">Commercial Information</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Ideal for MRP visibility, bulk planning discussions, and request-for-quote workflows.
                    </p>
                </article>
            </div>
        </div>
    </section>
</div>
@endsection

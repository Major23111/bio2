@extends('layouts.app')

@section('title', $solutionName)

@section('content')
<div class="min-h-screen bg-slate-50 py-12 md:py-16">
    <section class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-10">
            <p class="text-sm font-medium text-primary-700">Solutions</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">{{ $solutionName }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                This page shares the overview, coverage areas, and implementation guidance for {{ $solutionName }}.
                You can use it as the landing section for brochures, use-cases, and consultation steps.
            </p>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <h2 class="text-lg font-semibold text-slate-900">Coverage Focus</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Structured workflows, equipment alignment, and deployment support across diagnostics teams.
                    </p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <h2 class="text-lg font-semibold text-slate-900">Implementation Support</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Planning templates, onboarding checklist, and outcome tracking for institutional teams.
                    </p>
                </article>
            </div>
        </div>
    </section>
</div>
@endsection

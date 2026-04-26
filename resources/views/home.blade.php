@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @php
        $heroSlides = $heroSlides ?? [];
        $productCategories = $productCategories ?? collect();
    @endphp

    @push('styles')
        <style>
            .home-page {
                --home-accent: var(--color-secondary-600);
                --home-cyan: #38bdf8;
                --home-navy: var(--color-primary-800);
                --home-panel: rgba(255, 255, 255, 0.74);

                background-image:
                    url('{{ asset('upload/backgrounds/homebg2.png') }}'),
                    url('{{ asset('upload/backgrounds/homebg4.jpg') }}'),
                    url('{{ asset('upload/backgrounds/homebg3.jpg') }}');

                background-repeat: no-repeat, no-repeat, no-repeat;

                background-position:
                    top center,
                    center center,
                    bottom center;

                background-size:
                    100% 83vh,
                    100% auto,
                    100% auto;
            }

            .home-page .home-card,
            .home-page .home-panel {
                border-color: rgba(26, 77, 46, 0.12);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.94), rgba(255, 255, 255, 0.82));
                box-shadow: 0 24px 55px rgba(26, 30, 26, 0.06);
                backdrop-filter: blur(18px);
                padding: 1.5rem;
            }

            @media (min-width: 640px) {
                .home-page .home-panel {
                    padding: 2rem;
                }
            }

            @media (min-width: 1024px) {
                .home-page .home-panel {
                    padding: 2.5rem;
                }
            }

            .home-page .home-panel.home-newsletter-card {
                padding: 1.25rem;
            }

            @media (min-width: 640px) {
                .home-page .home-panel.home-newsletter-card {
                    padding: 1.5rem;
                }
            }

            @media (min-width: 1024px) {
                .home-page .home-panel.home-newsletter-card {
                    padding: 2rem;
                }
            }

            .hero-gradient-overlay {
                background: linear-gradient(to bottom, rgba(15, 23, 42, 0.35), rgba(15, 23, 42, 0.25), rgba(15, 23, 42, 0.30));
            }

            .testimonial-card {
                position: relative;
                isolation: isolate;
                overflow: hidden;
            }

            .testimonial-card::before {
                content: '"';
                position: absolute;
                top: -1rem;
                right: 1.5rem;
                font-family: 'Sora', serif;
                font-size: 8rem;
                font-weight: 800;
                color: rgba(26, 77, 46, 0.04);
                pointer-events: none;
                z-index: -1;
                line-height: 1;
            }

            .testimonial-author-badge {
                position: relative;
                display: inline-flex;
                height: 3rem;
                width: 3rem;
                align-items: center;
                justify-content: center;
                border-radius: 1rem;
                font-weight: 700;
                color: #fff;
                box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.15);
                border: 2px solid rgba(255, 255, 255, 0.82);
            }

            .insight-card .insight-icon-shell {
                transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), background 0.3s ease;
            }

            .home-panel:hover .insight-icon-shell {
                transform: scale(1.1) translateY(-2px);
            }

            .home-page .home-card {
                transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
            }

            .home-page .home-card:hover,
            .home-page .home-panel:hover {
                border-color: rgba(255, 106, 0, 0.25);
                box-shadow: 0 28px 60px rgba(26, 30, 26, 0.09);
            }

            .home-page .home-input {
                border-radius: 1rem;
                border-color: rgba(26, 77, 46, 0.18);
                background: rgba(255, 255, 255, 0.95);
            }

            .home-page .home-primary-button {
                border-radius: 1rem;
                background: var(--color-accent-orange);
                box-shadow: 0 18px 30px rgba(26, 77, 46, 0.28);
            }

            .home-page .home-primary-button:hover {
                background: var(--color-accent-orange);
            }

            .home-categories-heading h2,
            .home-solutions-heading h2 {
                color: #020617;
                font-weight: 700;
            }

            .home-categories-heading p,
            .home-solutions-heading p {
                color: #334155;
                font-weight: 600;
            }

            .home-hero::before,
            .home-hero::after {
                content: '';
                position: absolute;
                border-radius: 9999px;
                pointer-events: none;
                filter: blur(12px);
            }

            .home-hero::before {
                top: 4rem;
                left: -6rem;
                height: 18rem;
                width: 18rem;
                background: radial-gradient(circle, rgba(255, 106, 0, 0.34), transparent 70%);
            }

            .home-hero::after {
                right: -5rem;
                top: -2rem;
                height: 20rem;
                width: 20rem;
                background: radial-gradient(circle, rgba(56, 189, 248, 0.28), transparent 72%);
            }

            .home-hero-track-overlay {
                background-image:
                    linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
                background-size: 42px 42px;
                mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.85), transparent);
            }

            .home-hero-copy-shell {
                position: relative;
                max-width: 64rem;
            }

            .home-hero-title,
            .home-hero-copy,
            .home-hero-chip-text {
                text-shadow: 0 8px 28px rgba(0, 0, 0, 0.45);
            }

            .home-hero-focus-card {
                width: 100%;
                border-radius: var(--ui-radius-card);
                border: 1px solid rgba(255, 255, 255, 0.18);
                background: linear-gradient(180deg, rgba(13, 43, 25, 0.6), rgba(13, 43, 25, 0.85));
                box-shadow: 0 22px 55px rgba(0, 0, 0, 0.22);
                padding: 1.25rem;
                backdrop-filter: blur(18px);
            }

            .home-hero-focus-title,
            .home-hero-focus-copy,
            .home-hero-focus-meta {
                text-shadow: 0 10px 26px rgba(0, 0, 0, 0.42);
            }

            .home-route-visual {
                position: relative;
                overflow: hidden;
                border-radius: var(--ui-radius-card);
                min-height: 11rem;
            }

            .home-route-visual::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(7, 17, 31, 0.14), rgba(7, 17, 31, 0.76));
            }

            .home-route-visual img {
                height: 100%;
                width: 100%;
                object-fit: cover;
                transition: transform 0.6s ease;
            }

            .home-panel:hover .home-route-visual img {
                transform: scale(1.05);
            }

            .home-route-copy {
                position: absolute;
                inset: auto 1rem 1rem 1rem;
                z-index: 1;
            }

            .home-route-card {
                position: relative;
                overflow: hidden;
                min-height: 25rem;
                border-radius: var(--ui-radius-card);
                border: 1px solid rgba(255, 255, 255, 0.15);
                box-shadow: 0 26px 64px rgba(26, 30, 26, 0.12);
            }

            .home-route-card img {
                position: absolute;
                inset: 0;
                height: 100%;
                width: 100%;
                object-fit: cover;
                transition: transform 0.7s ease;
            }

            .home-route-card::before {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(180deg, rgba(13, 43, 25, 0.08) 0%, rgba(13, 43, 25, 0.22) 32%, rgba(13, 43, 25, 0.84) 100%);
                z-index: 0;
            }

            .home-route-card::after {
                content: '';
                position: absolute;
                inset: 1rem;
                border-radius: 1.5rem;
                border: 1px solid rgba(255, 255, 255, 0.12);
                pointer-events: none;
                z-index: 1;
            }

            .home-route-card:hover img {
                transform: scale(1.05);
            }

            .home-route-card__content {
                position: relative;
                z-index: 2;
                display: flex;
                min-height: 25rem;
                flex-direction: column;
                justify-content: flex-end;
                padding: 1.5rem;
            }

            .home-route-card__chip {
                position: absolute;
                left: 1.5rem;
                top: 1.5rem;
                z-index: 2;
                display: inline-flex;
                align-items: center;
                border-radius: 9999px;
                border: 1px solid rgba(255, 255, 255, 0.18);
                background: rgba(255, 255, 255, 0.12);
                padding: 0.55rem 0.9rem;
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: rgba(255, 255, 255, 0.88);
                backdrop-filter: blur(12px);
            }

            .home-route-card__panel {
                border-radius: 1.6rem;
                border: 1px solid rgba(255, 255, 255, 0.12);
                background: linear-gradient(180deg, rgba(13, 43, 25, 0.34), rgba(13, 43, 25, 0.52));
                padding: 1.25rem;
                backdrop-filter: blur(10px);
            }

            .home-delivery-card {
                position: relative;
                overflow: hidden;
            }

            .home-delivery-card::before {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(135deg, rgba(255, 106, 0, 0.96), rgba(255, 60, 0, 0.92)),
                    url('{{ asset('upload/corousel/lucknow-map.svg') }}');
                background-position: center, center;
                background-repeat: no-repeat, no-repeat;
                background-size: cover, cover;
                opacity: 1;
            }

            .home-delivery-card::after {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.15), transparent 26%),
                    linear-gradient(90deg, rgba(255, 106, 0, 0.14), transparent 52%);
            }

            .home-delivery-content {
                position: relative;
                z-index: 1;
            }

            .home-stats {
                margin-top: 1.5rem;
                z-index: 20;
            }

            .home-stats-shell {
                border: 1px solid rgba(26, 77, 46, 0.12);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.76));
                box-shadow: 0 28px 70px rgba(26, 30, 26, 0.08);
                backdrop-filter: blur(24px);
            }

            .home-category-carousel-wrapper {
                position: relative;
                margin-top: 1.5rem;
                padding-bottom: 0.5rem;
            }

            .home-category-grid {
                display: flex;
                gap: 1rem;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scrollbar-width: none;
                -ms-overflow-style: none;
                scroll-behavior: smooth;
            }

            .home-category-grid::-webkit-scrollbar {
                display: none;
            }

            .home-category-tile {
                position: relative;
                overflow: hidden;
                min-height: 0;
                display: flex;
                flex-direction: column;
                border-radius: var(--ui-radius-card);
                border: 1px solid rgba(26, 77, 46, 0.1);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0.9));
                box-shadow: 0 14px 30px rgba(26, 30, 26, 0.05);
                isolation: isolate;
                flex: 0 0 calc(100% - 1rem);
                scroll-snap-align: start;
            }

            .home-category-tile__media {
                position: relative;
                height: 14.25rem;
                flex: 0 0 14.25rem;
                overflow: hidden;
            }

            .home-category-tile__image {
                height: 100%;
                width: 100%;
                object-fit: cover;
                transition: transform 0.7s ease;
            }

            .home-category-tile__media::after {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(180deg, rgba(7, 17, 31, 0.04) 0%, rgba(7, 17, 31, 0.08) 55%, rgba(7, 17, 31, 0.26) 100%);
                z-index: 0;
            }

            .home-category-tile:hover .home-category-tile__image {
                transform: scale(1.06);
            }

            .home-category-tile__content {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                gap: 0.55rem;
                flex: 1;
                padding: 0.85rem;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.96));
            }

            .home-category-tile--featured,
            .home-category-tile--featured .home-category-tile__content {
                min-height: 0;
            }

            .home-category-tile--featured .home-category-tile__media {
                height: 14.25rem;
                flex-basis: 14.25rem;
            }

            .home-category-pill {
                display: inline-flex;
                align-items: center;
                border-radius: 9999px;
                border: 1px solid rgba(255, 255, 255, 0.22);
                background: rgba(255, 255, 255, 0.75);
                padding: 0.35rem 0.7rem;
                font-size: 0.64rem;
                font-weight: 700;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: #0f172a;
                backdrop-filter: blur(12px);
            }

            .home-category-meta {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                border-radius: 9999px;
                border: 1px solid rgba(255, 106, 0, 0.16);
                background: rgba(255, 243, 236, 0.95);
                padding: 0.35rem 0.65rem;
                font-size: 0.7rem;
                font-weight: 600;
                color: #9a3412;
                backdrop-filter: blur(10px);
            }

            .home-category-copy {
                max-width: 28rem;
                color: var(--ui-text-muted);
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 3;
                overflow: hidden;
            }

            .home-category-action {
                display: inline-flex;
                min-height: 2.5rem;
                align-items: center;
                justify-content: center;
                border-radius: 0.9rem;
                background: linear-gradient(135deg, var(--color-primary-600), var(--color-primary-500));
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
                font-weight: 700;
                color: #ffffff;
                box-shadow: 0 14px 24px rgba(26, 77, 46, 0.22);
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .home-category-action:hover {
                transform: translateY(-1px);
                background: linear-gradient(135deg, var(--color-primary-700), var(--color-primary-600));
                box-shadow: 0 16px 28px rgba(26, 77, 46, 0.28);
            }

            @media (min-width: 640px) {
                .home-category-tile {
                    flex: 0 0 calc(50% - 0.5rem);
                }
            }

            @media (min-width: 768px) {
                .home-category-tile {
                    flex: 0 0 calc(33.333% - 0.666rem);
                }
            }

            @media (min-width: 1024px) {
                .home-category-tile {
                    flex: 0 0 calc(25% - 0.75rem);
                }
            }

            .home-solutions {
                background: rgba(255, 255, 255, 0.4);
                backdrop-filter: blur(10px);
            }

            .home-trust {
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.52), rgba(255, 255, 255, 0.82));
                backdrop-filter: blur(14px);
            }

            .home-cta {
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.15), transparent 28%),
                    linear-gradient(135deg, rgba(255, 106, 0, 0.96), rgba(255, 60, 0, 0.92));
            }

            .home-reveal {
                opacity: 0;
                transform: translateY(24px);
                transition: opacity 0.8s ease, transform 0.8s ease;
                will-change: opacity, transform;
            }

            .home-reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            @media (prefers-reduced-motion: reduce) {

                .home-page .home-card,
                .home-page .home-panel,
                .home-reveal {
                    transition: none !important;
                    transform: none !important;
                    opacity: 1 !important;
                }
            }

            .home-solutions-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            @media (min-width: 1024px) {
                .home-solutions-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 1023px) {
                .home-solutions-grid {
                    display: flex;
                    overflow-x: auto;
                    scroll-snap-type: x mandatory;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                    gap: 1.25rem;
                    padding-bottom: 1.5rem;
                    scroll-behavior: smooth;
                    margin: 0 -0.5rem;
                    padding: 0 0.5rem 1.5rem 0.5rem;
                }

                .home-solutions-grid::-webkit-scrollbar {
                    display: none;
                }

                .home-solutions-grid .home-route-card {
                    flex: 0 0 calc(100% - 1.5rem);
                    scroll-snap-align: center;
                    min-height: 26rem;
                }
            }
        </style>
    @endpush

    <div class="home-page">

        <section class="home-hero relative overflow-hidden bg-primary-800 text-white min-h-[83vh]"
            aria-roledescription="carousel" aria-label="Hero slideshow">
            <div class="home-hero-track-overlay absolute inset-0 opacity-30"></div>
            <div class="absolute inset-0 overflow-hidden" id="heroCarousel">
                <div id="heroTrack" class="flex h-full w-full translate-x-0 transition-transform duration-700 ease-out" aria-live="off">
                    @forelse ($heroSlides ?? [] as $slide)
                        <article class="relative h-full w-full shrink-0">
                            <img src="{{ asset($slide['image']) }}" alt="{{ $slide['title'] }}"
                                class="absolute inset-0 h-full w-full object-cover opacity-80" style="filter: blur(4px); transform: scale(1.03);" @if ($loop->first) fetchpriority="high"
                                @else loading="lazy" @endif decoding="async">
                            <div class="hero-gradient-overlay absolute inset-0 z-0"></div>

                            <div
                                class="relative z-10 mx-auto flex min-h-[83vh] w-full max-w-none flex-col items-center justify-center px-4 py-12 text-center sm:px-6 md:py-16 lg:px-8 xl:px-10">
                                <div
                                    class="home-hero-copy-shell flex max-w-5xl flex-col items-center justify-center text-visible">
                                    @if($loop->index === 0)
                                        <h1
                                            class="font-display mb-4 text-4xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                                            Precision <span class="text-secondary-600">Diagnostics</span>. Built on Experience.
                                        </h1>
                                        <p class="mb-6 text-2xl font-bold text-secondary-600 sm:text-3xl md:text-4xl">
                                            Driven by Innovation
                                        </p>
                                        <p class="home-hero-copy max-w-3xl text-base leading-8 text-white/90 md:text-xl">
                                            Biogenix delivers high-performance diagnostic solutions backed by years of industry
                                            expertise, advanced manufacturing, and a commitment to continuous innovation.
                                        </p>
                                    @elseif($loop->index === 1)
                                        <h2
                                            class="font-display mb-6 text-4xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                                            Driven by <span class="text-secondary-600">Innovation</span>.
                                        </h2>
                                        <p class="home-hero-copy max-w-3xl text-base leading-8 text-white/90 md:text-xl">
                                            From trusted manufacturing foundations to next-generation diagnostic technologies,
                                            Biogenix empowers laboratories and institutions with precision, reliability, and scale.
                                        </p>
                                    @elseif($loop->index === 2)
                                        <h2
                                            class="font-display mb-6 text-4xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                                            Trusted <span class="text-secondary-600">Diagnostics</span>. Proven Over Time.
                                        </h2>
                                        <p class="home-hero-copy max-w-3xl text-base leading-8 text-white/90 md:text-xl">
                                            With a strong legacy in diagnostic manufacturing and distribution, Biogenix continues to
                                            deliver reliable solutions to laboratories across India.
                                        </p>
                                    @else
                                        <h2
                                            class="font-display mb-6 text-4xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                                            Next-Gen <span class="text-secondary-600">Healthcare</span> Solutions
                                        </h2>
                                        <p class="home-hero-copy max-w-3xl text-base leading-8 text-white/90 md:text-xl">
                                            {{ $slide['copy'] }}
                                        </p>
                                    @endif
                                    <div class="mt-8 flex justify-center">
                                        <a href="{{ route('products.index') }}"
                                            class="home-primary-button inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all hover:scale-105 sm:text-lg">
                                            Explore Our Products
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="relative h-full w-full shrink-0">
                            <img src="{{ asset('upload/corousel/image1.jpg') }}" alt="Precision Diagnostics"
                                class="absolute inset-0 h-full w-full object-cover opacity-80" style="filter: blur(4px); transform: scale(1.03);" fetchpriority="high" decoding="async">
                            <div class="hero-gradient-overlay absolute inset-0 z-0"></div>
                            <div
                                class="relative z-10 mx-auto flex min-h-[83vh] w-full max-w-none flex-col items-center justify-center px-4 py-12 text-center sm:px-6 md:py-16 lg:px-8 xl:px-10">
                                <div
                                    class="home-hero-copy-shell flex max-w-5xl flex-col items-center justify-center text-visible">
                                    <h2
                                        class="font-display mb-6 text-4xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                                        Next-Gen <span class="text-secondary-600">Healthcare</span> Solutions
                                    </h2>
                                    <p class="home-hero-copy max-w-3xl text-base leading-8 text-white/90 md:text-xl">
                                        Biogenix delivers precision diagnostic tools and intelligent instrument ecosystems for
                                        North India's labs and care networks.</p>
                                    <div class="mt-8 flex justify-center">
                                        <a href="{{ route('products.index') }}"
                                            class="home-primary-button inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all hover:scale-105 sm:text-lg">
                                            Explore Our Products
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforelse
                </div>
            </div>

            <div class="pointer-events-none absolute bottom-5 left-0 right-0 z-20 sm:bottom-6">
                <div
                    class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10 flex items-center justify-between gap-3">
                    <div id="heroDots" class="pointer-events-auto flex items-center gap-2">
                        @foreach ($heroSlides ?? [] as $slide)
                            <button type="button" class="h-2.5 w-8 rounded-full bg-white/40 transition hover:bg-white/80"
                                data-hero-dot data-slide-index="{{ $loop->index }}"
                                aria-label="Go to slide {{ $loop->iteration }}" @if($loop->first) aria-current="true" @endif></button>
                        @endforeach
                    </div>
                    <div class="pointer-events-auto flex gap-2">
                        <button id="heroPrev" type="button" aria-label="Previous slide"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/50 bg-white/10 text-white transition hover:bg-white/25 hover:scale-105">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                            </svg>
                        </button>
                        <button id="heroNext" type="button" aria-label="Next slide"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/50 bg-white/10 text-white transition hover:bg-white/25 hover:scale-105">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Highlighted Numbering Section -->
        <section class="bg-white/80 py-6 md:py-8 shadow-[0_4px_30px_rgba(0,0,0,0.03)] border-b border-slate-100 relative z-20 backdrop-blur-md">
            <div class="mx-auto max-w-[1400px] w-full px-4 sm:px-6 lg:px-8">

                {{-- Mobile: horizontal scroll strip; md+: centred wrap --}}
                <div class="flex gap-x-8 gap-y-6 overflow-x-auto scroll-smooth snap-x snap-mandatory [-webkit-overflow-scrolling:touch] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden
                            md:flex-wrap md:justify-center md:overflow-visible md:gap-x-16 md:gap-y-8 lg:w-[900px] lg:mx-auto
                            pb-2 md:pb-0">

                    <!-- Item 1 -->
                    <div class="flex flex-col items-center text-center w-[160px] shrink-0 snap-start md:w-full md:max-w-[200px] md:shrink">
                        <div class="mb-3 text-primary-600">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                        </div>
                        <h4 class="text-[26px] font-bold text-slate-700 tracking-tight">10,000 <span class="text-xl">+</span></h4>
                        <p class="mt-1 text-sm font-bold text-slate-500 leading-snug tracking-wide">Laboratories &amp; Hospitals Served</p>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex flex-col items-center text-center w-[160px] shrink-0 snap-start md:w-full md:max-w-[200px] md:shrink">
                        <div class="mb-5 text-primary-600">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                            </svg>
                        </div>
                        <h4 class="text-[26px] font-bold text-slate-700 tracking-tight">18 <span class="text-xl">+</span></h4>
                        <p class="mt-1 text-sm font-bold text-slate-500 leading-snug tracking-wide">Years in Diagnostics Industry</p>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex flex-col items-center text-center w-[160px] shrink-0 snap-start md:w-full md:max-w-[200px] md:shrink">
                        <div class="mb-5 text-primary-600">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.58 1.838-1.589-5.06-5.044 1.636 1.838-2.58-5.074-1.353 5.06-1.589-.016-.016c.4-.413.916-.704 1.488-.847L12.5 7.5m1.184 9.1l3.529-5.07 5.06 1.589-1.353-5.074 2.58-1.838-5.044 1.636-1.589-5.06-2.58 1.838-4.996-1.332m0 0a9.003 9.003 0 00-4.088 14.887M9.25 5L7 7" />
                            </svg>
                        </div>
                        <h4 class="text-[26px] font-bold text-slate-700 tracking-tight">500 <span class="text-xl">+</span></h4>
                        <p class="mt-1 text-sm font-bold text-slate-500 leading-snug tracking-wide">Diagnostic Products &amp; Instruments</p>
                    </div>

                    <!-- Item 4 -->
                    <div class="flex flex-col items-center text-center w-[160px] shrink-0 snap-start md:w-full md:max-w-[200px] md:shrink">
                        <div class="mb-5 text-primary-600">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-[26px] font-bold text-slate-700 tracking-tight">650 <span class="text-xl">+</span></h4>
                        <p class="mt-1 text-sm font-bold text-slate-500 leading-snug tracking-wide">Channels and Distributor Partners</p>
                    </div>

                    <!-- Item 5 -->
                    <div class="flex flex-col items-center text-center w-[160px] shrink-0 snap-start md:w-full md:max-w-[200px] md:shrink">
                        <div class="mb-5 text-primary-600">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 10.5l8-8.5 4.5 4.5 7.5-7.5" />
                            </svg>
                        </div>
                        <h4 class="text-[26px] font-bold text-slate-700 tracking-tight">150 <span class="text-xl">+</span></h4>
                        <p class="mt-1 text-sm font-bold text-slate-500 leading-snug tracking-wide">Government &amp; Institutional Clients</p>
                    </div>

                    <!-- Item 6 -->
                    <div class="flex flex-col items-center text-center w-[160px] shrink-0 snap-start md:w-full md:max-w-[200px] md:shrink">
                        <div class="mb-5 text-primary-600">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <h4 class="text-[26px] font-bold text-slate-700 tracking-tight">50 Million <span class="text-xl">+</span></h4>
                        <p class="mt-1 text-sm font-bold text-slate-500 leading-snug tracking-wide">Test Kits Produced Annually</p>
                    </div>
                </div>
            </div>
        </section>


        <!-- Portfolio Section -->

        <section class="bg-white py-8 md:py-10 relative z-10 border-b border-slate-100">
            <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
                <div class="w-full">
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <span class="text-primary-600 font-medium text-[15px] tracking-wide mb-1 block">Showcase</span>
                            <h2 class="text-4xl md:text-[44px] font-bold text-slate-800 tracking-tight font-display">Institutional Network</h2>
                        </div>
                        <a href="{{ route('portfolio') }}" class="inline-flex items-center gap-3 text-primary-600 font-bold text-lg hover:text-primary-700 transition group">
                            <span class="flex items-center justify-center w-[42px] h-[42px] rounded-full border-2 border-primary-600 group-hover:bg-primary-50 group-hover:scale-105 transition">
                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            See All
                        </a>
                    </div>
                    <p class="mt-5 text-[17px] text-slate-500 leading-relaxed max-w-3xl font-medium">
                        With a strong and growing presence across India, Biogenix has established trusted partnerships with 150+ government institutions, healthcare organisations, and diagnostic networks.<br><br>
                        Our collaborations span across state healthcare departments, government hospitals, medical colleges, and public health programs, reinforcing our commitment to strengthening diagnostic infrastructure at scale.
                    </p>

                    @php
                        $portfolioLogoFiles = collect(\Illuminate\Support\Facades\File::files(public_path('upload/portfolio')))
                            ->sortBy(fn ($file) => strtolower($file->getFilename()))
                            ->values();
                    @endphp
                    <div class="mt-8 w-full">
                        @if ($portfolioLogoFiles->isNotEmpty())
                            @php
                                $portfolioPerRow = (int) ceil($portfolioLogoFiles->count() / 2);
                                $portfolioRows = [
                                    $portfolioLogoFiles->slice(0, $portfolioPerRow)->values(),
                                    $portfolioLogoFiles->slice($portfolioPerRow)->values(),
                                ];
                            @endphp

                            <div class="flex gap-3 overflow-x-auto pb-2 md:hidden [scrollbar-width:none] [&::-webkit-scrollbar]:hidden snap-x snap-mandatory">
                                @foreach ($portfolioLogoFiles as $logoFile)
                                    @php
                                        $logoName = $logoFile->getFilename();
                                        $logoLabel = preg_replace('/\.[^.]+$/', '', $logoName);
                                        $logoUrl = asset('upload/portfolio/' . rawurlencode($logoName));
                                    @endphp
                                    <article class="flex h-20 w-[138px] shrink-0 snap-start items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 shadow-sm">
                                        <img
                                            src="{{ $logoUrl }}"
                                            alt="{{ $logoLabel }}"
                                            loading="lazy"
                                            decoding="async"
                                            class="max-h-full w-full object-contain"
                                        >
                                    </article>
                                @endforeach
                            </div>

                            <div class="hidden space-y-4 md:block">
                                @foreach ($portfolioRows as $portfolioRow)
                                    <div class="flex gap-4 overflow-x-auto pb-1 md:overflow-x-visible [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                                        @foreach ($portfolioRow as $logoFile)
                                            @php
                                                $logoName = $logoFile->getFilename();
                                                $logoLabel = preg_replace('/\.[^.]+$/', '', $logoName);
                                                $logoUrl = asset('upload/portfolio/' . rawurlencode($logoName));
                                            @endphp
                                            <article class="flex h-24 min-w-[170px] items-center justify-center rounded-xl border border-slate-200 bg-white p-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:min-w-[180px] md:min-w-0 lg:min-w-[190px]">
                                                <img
                                                    src="{{ $logoUrl }}"
                                                    alt="{{ $logoLabel }}"
                                                    loading="lazy"
                                                    decoding="async"
                                                    class="max-h-full w-full object-contain"
                                                >
                                            </article>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center text-sm text-slate-500">
                                No portfolio images found in <span class="font-semibold">public/upload/portfolio</span>.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="home-categories relative py-12 md:py-16 overflow-hidden">
            <img src="{{ asset('upload/categories/core-categories-bg.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover" loading="lazy" decoding="async">
            <div class="absolute inset-0 bg-gradient-to-br from-white/80 via-white/60 to-white/40 backdrop-blur-[2px]"></div>
            <div class="relative z-10 mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
                <div class="home-reveal home-categories-heading text-slate-900">
                    <x-ui.section-heading title="Core Product Categories"
                        subtitle="Designed for modern diagnostics workflows and scalable healthcare operations." />
                </div>
                <div class="home-category-carousel-wrapper home-reveal group">
                    <button id="catPrev" type="button" aria-label="Previous category"
                        class="absolute -left-2 sm:-left-4 top-1/2 z-10 -translate-y-1/2 inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-400/50 bg-slate-900/5 text-slate-600 backdrop-blur-sm transition hover:scale-110 hover:bg-slate-900/10 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>

                    <button id="catNext" type="button" aria-label="Next category"
                        class="absolute -right-2 sm:-right-4 top-1/2 z-10 -translate-y-1/2 inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-400/50 bg-slate-900/5 text-slate-600 backdrop-blur-sm transition hover:scale-110 hover:bg-slate-900/10 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
                        </svg>
                    </button>

                    <div class="home-category-grid" id="categoryCarousel">
                        @forelse ($productCategories as $category)
                            @php
                                $tileClass = 'home-category-tile--standard';
                                // Try to find a matching image in public/upload/categories/ by category name
                                $catImagePath = null;
                                $catName = $category->name;
                                $catDir = public_path('upload/categories');
                                if (is_dir($catDir)) {
                                    $catFiles = scandir($catDir);
                                    foreach ($catFiles as $catFile) {
                                        $fileBaseName = pathinfo($catFile, PATHINFO_FILENAME);
                                        if (strcasecmp($fileBaseName, $catName) === 0) {
                                            $catImagePath = 'upload/categories/' . $catFile;
                                            break;
                                        }
                                    }
                                }
                                $imagePath = $catImagePath ?: ($category->default_image_path ?: 'upload/categories/image1.jpg');
                                $categoryCopy = \Illuminate\Support\Str::limit($category->description ?: $category->application ?: 'Explore products from this category.', 60);
                            @endphp
                            <article
                                class="home-category-tile glass-card hover-lift home-reveal group {{ $tileClass }} rounded-[var(--ui-radius-card)]">
                                <div class="home-category-tile__media">
                                    <img src="{{ asset($imagePath) }}" alt="{{ $category->name }}"
                                        class="home-category-tile__image" loading="lazy" decoding="async">
                                    <div class="relative z-[1] flex items-start justify-between gap-3 p-4">
                                        <span
                                            class="home-category-pill">{{ $loop->first ? 'Featured category' : 'Category' }}</span>
                                        @if (isset($category->products_count) && $category->products_count > 0)
                                            <span class="home-category-meta">{{ $category->products_count }} products</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="home-category-tile__content">
                                    <div>
                                        <h3 class="font-display text-lg font-semibold tracking-tight text-slate-950">
                                            {{ $category->name }}
                                        </h3>
                                        <p class="home-category-copy mt-1.5 text-[13px] leading-5.5">
                                            {{ $categoryCopy }}
                                        </p>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="{{ route('products.index', ['category' => $category->slug ?? $category->id]) }}"
                                            class="home-category-action hover-lift glow-orange">Explore</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="home-panel sm:col-span-2 xl:col-span-5">
                                <h3 class="text-lg font-semibold text-slate-900">Categories will appear here</h3>
                                <p class="mt-2 text-sm text-slate-600">No home page categories are available right now.</p>
                            </article>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section class="home-solutions py-12 md:py-16">
            <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
                <div class="home-reveal home-solutions-heading">
                    <x-ui.section-heading title="Clinical Business Solutions"
                        subtitle="Purpose-built pathways for B2B institutions and B2C healthcare buyers." />
                </div>
                <div class="relative mt-6">
                    <!-- Carousel Navigation Buttons (Visible only on mobile/tablet) -->
                    <button id="solutionsPrev" type="button" aria-label="Previous solution"
                        class="absolute -left-2 top-1/2 z-20 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white backdrop-blur-md transition-all hover:scale-110 hover:bg-white/20 lg:hidden focus:outline-none ring-1 ring-white/5">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>

                    <button id="solutionsNext" type="button" aria-label="Next solution"
                        class="absolute -right-2 top-1/2 z-20 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white backdrop-blur-md transition-all hover:scale-110 hover:bg-white/20 lg:hidden focus:outline-none ring-1 ring-white/5">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
                        </svg>
                    </button>

                    <div class="home-solutions-grid" id="solutionsCarousel">
                        <article class="home-route-card home-reveal">
                            <img src="{{ asset('upload/corousel/b2b-enterprise-bg.png') }}" alt="B2B Enterprise Solutions"
                                loading="lazy" decoding="async">
                            <span class="home-route-card__chip">B2B Operations</span>
                            <div class="home-route-card__content">
                                <div class="home-route-card__panel">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/65">For
                                        institutions and channel partners</p>
                                    <h3 class="mt-3 text-2xl font-semibold text-white">Structured buying for distributors, labs and hospitals and channel partners.</h3>
                                    <p class="mt-3 text-sm leading-7 text-primary-50/90">Login-based pricing, quotation flow, order confirmation and real-time tracking in one connected system.</p>
                                    <div class="mt-5 flex flex-wrap gap-3">
                                        <x-ui.action-link :href="route('login', ['user_type' => 'b2b'])"
                                            variant="inverse">B2B Login</x-ui.action-link>
                                        <x-ui.action-link href="{{ url('/b2b-signup') }}"
                                            class="bg-black/30 hover:bg-black/50 border border-white/10 text-white backdrop-blur-md transition-colors">B2B
                                            Sign Up</x-ui.action-link>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="home-route-card home-reveal">
                            <img src="{{ asset('upload/corousel/b2c-consumer-bg.png') }}" alt="B2C Direct-to-Consumer"
                                loading="lazy" decoding="async">
                            <span class="home-route-card__chip">B2C Access</span>
                            <div class="home-route-card__content">
                                <div class="home-route-card__panel">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/65">For direct and
                                        retail buyers</p>
                                    <h3 class="mt-3 text-2xl font-semibold text-white">Faster product access for labs, clinics and hospitals.</h3>
                                    <p class="mt-3 text-sm leading-7 text-primary-50/90">Browse, generate a quote, check out, pay securely and track your order with minimal friction.</p>
                                    <div class="mt-5 flex flex-wrap gap-3">
                                        <x-ui.action-link :href="route('login', ['user_type' => 'b2c'])"
                                            variant="inverse">B2C Login</x-ui.action-link>
                                        <x-ui.action-link href="{{ url('/signup') }}"
                                            class="bg-black/30 hover:bg-black/50 border border-white/10 text-white backdrop-blur-md transition-colors">B2C
                                            Sign Up</x-ui.action-link>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-newsletter bg-transparent py-12 md:py-16">
            <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-5 px-4 sm:px-6 xl:grid-cols-12 lg:px-8 xl:px-10">
                <article class="home-quiz-card home-reveal overflow-hidden rounded-[var(--ui-radius-card)] bg-primary-600 px-6 py-8 shadow-[0_24px_55px_rgba(26,77,46,0.25)] xl:col-span-7 md:flex md:items-center md:justify-between md:p-10">
                    <div class="max-w-xl">
                        <h2 class="font-display text-2xl font-semibold tracking-tight text-white lg:text-3xl">
                            Test Your Diagnostic<br>Precision</h2>
                        <p class="mt-3 text-sm leading-6 text-white/80 md:text-[15px] md:leading-7">
                            Take our 4-minute kit assessment and unlock a 15% discount code on your first clinical order.
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0 md:pl-6 md:shrink-0 flex items-center">
                        <a href="{{ route('diagnostic-quiz') }}" class="inline-flex items-center gap-2.5 rounded-xl border border-secondary-700/20 bg-secondary-600 px-7 py-3.5 text-sm font-semibold text-primary-800 shadow-sm transition hover:scale-105 hover:bg-secondary-500 glow-orange">
                            Start Quiz
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </a>
                    </div>
                </article>

                <article class="home-panel home-newsletter-card home-reveal rounded-[var(--ui-radius-card)] xl:col-span-5">
                    <h3 class="text-xl font-semibold text-slate-900">Stay Updated with Biogenix</h3>
                    <p class="mt-1.5 text-sm text-slate-600">Get updates on new product launches, technical insights, and
                        operational improvements designed for modern diagnostic setups.</p>
                    <form id="newsletterForm" class="mt-3 space-y-2.5" novalidate>
                        <div>
                            <label for="newsletterEmail" class="mb-1.5 block text-sm font-semibold text-slate-700">Work
                                Email</label>
                            <input id="newsletterEmail" type="email"
                                class="block min-h-10 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10"
                                placeholder="you@organization.com" required>
                        </div>
                        <button type="submit" id="newsletterSubmitBtn"
                            class="inline-flex min-h-10 w-full items-center justify-center rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20">Subscribe</button>
                        <p id="newsletterStatus" class="min-h-[1rem] text-sm font-medium text-slate-600"></p>
                    </form>
                </article>
            </div>
        </section>

        <section class="home-northern-hub bg-transparent py-10 md:py-12">
            <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
                <div
                    class="home-reveal relative overflow-hidden rounded-[var(--ui-radius-card)] border border-slate-200/80 shadow-[var(--ui-shadow-card)] glass-card">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <div class="flex flex-col justify-center p-5 sm:p-7 lg:p-8 xl:p-10">
                            <h2
                                class="font-display text-[1.375rem] font-bold tracking-tight text-slate-950 sm:text-2xl md:text-3xl lg:text-[2rem] leading-[1.2]">
                                Nationwide Presence. <br class="sm:hidden">Trusted Distribution Network.</h2>
                            <p class="mt-3 max-w-lg text-sm leading-6 text-slate-600 md:text-[15px] md:leading-7">
                                Biogenix is supported by a growing network of authorized distributors and partners across
                                India, ensuring reliable product availability, faster access, and localized support for
                                laboratories, hospitals, and institutions.
                            </p>

                            <ul class="mt-5 space-y-2.5">
                                <li class="flex items-center gap-3">
                                    <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-600">
                                        <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                    <span class="text-sm font-semibold text-slate-800">Verified & Authorized Channel
                                        Partners</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-600">
                                        <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                    <span class="text-sm font-semibold text-slate-800">Strong Regional Distribution
                                        Coverage</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span
                                        class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-600">
                                        <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                    <span class="text-sm font-semibold text-slate-800">Reliable Product Availability &
                                        Support</span>
                                </li>
                            </ul>

                            <div class="mt-8">
                                <button type="button" onclick="openDistributorModal()"
                                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-primary-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-primary-600/20 transition hover:scale-[1.02] active:scale-95 hover:bg-primary-700">
                                    Find a Distributor
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div
                            class="relative min-h-[240px] lg:min-h-[360px] overflow-hidden rounded-[var(--ui-radius-card)]">
                            <img src="{{ asset('upload/corousel/nationwide-bg.jpg') }}" alt="Biogenix Distribution Network"
                                class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
                            <div
                                class="absolute bottom-4 right-4 z-10 rounded-2xl border border-secondary-700/20 bg-secondary-600 px-4 py-2.5 shadow-sm sm:bottom-5 sm:right-5 text-center">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-900">Authorized
                                    Network</p>
                                <p class="font-display mt-1 text-lg font-bold tracking-tight text-slate-900">Pan-India
                                    Distribution</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                /* Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Hero Carousel Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ */
                const track = document.getElementById('heroTrack');
                const dots = Array.from(document.querySelectorAll('[data-hero-dot]'));
                const nextBtn = document.getElementById('heroNext');
                const prevBtn = document.getElementById('heroPrev');
                const carousel = document.getElementById('heroCarousel');
                let index = 0;
                let intervalId = null;
                const total = dots.length;

                function paintDots() {
                    dots.forEach(function (dot, dotIndex) {
                        const active = dotIndex === index;
                        dot.classList.toggle('bg-white', active);
                        dot.classList.toggle('w-10', active);
                        dot.classList.toggle('bg-white/40', !active);
                        dot.setAttribute('aria-current', active ? 'true' : 'false');
                    });
                }

                function moveTo(target) {
                    if (!track || total === 0) return;
                    index = (target + total) % total;
                    track.style.transform = 'translateX(-' + (index * 100) + '%)';
                    paintDots();
                }

                function startAuto() {
                    if (total <= 1) return;
                    if (intervalId) clearInterval(intervalId);
                    intervalId = setInterval(function () { moveTo(index + 1); }, 5000);
                }

                function stopAuto() {
                    if (intervalId) { clearInterval(intervalId); intervalId = null; }
                }

                dots.forEach(function (dot) {
                    dot.addEventListener('click', function () {
                        moveTo(Number(dot.getAttribute('data-slide-index') || 0));
                        startAuto();
                    });
                });

                if (nextBtn) nextBtn.addEventListener('click', function () { moveTo(index + 1); startAuto(); });
                if (prevBtn) prevBtn.addEventListener('click', function () { moveTo(index - 1); startAuto(); });

                /* Ã¢â€â‚¬Ã¢â€â‚¬ Touch / Swipe support Ã¢â€â‚¬Ã¢â€â‚¬ */
                if (carousel) {
                    let touchStartX = 0;
                    let touchEndX = 0;

                    carousel.addEventListener('touchstart', function (e) {
                        touchStartX = e.changedTouches[0].screenX;
                        stopAuto();
                    }, { passive: true });

                    carousel.addEventListener('touchend', function (e) {
                        touchEndX = e.changedTouches[0].screenX;
                        const diff = touchStartX - touchEndX;
                        if (Math.abs(diff) > 40) {
                            moveTo(diff > 0 ? index + 1 : index - 1);
                        }
                        startAuto();
                    }, { passive: true });

                    carousel.addEventListener('mouseenter', stopAuto);
                    carousel.addEventListener('mouseleave', startAuto);
                }

                if (total > 0) {
                    moveTo(0);
                    startAuto();
                }

                const revealItems = Array.from(document.querySelectorAll('.home-reveal'));
                let revealFrameId = null;

                function refreshRevealItems() {
                    revealFrameId = null;

                    if (!revealItems.length) {
                        return;
                    }

                    const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
                    const visibleTopLimit = viewportHeight * 0.9;
                    const visibleBottomLimit = viewportHeight * 0.08;

                    revealItems.forEach(function (item) {
                        const rect = item.getBoundingClientRect();
                        const isVisible = rect.top <= visibleTopLimit && rect.bottom >= visibleBottomLimit;

                        item.classList.toggle('is-visible', isVisible);
                    });
                }

                function queueRevealRefresh() {
                    if (revealFrameId !== null) {
                        return;
                    }

                    revealFrameId = window.requestAnimationFrame(refreshRevealItems);
                }

                queueRevealRefresh();
                window.addEventListener('load', queueRevealRefresh);
                window.addEventListener('resize', queueRevealRefresh);
                window.addEventListener('scroll', queueRevealRefresh, { passive: true });

                /* Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Newsletter Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ */
                const newsletterForm = document.getElementById('newsletterForm');
                const newsletterBtn = document.getElementById('newsletterSubmitBtn');
                const newsletterStatus = document.getElementById('newsletterStatus');
                const newsletterEmail = document.getElementById('newsletterEmail');

                if (newsletterForm && newsletterBtn && newsletterStatus && newsletterEmail) {
                    newsletterForm.addEventListener('submit', function (event) {
                        event.preventDefault();
                        const email = newsletterEmail.value.trim();
                        newsletterEmail.classList.remove('border-rose-400', 'ring-4', 'ring-rose-500/10');

                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                            newsletterEmail.classList.add('border-rose-400', 'ring-4', 'ring-rose-500/10');
                            newsletterStatus.textContent = 'Please enter a valid email.';
                            newsletterStatus.classList.add('text-rose-600');
                            newsletterStatus.classList.remove('text-primary-600');
                            return;
                        }

                        newsletterBtn.disabled = true;
                        newsletterBtn.classList.add('cursor-not-allowed', 'opacity-70');
                        newsletterBtn.setAttribute('aria-disabled', 'true');
                        newsletterBtn.textContent = 'Subscribed';
                        newsletterStatus.textContent = 'You are subscribed. Thank you.';
                        newsletterStatus.classList.remove('text-rose-600');
                        newsletterStatus.classList.add('text-primary-600');
                        newsletterForm.reset();
                    });
                }

                /* Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Category Carousel Auto Scroll Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ */
                const catCarousel = document.getElementById('categoryCarousel');
                let catScrollInterval;

                function startCatScroll() {
                    if (!catCarousel) return;
                    if (catScrollInterval) clearInterval(catScrollInterval);

                    catScrollInterval = setInterval(() => {
                        const maxScrollLeft = catCarousel.scrollWidth - catCarousel.clientWidth;
                        if (catCarousel.scrollLeft >= maxScrollLeft - 10) {
                            catCarousel.scrollTo({ left: 0, behavior: 'smooth' });
                        } else {
                            const itemWidth = catCarousel.children[0]?.offsetWidth || 300;
                            const gap = 16; // 1rem
                            catCarousel.scrollBy({ left: itemWidth + gap, behavior: 'smooth' });
                        }
                    }, 3000);
                }

                function stopCatScroll() {
                    if (catScrollInterval) clearInterval(catScrollInterval);
                }

                if (catCarousel) {
                    setTimeout(() => {
                        startCatScroll();
                    }, 1500);

                    catCarousel.addEventListener('mouseenter', stopCatScroll);
                    catCarousel.addEventListener('mouseleave', startCatScroll);
                    catCarousel.addEventListener('touchstart', stopCatScroll, { passive: true });
                    catCarousel.addEventListener('touchend', startCatScroll, { passive: true });

                    const catPrev = document.getElementById('catPrev');
                    const catNext = document.getElementById('catNext');

                    if (catPrev) {
                        catPrev.addEventListener('mouseenter', stopCatScroll);
                        catPrev.addEventListener('mouseleave', startCatScroll);
                        catPrev.addEventListener('click', () => {
                            const itemWidth = catCarousel.children[0]?.offsetWidth || 300;
                            catCarousel.scrollBy({ left: -(itemWidth + 16), behavior: 'smooth' });
                        });
                    }

                    if (catNext) {
                        catNext.addEventListener('mouseenter', stopCatScroll);
                        catNext.addEventListener('mouseleave', startCatScroll);
                        catNext.addEventListener('click', () => {
                            const itemWidth = catCarousel.children[0]?.offsetWidth || 300;
                            catCarousel.scrollBy({ left: itemWidth + 16, behavior: 'smooth' });
                        });
                    }
                }

                /* Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Solutions Carousel Auto Scroll (Mobile Only) Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ */
                const solCarousel = document.getElementById('solutionsCarousel');
                const solPrev = document.getElementById('solutionsPrev');
                const solNext = document.getElementById('solutionsNext');
                let solScrollInterval;

                function startSolScroll() {
                    if (!solCarousel || window.innerWidth >= 1024) return;
                    if (solScrollInterval) clearInterval(solScrollInterval);

                    solScrollInterval = setInterval(() => {
                        const maxScrollLeft = solCarousel.scrollWidth - solCarousel.clientWidth;
                        if (solCarousel.scrollLeft >= maxScrollLeft - 10) {
                            solCarousel.scrollTo({ left: 0, behavior: 'smooth' });
                        } else {
                            const itemWidth = solCarousel.children[0]?.offsetWidth || 300;
                            const gap = 20; // 1.25rem
                            solCarousel.scrollBy({ left: itemWidth + gap, behavior: 'smooth' });
                        }
                    }, 4000);
                }

                function stopSolScroll() {
                    if (solScrollInterval) clearInterval(solScrollInterval);
                }

                if (solCarousel) {
                    // Only start if on mobile
                    if (window.innerWidth < 1024) {
                        setTimeout(() => { startSolScroll(); }, 2000);
                    }

                    solCarousel.addEventListener('mouseenter', stopSolScroll);
                    solCarousel.addEventListener('mouseleave', startSolScroll);
                    solCarousel.addEventListener('touchstart', stopSolScroll, { passive: true });
                    solCarousel.addEventListener('touchend', startSolScroll, { passive: true });

                    if (solPrev) {
                        solPrev.addEventListener('click', () => {
                            const itemWidth = solCarousel.children[0]?.offsetWidth || 300;
                            solCarousel.scrollBy({ left: -(itemWidth + 20), behavior: 'smooth' });
                            stopSolScroll();
                            startSolScroll();
                        });
                    }

                    if (solNext) {
                        solNext.addEventListener('click', () => {
                            const itemWidth = solCarousel.children[0]?.offsetWidth || 300;
                            solCarousel.scrollBy({ left: itemWidth + 20, behavior: 'smooth' });
                            stopSolScroll();
                            startSolScroll();
                        });
                    }

                    // Re-check on resize
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            stopSolScroll();
                            solCarousel.scrollTo({ left: 0 });
                        } else {
                            startSolScroll();
                        }
                    });
                }
            });
        </script>
    @endpush

    {{-- Authorized Distributor Modal --}}
    <div id="distributorModal"
        class="fixed inset-0 z-[110] flex items-center justify-center p-4 transition-all duration-300 opacity-0 pointer-events-none"
        role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDistributorModal()"></div>

        {{-- Modal Content --}}
        <div class="relative w-full max-w-4xl overflow-hidden rounded-[28px] border border-white/20 bg-white/95 shadow-[0_24px_80px_rgba(26,77,46,0.2)] backdrop-blur-md transition-all duration-300 scale-95 translate-y-4"
            id="distributorModalContent">
            {{-- Close Button --}}
            <button onclick="closeDistributorModal()"
                class="absolute right-5 top-5 z-20 inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200/80 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-rose-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="max-h-[min(85vh,700px)] overflow-y-auto px-6 py-8 sm:px-8 lg:px-10">
                <div class="mb-8">
                    <h2 class="font-display text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">Authorized
                        Biogenix Network</h2>
                    <p class="mt-2 text-sm text-slate-600">Trusted partners providing reliable access and clinical support
                        across India.</p>
                </div>

                {{-- Filter Bar --}}
                <div class="sticky top-0 z-10 -mx-6 mb-8 bg-white/95 px-6 pb-4 pt-1 backdrop-blur-sm sm:-mx-10 sm:px-10">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="relative">
                            <label for="distributorSearch"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-400">Search</label>
                            <div class="relative">
                                <input type="text" id="distributorSearch" placeholder="Distributor name..."
                                    class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm transition focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10">
                                <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label for="distributorState"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-400">State</label>
                            <select id="distributorState"
                                class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm transition focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10">
                                <option value="">All States</option>
                            </select>
                        </div>
                        <div>
                            <label for="distributorCity"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-400">City</label>
                            <select id="distributorCity"
                                class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm transition focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10">
                                <option value="">All Cities</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Results List --}}
                <div id="distributorList" class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    {{-- Dynamically populated --}}
                </div>

                {{-- Empty State --}}
                <div id="distributorEmpty" class="hidden flex-col items-center justify-center py-12 text-center">
                    <div
                        class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-50 text-slate-400">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="text-base font-semibold text-slate-900">No partner found in this region</p>
                    <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or search terms.</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const distributorDataset = [
                { name: "Global Medilinks", state: "Uttar Pradesh", city: "Lucknow", address: "G-12, Medical Market, Hazratganj", phone: "+91 91234 56789", email: "info@globalmed.in", focus: ["Diagnostics", "Molecular"] },
                { name: "Southern Bio Systems", state: "Karnataka", city: "Bengaluru", address: "Suite 405, Prestige Towers, Residency Rd", phone: "+91 80234 98765", email: "sales@southbio.com", focus: ["Life Science", "ELISA"] },
                { name: "Western Life Science", state: "Maharashtra", city: "Mumbai", address: "Building A, BKC Park, Bandra East", phone: "+91 22233 44455", email: "contact@westernlife.in", focus: ["Rapid Tests", "Serology"] },
                { name: "Oriental Diagnostics", state: "West Bengal", city: "Kolkata", address: "Harish Avenue, Ballygunge", phone: "+91 33245 11223", email: "india.oriental@biogenix.online", focus: ["Instruments", "Reagents"] },
                { name: "Precision Lab Solutions", state: "Delhi", city: "New Delhi", address: "Okhla Phase III, Industrial Area", phone: "+91 11456 78901", email: "delhi@precisionlab.in", focus: ["Pathology", "Technical Support"] },
                { name: "Hitech Solutions Pvt Ltd", state: "Tamil Nadu", city: "Chennai", address: "Anna Salai, Mount Road", phone: "+91 44234 56789", email: "chennai@hitechsol.com", focus: ["Molecular Diagnostics"] },
                { name: "Matrix Meditech", state: "Gujarat", city: "Ahmedabad", address: "SG Highway, Satellite Square", phone: "+91 79234 11223", email: "matrix@amd.meditech.in", focus: ["Rapid Kits", "Serology"] }
            ];

            function openDistributorModal() {
                const modal = document.getElementById('distributorModal');
                const content = document.getElementById('distributorModalContent');
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100', 'pointer-events-auto');
                content.classList.remove('scale-95', 'translate-y-4');
                content.classList.add('scale-100', 'translate-y-0');
                document.body.style.overflow = 'hidden';
                initDistributorFilters();
                renderDistributorList();

                const firstFocusable = content.querySelector('button, input, select, [tabindex]');
                if (firstFocusable) firstFocusable.focus();

                document.addEventListener('keydown', handleModalEscape);
            }

            function handleModalEscape(e) {
                if (e.key === 'Escape') closeDistributorModal();
            }

            function closeDistributorModal() {
                const modal = document.getElementById('distributorModal');
                const content = document.getElementById('distributorModalContent');
                modal.classList.remove('opacity-100', 'pointer-events-auto');
                modal.classList.add('opacity-0', 'pointer-events-none');
                content.classList.remove('scale-100', 'translate-y-0');
                content.classList.add('scale-95', 'translate-y-4');
                document.body.style.overflow = '';
                document.removeEventListener('keydown', handleModalEscape);
            }

            function initDistributorFilters() {
                const stateSelect = document.getElementById('distributorState');
                const citySelect = document.getElementById('distributorCity');

                // Populate states
                const states = [...new Set(distributorDataset.map(d => d.state))].sort();
                stateSelect.innerHTML = '<option value="">All States</option>' +
                    states.map(s => `<option value="${s}">${s}</option>`).join('');

                stateSelect.onchange = () => {
                    const selectedState = stateSelect.value;
                    const filteredCities = [...new Set(distributorDataset.filter(d => !selectedState || d.state === selectedState).map(d => d.city))].sort();
                    citySelect.innerHTML = '<option value="">All Cities</option>' +
                        filteredCities.map(c => `<option value="${c}">${c}</option>`).join('');
                    renderDistributorList();
                };

                citySelect.onchange = renderDistributorList;
                document.getElementById('distributorSearch').oninput = renderDistributorList;
            }

            function renderDistributorList() {
                const searchTerm = document.getElementById('distributorSearch').value.toLowerCase();
                const state = document.getElementById('distributorState').value;
                const city = document.getElementById('distributorCity').value;
                const container = document.getElementById('distributorList');
                const emptyState = document.getElementById('distributorEmpty');

                const filtered = distributorDataset.filter(d => {
                    const matchesSearch = d.name.toLowerCase().includes(searchTerm) || d.address.toLowerCase().includes(searchTerm);
                    const matchesState = !state || d.state === state;
                    const matchesCity = !city || d.city === city;
                    return matchesSearch && matchesState && matchesCity;
                });

                if (filtered.length === 0) {
                    container.innerHTML = '';
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                } else {
                    emptyState.classList.add('hidden');
                    emptyState.classList.remove('flex');
                    container.innerHTML = filtered.map(d => `
                                                    <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-primary-200 hover:shadow-md">
                                                        <div class="flex items-start justify-between">
                                                            <div>
                                                                <span class="mb-2 inline-flex rounded-full bg-primary-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-primary-700">Authorized Distributor</span>
                                                                <h3 class="font-display text-lg font-bold text-slate-900">${d.name}</h3>
                                                                <p class="mt-1 text-sm text-slate-500">${d.address}, ${d.city}, ${d.state}</p>
                                                            </div>
                                                            <div class="flex flex-col gap-2">
                                                                <a href="tel:${d.phone.replace(/\s+/g, '')}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-600 transition hover:bg-primary-600 hover:text-white" title="Call">
                                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                                                </a>
                                                                <a href="mailto:${d.email}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-600 transition hover:bg-primary-600 hover:text-white" title="Email">
                                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="mt-4 flex flex-wrap gap-1.5">
                                                            ${d.focus.map(f => `<span class="rounded-lg bg-slate-50 px-2 py-0.5 text-[11px] font-semibold text-slate-600">${f}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                `).join('');
                }
            }
        </script>
    @endpush
@endsection

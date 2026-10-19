@extends('layouts.app')

@section('content')

    <main>


        <section
            class="hero hero-slider"
            x-data="heroSlider"
            aria-label="Featured skincare collections"
            @mouseenter="pause()"
            @mouseleave="resume()"
            @focusin="pause()"
            @focusout="resume()"
        >
            <div class="hero-slides">
                {{-- Slide 1 --}}
                <article
                    class="hero-slide"
                    x-show="activeSlide === 0"
                    x-transition:enter="hero-enter"
                    x-transition:leave="hero-leave"
                    aria-label="Featured collection"
                >
                    <img
                        src="https://images.unsplash.com/photo-1581182800629-7d90925ad072?auto=format&fit=crop&w=1800&q=86"
                        alt="Woman with healthy, glowing skin"
                        fetchpriority="high"
                    >

                    <div class="hero-overlay"></div>

                    <div class="hero-content shell">
                        <p class="eyebrow">NEW SEASON, BETTER SKIN</p>

                        <h1>
                            Your ritual.<br>
                            <em>Refined.</em>
                        </h1>

                        <p>
                            Discover clinically considered skincare for calm,
                            radiant, resilient skin.
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-dark">
                                Shop the edit
                            </a>

                            <a
                                href="{{ route('categories.show', 'skincare') }}"
                                class="btn btn-light"
                            >
                                Build my routine
                            </a>
                        </div>
                    </div>
                </article>

                {{-- Slide 2 --}}
                <article
                    class="hero-slide"
                    x-show="activeSlide === 1"
                    x-cloak
                >
                    <img
                        src="https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?auto=format&fit=crop&w=1800&q=86"
                        alt="Skincare products for a daily beauty ritual"
                        loading="lazy"
                    >

                    <div class="hero-overlay"></div>

                    <div class="hero-content shell">
                        <p class="eyebrow">YOUR DAILY ESSENTIALS</p>

                        <h1>
                            Simple care.<br>
                            <em>Lasting glow.</em>
                        </h1>

                        <p>
                            Build a thoughtful routine with products selected
                            for your everyday skincare needs.
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-dark">
                                Explore skincare
                            </a>

                            <a
                                href="{{ route('categories.show', 'skincare') }}"
                                class="btn btn-light"
                            >
                                Find your routine
                            </a>
                        </div>
                    </div>
                </article>

                {{-- Slide 3 --}}
                <article
                    class="hero-slide"
                    x-show="activeSlide === 2"
                    x-cloak
                >
                    <img
                        src="https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?auto=format&fit=crop&w=1800&q=86"
                        alt="Beauty and skincare essentials"
                        loading="lazy"
                    >

                    <div class="hero-overlay"></div>

                    <div class="hero-content shell">
                        <p class="eyebrow">A LITTLE SELF-CARE</p>

                        <h1>
                            Make time<br>
                            <em>for yourself.</em>
                        </h1>

                        <p>
                            Discover skincare and beauty essentials that make
                            your daily ritual feel special.
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-dark">
                                Shop all products
                            </a>

                            <a href="{{ route('brands.index') }}" class="btn btn-light">
                                Explore brands
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            {{-- Previous / Next controls --}}
            <button
                type="button"
                class="hero-arrow hero-arrow-prev"
                @click="previous()"
                aria-label="Previous slide"
            >
                <span aria-hidden="true">&larr;</span>
            </button>

            <button
                type="button"
                class="hero-arrow hero-arrow-next"
                @click="next()"
                aria-label="Next slide"
            >
                <span aria-hidden="true">&rarr;</span>
            </button>

            {{-- Slide indicators --}}
            <div class="hero-pagination" aria-label="Choose a slide">
                <template x-for="index in slideCount" :key="index">
                    <button
                        type="button"
                        class="hero-dot"
                        :class="{ 'is-active': activeSlide === index - 1 }"
                        @click="goTo(index - 1)"
                        :aria-label="'Go to slide ' + index"
                        :aria-pressed="activeSlide === index - 1"
                    ></button>
                </template>
            </div>
        </section>


        {{-- New Arrivals --}}
        <section class="section shell">

            <div class="heading-row">

                <div class="section-heading">
                    <p class="eyebrow">JUST LANDED</p>
                    <h2>New to the shelf</h2>
                </div>

                <a
                    href="{{ route('products.new-arrivals') }}"
                    class="text-link"
                >
                    Shop all
                    <x-ui-icon name="arrow" size="16" />
                </a>

            </div>

            <div class="product-grid">

                @foreach($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach

            </div>

        </section>


        {{-- Barrier Edit --}}
        <section class="campaign shell">

            <div class="campaign-copy">

                <p class="eyebrow">THE BARRIER EDIT</p>

                <h2>
                    Less irritation.<br>
                    More resilience.
                </h2>

                <p>
                    A considered routine of ceramides, centella and soothing
                    botanicals for sensitive, stressed skin.
                </p>

                <a
                    href="{{ route('categories.show', 'skincare') }}"
                    class="text-link light-link"
                >
                    Explore the edit
                    <x-ui-icon name="arrow" />
                </a>

            </div>

            <img
                src="https://images.unsplash.com/photo-1650529192647-ce4eb5fb3314?auto=format&fit=crop&w=1000&q=85"
                alt="Barrier care skincare collection"
            >

        </section>


        {{-- Categories --}}
        <section class="section shell">

            <div class="section-heading text-center">
                <p class="eyebrow">START WITH WHAT YOU NEED</p>
                <h2>Shop by category</h2>
            </div>

            <div class="category-grid">

                @php
                    $images = [
                        'https://images.unsplash.com/photo-1613803745799-ba6c10aace85?auto=format&fit=crop&w=650&q=80',
                        'https://images.unsplash.com/photo-1638609927252-9982beed7af8?auto=format&fit=crop&w=650&q=80',
                        'https://images.unsplash.com/photo-1621770522713-8b47c60a6227?auto=format&fit=crop&w=650&q=80',
                        'https://images.unsplash.com/photo-1622910076411-b126ff7e469b?auto=format&fit=crop&w=650&q=80',
                        'https://images.unsplash.com/photo-1650529192647-ce4eb5fb3314?auto=format&fit=crop&w=650&q=80',
                    ];
                @endphp

                @foreach($categories->take(5) as $index => $category)

                    <a
                        href="{{ route('categories.show', $category) }}"
                        class="category-card"
                    >
                        <img
                            src="{{ $images[$index] }}"
                            alt=""
                        >

                        <span>
                        {{ $category->name }}
                    </span>

                        <x-ui-icon name="arrow" />
                    </a>

                @endforeach

            </div>

        </section>


        {{-- Best Sellers --}}
        <section class="section shell">

            <div class="heading-row">

                <div class="section-heading">
                    <p class="eyebrow">COMMUNITY FAVOURITES</p>
                    <h2>The ones you come back for</h2>
                </div>

                <a
                    href="{{ route('products.index') }}"
                    class="text-link"
                >
                    Shop all
                    <x-ui-icon name="arrow" />
                </a>

            </div>

            <div class="product-grid">

                @foreach($bestSellers as $product)
                    <x-product-card :product="$product" />
                @endforeach

            </div>

        </section>


        {{-- Editorial --}}
        <section class="editorial shell">

            <img
                src="https://images.unsplash.com/photo-1674932668403-33398b81c92f?auto=format&fit=crop&w=1000&q=85"
                alt="Woman with healthy skin"
                loading="lazy"
            >

            <div>

                <p class="eyebrow">
                    YOUR SKIN, UNDERSTOOD
                </p>

                <h2>
                    A simpler path to your best skin.
                </h2>

                <p>
                    Thoughtful formulas, honest information and authentic
                    products for routines that work in our climate.
                </p>

                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-dark"
                >
                    Find my routine
                </a>

            </div>

        </section>

    </main>

@endsection

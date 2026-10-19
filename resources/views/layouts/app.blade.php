<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Aranya Skin & Beauty') }}</title>

    <meta
        name="description"
        content="{{ $description ?? 'Authentic skincare and beauty, thoughtfully curated for Bangladesh.' }}"
    >

    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="{{ $title ?? config('app.name', 'Aranya Skin & Beauty') }}">
    <meta
        property="og:description"
        content="{{ $description ?? 'Authentic skincare and beauty, thoughtfully curated for Bangladesh.' }}"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body x-data="storefront">

{{-- Announcement Bar --}}
<div class="announcement">
    <span>Authentic skincare, thoughtfully sourced</span>

    <span class="announcement-center">
            Free delivery in Dhaka over ৳3,000
        </span>

    <a href="{{ route('account.orders') }}">
        Track your order
        <x-ui-icon name="arrow" size="13" />
    </a>
</div>


{{-- Header --}}
<header class="site-header">

    <div class="header-main shell">

        <button
            class="icon-btn mobile-only"
            type="button"
            aria-label="Open menu"
            @click="mobileMenu = true"
        >
            <x-ui-icon name="menu" />
        </button>

        <a href="{{ route('home') }}" class="logo">
            <span>ARANYA</span>
            <small>SKIN & BEAUTY</small>
        </a>


        {{-- Desktop Search --}}
        <form action="{{ route('search') }}" class="desktop-search">
            <x-ui-icon name="search" size="19" />

            <input
                name="q"
                aria-label="Search products"
                placeholder="Search products, brands or concerns"
                value="{{ request('q') }}"
            >

            <button type="submit">
                Search
            </button>
        </form>


        {{-- Header Actions --}}
        <div class="header-actions">

            @auth

                {{-- Authenticated user --}}
                <a
                    href="{{ route('account.dashboard') }}"
                    class="header-action desktop-action"
                >
                    <x-ui-icon name="user" />
                    <span>Account</span>
                </a>

                <a href="{{ route('wishlist.index') }}" class="header-action">
                    <x-ui-icon name="heart" />
                    <span class="desktop-action">Wishlist</span>
                </a>
            @else

                {{-- Guest user --}}
                <a
                    href="{{ route('login') }}"
                    class="header-action"
                >
                    <x-ui-icon name="user" />
                    <span class="desktop-action">Login</span>
                </a>

                <a
                    href="{{ route('register') }}"
                    class="header-action"
                >
                    <x-ui-icon name="user-plus" />
                    <span class="desktop-action">Register</span>
                </a>

            @endauth

            <a
                href="{{ route('cart.index') }}"
                class="header-action"
            >
                <x-ui-icon name="bag" />
                <span class="desktop-action">Cart</span>
            </a>

        </div>

    </div>


    {{-- Main Navigation --}}
    <nav class="main-nav shell" aria-label="Primary navigation">

        <a href="{{ route('home') }}">
            Home
        </a>

        <a href="{{ route('products.index') }}">
            Shop
        </a>

        <a href="{{ route('brands.index') }}">
            Brands
        </a>

        @foreach($navigationCategories as $category)
            @if($category->children->isNotEmpty())

                <div
                    class="relative h-full"
                    x-data="{ open: false }"
                    @mouseenter="open = true"
                    @mouseleave="open = false"
                >
                    <a
                        href="{{ route('categories.show', $category) }}"
                        class="h-full"
                    >
                        {{ $category->name }}

                        <x-ui-icon
                            name="chevron"
                            size="12"
                            class="transition-transform"
                            x-bind:class="{ 'rotate-180': open }"
                        />
                    </a>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition
                        class="absolute left-1/2 top-full z-50 w-64 -translate-x-1/2 border border-[#e5e0d7] bg-white p-4 shadow-xl"
                    >
                        <div class="mb-2">
                            <a
                                href="{{ route('categories.show', $category) }}"
                                class="block px-3 py-2 font-semibold"
                            >
                                All {{ $category->name }}
                            </a>
                        </div>

                        @foreach($category->children as $child)
                            <a
                                href="{{ route('categories.show', $child) }}"
                                class="block px-3 py-2 text-[#6c716b] hover:bg-[#f8f5ee] hover:text-[#213d34]"
                            >
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

            @else

                <a href="{{ route('categories.show', $category) }}">
                    {{ $category->name }}
                </a>

            @endif
        @endforeach


        <a
            class="sale-link"
            href="{{ route('products.sale') }}"
        >
            Sale
        </a>

        <a href="{{ route('products.new-arrivals') }}">
            New Arrivals
        </a>

    </nav>

</header>


{{-- Success Message --}}
@if(session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="fixed right-6 top-6 z-[120] flex max-w-sm items-start gap-4 bg-[#213d34] px-5 py-4 text-white shadow-xl"
        role="status"
    >
        <span class="flex-1">
            {{ session('success') }}
        </span>

        <button
            type="button"
            @click="show = false"
            class="shrink-0 text-white/70 transition hover:text-white"
            aria-label="Dismiss notification"
        >
            <x-ui-icon name="close" size="16" />
        </button>
    </div>
@endif


{{-- Validation Errors --}}
@if($errors->any())
    <div
        class="mx-auto mt-5 max-w-3xl border border-red-300 bg-red-50 p-4 text-red-800"
        role="alert"
    >
        <strong>Please correct the following:</strong>

        <ul class="mt-2 list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- Page Content --}}
@yield('content')


{{-- Benefits --}}
<section class="benefits shell">

    <div>
        <x-ui-icon name="shield" />

        <span>
                <strong>100% authentic</strong>
                <small>Directly sourced, always genuine</small>
        </span>
    </div>

    <div>
        <x-ui-icon name="truck" />

        <span>
                <strong>Nationwide delivery</strong>
                <small>Fast delivery across Bangladesh</small>
            </span>
    </div>

    <div>
        <x-ui-icon name="star" />

        <span>
                <strong>Expertly curated</strong>
                <small>Products selected with intention</small>
            </span>
    </div>

    <div>
        <x-ui-icon name="shield" />

        <span>
                <strong>Secure checkout</strong>
                <small>COD, bKash, Nagad and cards</small>
            </span>
    </div>

</section>


{{-- Newsletter --}}
<section class="newsletter">

    <div>
        <p class="eyebrow">
            THE ARANYA EDIT
        </p>

        <h2>
            Good skin starts with good advice.
        </h2>

        <p>
            Join our community for expert notes, new drops and
            members-only offers.
        </p>
    </div>

    <form
        action="{{ route('newsletter.subscribe') }}"
        method="POST"
    >
        @csrf

        <input
            name="email"
            aria-label="Email address"
            placeholder="Your email address"
            type="email"
            value="{{ old('email') }}"
            autocomplete="email"
            maxlength="255"
            required
        >

        <button
            class="btn btn-dark"
            type="submit"
        >
            Join the list
        </button>


    </form>



</section>


{{-- Footer --}}
<footer>

    <div class="shell footer-grid">

        <div class="footer-brand">

            <a
                href="{{ route('home') }}"
                class="logo logo-light"
            >
                <span>ARANYA</span>
                <small>SKIN & BEAUTY</small>
            </a>

            <p>
                Authentic beauty, considered for Bangladesh.
                Curated formulas, honest guidance and dependable delivery.
            </p>

        </div>


        <div class="footer-col">

            <h3>Shop</h3>

            <a href="{{ route('categories.show', 'skincare') }}">
                Skin Care
            </a>

            <a href="{{ route('products.new-arrivals') }}">
                New Arrivals
            </a>

            <a href="{{ route('products.sale') }}">
                Sale
            </a>

        </div>


        <div class="footer-col">
            <h3>Help</h3>

            <a href="{{ route('pages.contact') }}">
                Contact
            </a>

            <a href="{{ route('pages.faq') }}">
                FAQ
            </a>

            <a href="{{ route('pages.show', ['slug' => 'shipping-policy']) }}">
                Shipping
            </a>

            <a href="{{ route('pages.show', ['slug' => 'refund-policy']) }}">
                Refunds & Returns
            </a>
        </div>

        <div class="footer-col">
            <h3>About</h3>

            <a href="{{ route('pages.show', ['slug' => 'our-story']) }}">
                Our Story
            </a>

            <a href="{{ route('pages.show', ['slug' => 'privacy-policy']) }}">
                Privacy Policy
            </a>
        </div>

    </div>


    <div class="shell footer-bottom">

            <span>
                © {{ date('Y') }} Aranya Skin & Beauty.
            </span>

        <span>
                Cash on Delivery · bKash · Nagad
            </span>

    </div>

</footer>


{{-- Mobile Menu --}}
<template x-if="mobileMenu">
    <div
        class="drawer-layer"
        x-cloak
        @keydown.escape.window="mobileMenu = false"
    >
        <div
            class="drawer-scrim"
            @click="mobileMenu = false"
            aria-hidden="true"
        ></div>

        <aside
            class="mobile-menu"
            role="dialog"
            aria-modal="true"
            aria-label="Mobile navigation"
        >
            <header>
                <a href="{{ route('home') }}" class="logo">
                    <span>ARANYA</span>
                    <small>SKIN & BEAUTY</small>
                </a>

                <button
                    type="button"
                    class="icon-btn"
                    @click="mobileMenu = false"
                    aria-label="Close menu"
                >
                    <x-ui-icon name="close" />
                </button>
            </header>


            <form action="{{ route('search') }}" class="mobile-search">
                <x-ui-icon name="search" size="19" />

                <input
                    name="q"
                    aria-label="Search products"
                    placeholder="Search products, brands or concerns"
                    value="{{ request('q') }}"
                >

                <button type="submit">
                    Search
                </button>
            </form>

            <nav aria-label="Mobile navigation links">
                <a href="{{ route('products.index') }}">
                    Shop
                    <x-ui-icon name="chevron" />
                </a>

                <a href="{{ route('products.new-arrivals') }}">
                    New Arrivals
                    <x-ui-icon name="chevron" />
                </a>

                <a href="{{ route('categories.show', 'skincare') }}">
                    Skin Care
                    <x-ui-icon name="chevron" />
                </a>

                <a href="{{ route('brands.index') }}">
                    Brands
                    <x-ui-icon name="chevron" />
                </a>

                <a href="{{ route('products.sale') }}">
                    Sale
                    <x-ui-icon name="chevron" />
                </a>
            </nav>
        </aside>
    </div>
</template>


{{-- Cart Drawer --}}
<template x-if="cartDrawer">

    <div class="drawer-layer">

        <div
            class="drawer-scrim"
            @click="cartDrawer = false"
        ></div>

        <aside class="cart-drawer">

            <header>

                <div>
                    <h2>Your bag</h2>
                </div>

                <button
                    class="icon-btn"
                    @click="cartDrawer = false"
                    aria-label="Close cart"
                >
                    <x-ui-icon name="close" />
                </button>

            </header>


            <div class="drawer-progress">

                <p>
                    Free delivery in Dhaka over
                    <strong>৳3,000</strong>
                </p>

                <i>
                    <b></b>
                </i>

            </div>


            <div class="flex flex-1 items-center justify-center px-8 text-center text-sm text-[#6c716b]">

                Open your shopping bag to review
                current items and totals.

            </div>


            <div class="p-6">

                <a
                    class="btn btn-dark full"
                    href="{{ route('cart.index') }}"
                >
                    View shopping bag
                    <x-ui-icon name="arrow" />
                </a>

            </div>

        </aside>

    </div>

</template>


{{-- Quick View Modal --}}
<template x-if="quickView">

    <div
        class="modal-layer"
        role="dialog"
        aria-modal="true"
    >

        <div
            class="drawer-scrim"
            @click="quickView = null"
        ></div>


        <div class="quick-modal">

            <button
                class="icon-btn modal-close"
                @click="quickView = null"
                aria-label="Close quick view"
            >
                <x-ui-icon name="close" />
            </button>


            <img class="product-image"
                :src="quickView.image"
                :alt="quickView.name"
            >


            <div>

                <p
                    class="product-brand"
                    x-text="quickView.brand"
                ></p>

                <h2
                    x-text="quickView.name"
                ></h2>

                <div class="detail-price">
                    ৳<span x-text="Number(quickView.price).toLocaleString()"></span>
                </div>

                <p>
                    Authentic skincare selected for visible
                    results and a comfortable daily ritual.
                </p>

                <a
                    class="btn btn-dark full"
                    :href="quickView.url"
                >
                    View full details
                </a>

            </div>

        </div>

    </div>

</template>


@stack('scripts')

</body>
</html>

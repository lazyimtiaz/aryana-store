@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb" aria-label="Breadcrumb">

            <a href="{{ route('home') }}">
                Home
            </a>

            <span>
            <x-ui-icon name="chevron" size="13" />
            {{ $title }}
        </span>

        </nav>


        <div class="catalog-title">

            <div>

                <h1>
                    {{ $title }}
                </h1>

                <p>
                    {{ $description }}
                </p>

            </div>

            <span>
            {{ $products->total() }} products
        </span>

        </div>


        {{-- Catalog Toolbar --}}
        <form method="GET" class="catalog-toolbar">

            <button
                class="btn btn-outline filter-mobile"
                type="button"
                @click="filtersOpen = true"
            >
                Filters
            </button>


            <div class="view-count">
                Showing
                {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                of
                {{ $products->total() }}
            </div>


            {{-- Preserve existing filters --}}
            @foreach(request()->except('sort', 'page') as $key => $value)

                @if(is_array($value))

                    @foreach($value as $entry)

                        <input
                            type="hidden"
                            name="{{ $key }}[]"
                            value="{{ $entry }}"
                        >

                    @endforeach

                @else

                    <input
                        type="hidden"
                        name="{{ $key }}"
                        value="{{ $value }}"
                    >

                @endif

            @endforeach


            <label>
                Sort by

                <select
                    name="sort"
                    onchange="this.form.submit()"
                >
                    <option value="">
                        Featured
                    </option>

                    <option
                        value="newest"
                        @selected(request('sort') === 'newest')
                    >
                        Newest
                    </option>

                    <option
                        value="price_low"
                        @selected(request('sort') === 'price_low')
                    >
                        Price: low to high
                    </option>

                    <option
                        value="price_high"
                        @selected(request('sort') === 'price_high')
                    >
                        Price: high to low
                    </option>
                </select>

            </label>

        </form>

        {{-- Mobile / Tablet Filter Drawer --}}
        <div
            x-cloak
            x-show="filtersOpen"
            x-transition.opacity
            class="drawer-layer filter-mobile-layer"
            @keydown.escape.window="filtersOpen = false"
        >
            {{-- Backdrop --}}
            <div
                class="drawer-scrim"
                @click="filtersOpen = false"
                aria-hidden="true"
            ></div>

            {{-- Drawer panel --}}
            <aside
                class="filter-drawer"
                role="dialog"
                aria-modal="true"
                aria-labelledby="mobile-filter-title"
                @click.stop
            >
                <header>
                    <strong id="mobile-filter-title">Filter products</strong>

                    <button
                        type="button"
                        class="icon-button"
                        @click="filtersOpen = false"
                        aria-label="Close filters"
                    >
                        <x-ui-icon name="close" size="18" />
                    </button>
                </header>

                <div class="mobile-filter-content">
                    <form method="GET" action="{{ url()->current() }}">
                        {{-- Preserve the current sorting option --}}
                        @if(request()->filled('sort'))
                            <input
                                type="hidden"
                                name="sort"
                                value="{{ request('sort') }}"
                            >
                        @endif

                        {{-- Brands --}}
                        <details open>
                            <summary>
                                Brands
                                <x-ui-icon name="plus" size="16" />
                            </summary>

                            <div class="mobile-filter-options">
                                @foreach($brands as $brand)
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="brand[]"
                                            value="{{ $brand->slug }}"
                                            @checked(
                                                in_array(
                                                    $brand->slug,
                                                    (array) request('brand', [])
                                                )
                                            )
                                        >

                                        <span>{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        {{-- Price --}}
                        <details open>
                            <summary>
                                Price
                                <x-ui-icon name="plus" size="16" />
                            </summary>

                            <div class="mobile-filter-price">
                                <input
                                    class="w-full border p-2"
                                    type="number"
                                    name="min_price"
                                    min="0"
                                    step="any"
                                    inputmode="decimal"
                                    placeholder="Min"
                                    aria-label="Minimum price"
                                    value="{{ request('min_price') }}"
                                >

                                <input
                                    class="w-full border p-2"
                                    type="number"
                                    name="max_price"
                                    min="0"
                                    step="any"
                                    inputmode="decimal"
                                    placeholder="Max"
                                    aria-label="Maximum price"
                                    value="{{ request('max_price') }}"
                                >
                            </div>
                        </details>

                        <div class="mobile-filter-actions">
                            <a
                                class="btn btn-outline"
                                href="{{ url()->current() }}"
                            >
                                Clear all
                            </a>

                            <button
                                type="submit"
                                class="btn btn-dark"
                            >
                                Apply filters
                            </button>
                        </div>
                    </form>
                </div>
            </aside>
        </div>



        <div class="catalog-layout">

            {{-- Filters --}}
            <aside class="filters">

                <div class="filter-head">
                    <strong>
                        Filter products
                    </strong>
                </div>


                <form method="GET">

                    <input
                        type="hidden"
                        name="sort"
                        value="{{ request('sort') }}"
                    >


                    {{-- Brands --}}
                    <details open>

                        <summary>
                            Brands
                            <x-ui-icon name="plus" size="16" />
                        </summary>

                        <div>

                            @foreach($brands as $brand)

                                <label>

                                    <input
                                        type="checkbox"
                                        name="brand[]"
                                        value="{{ $brand->slug }}"
                                        @checked(in_array($brand->slug, (array) request('brand', [])))
                                    >

                                    <span>
                                    {{ $brand->name }}
                                </span>

                                </label>

                            @endforeach

                        </div>

                    </details>


                    {{-- Price --}}
                    <details open>

                        <summary>
                            Price
                            <x-ui-icon name="plus" size="16" />
                        </summary>

                        <div class="grid grid-cols-2 gap-2 pb-4">

                            <input
                                class="w-full border p-2"
                                name="min_price"
                                inputmode="numeric"
                                placeholder="Min"
                                value="{{ request('min_price') }}"
                            >

                            <input
                                class="w-full border p-2"
                                name="max_price"
                                inputmode="numeric"
                                placeholder="Max"
                                value="{{ request('max_price') }}"
                            >

                        </div>

                    </details>


                    <button
                        type="submit"
                        class="btn btn-dark full mt-4"
                    >
                        Apply filters
                    </button>

                </form>

            </aside>


            {{-- Products --}}
            <div class="catalog-products">

                @forelse($products as $product)

                    <x-product-card :product="$product" />

                @empty

                    <div class="col-span-full bg-[#f8f5ee] px-8 py-16 text-center">

                        <h2>
                            No products found
                        </h2>

                        <p>
                            Try clearing your filters or searching for another term.
                        </p>

                        <a
                            class="btn btn-dark"
                            href="{{ route('products.index') }}"
                        >
                            Browse all products
                        </a>

                    </div>

                @endforelse


                <div class="paginate">
                    {{ $products->links() }}
                </div>

            </div>

        </div>

    </main>

@endsection

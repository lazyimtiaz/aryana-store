@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
    <main class="min-h-screen bg-[#f8f6f1]">
        <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">

            {{-- Header --}}
            <div class="mb-10">
                <p class="mb-2 text-xs font-medium uppercase tracking-[0.2em] text-[#7b817a]">
                    Saved for later
                </p>

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <h1 class="font-serif text-4xl text-[#213d34]">
                            My Wishlist
                        </h1>

                        <p class="mt-3 max-w-xl text-sm leading-6 text-[#6f746e]">
                            Products you've saved for later.
                        </p>
                    </div>

                    @if($items->isNotEmpty())
                        <span class="text-sm text-[#6f746e]">
                            {{ $items->count() }}
                            {{ Str::plural('item', $items->count()) }}
                        </span>
                    @endif
                </div>
            </div>

            @if($items->isEmpty())
                {{-- Empty wishlist --}}
                <div class="flex min-h-[420px] flex-col items-center justify-center border border-[#dedbd3] bg-white px-6 text-center">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#f1eee7] text-[#213d34]">
                        <x-ui-icon name="heart" size="28" />
                    </div>

                    <h2 class="font-serif text-2xl text-[#213d34]">
                        Your wishlist is empty
                    </h2>

                    <p class="mt-3 max-w-md text-sm leading-6 text-[#6f746e]">
                        Save products you love and come back to them whenever you're ready.
                    </p>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-dark mt-7 inline-flex"
                    >
                        Continue shopping
                    </a>
                </div>
            @else
                {{-- Wishlist products --}}
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach($items as $item)
                        @php
                            $product = $item->product;

                            $image = $product?->primaryImage?->image
                                ? asset('storage/' . $product->primaryImage->image)
                                : 'https://images.unsplash.com/photo-1622910076411-b126ff7e469b?auto=format&fit=crop&w=900&q=80';
                        @endphp

                        @if($product)
                            <article class="product-card">

                                <div class="product-media">
                                    <a href="{{ route('products.show', $product) }}">
                                        <img
                                            src="{{ $image }}"
                                            alt="{{ $product->primaryImage?->alt_text ?? $product->name }}"
                                            loading="lazy"
                                        >
                                    </a>

                                    @if(
                                        $product->sale_price !== null &&
                                        $product->sale_price < $product->regular_price
                                    )
                                        <span class="badge">
                                            -{{ round((1 - ($product->sale_price / $product->regular_price)) * 100) }}%
                                        </span>
                                    @elseif($product->featured)
                                        <span class="badge">Bestseller</span>
                                    @endif

                                    {{-- Remove from wishlist --}}
                                    <form
                                        action="{{ route('wishlist.destroy', $product) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="icon-btn wish"
                                            type="submit"
                                            aria-label="Remove {{ $product->name }} from wishlist"
                                        >
                                            <x-ui-icon
                                                name="heart"
                                                size="18"
                                                class="fill-current"
                                            />
                                        </button>
                                    </form>
                                </div>

                                <div class="product-info">

                                    <p class="product-brand">
                                        {{ $product->brand?->name }}
                                    </p>

                                    <a
                                        href="{{ route('products.show', $product) }}"
                                        class="product-name"
                                    >
                                        {{ $product->name }}
                                    </a>

                                    <div class="rating">
                                        <x-ui-icon
                                            name="star"
                                            size="14"
                                            class="fill-current"
                                        />

                                        <strong>
                                            {{ round($product->approved_reviews_avg_rating ?? 4.8, 1) }}
                                        </strong>

                                        <span>
                                            ({{ $product->approved_reviews_count ?? 0 }})
                                        </span>
                                    </div>

                                    <div class="price">
                                        <strong>
                                            ৳{{ number_format($product->current_price) }}
                                        </strong>

                                        @if(
                                            $product->sale_price !== null &&
                                            $product->sale_price < $product->regular_price
                                        )
                                            <del>
                                                ৳{{ number_format($product->regular_price) }}
                                            </del>
                                        @endif
                                    </div>

                                    {{-- Add to cart --}}
                                    <form
                                        action="{{ route('cart.store') }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="{{ $product->id }}"
                                        >

                                        <button
                                            class="btn btn-dark add-btn"
                                            type="submit"
                                        >
                                            <x-ui-icon name="bag" size="16" />
                                            Add to cart
                                        </button>
                                    </form>

                                </div>
                            </article>
                        @endif
                    @endforeach

                </div>
            @endif

        </section>
    </main>
@endsection

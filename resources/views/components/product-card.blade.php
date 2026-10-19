@props(['product'])

@php
    $productImage = $product->primaryImage?->image;

    $image = $productImage
        ? asset('storage/' . $productImage)
        : 'https://images.unsplash.com/photo-1622910076411-b126ff7e469b?auto=format&fit=crop&w=900&q=80';

    $rating = round($product->approved_reviews_avg_rating ?? 4.8, 1);
@endphp

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
            <span class="badge">
                Bestseller
            </span>
        @endif

        @auth
            <form
                action="{{ route('wishlist.store', $product) }}"
                method="POST"
            >
                @csrf

                <button
                    class="icon-btn wish"
                    type="submit"
                    aria-label="Add {{ $product->name }} to wishlist"
                >
                    <x-ui-icon name="heart" size="18" />
                </button>
            </form>
        @else
            <a
                href="{{ route('login') }}"
                class="icon-btn wish"
                aria-label="Login to add {{ $product->name }} to wishlist"
            >
                <x-ui-icon name="heart" size="18" />
            </a>
        @endauth

        <button
            class="quick-view"
            type="button"
            @click="openQuickView({{ Js::from([
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand?->name,
                'price' => $product->current_price,
                'image' => $image,
                'url' => route('products.show', $product),
            ]) }})"
        >
            Quick view
        </button>

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

            <strong>{{ $rating }}</strong>

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

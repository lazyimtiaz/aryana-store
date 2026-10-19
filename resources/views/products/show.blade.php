@extends('layouts.app')

@section(
    'title',
    $product->meta_title ?: $product->name
)

@section('content')

    @push('head')

        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->name,
                'sku' => $product->sku,

                'brand' => [
                    '@type' => 'Brand',
                    'name' => $product->brand?->name,
                ],

                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'BDT',
                    'price' => $product->current_price,
                    'availability' => $product->stock_quantity
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
                ],
            ], JSON_UNESCAPED_SLASHES) !!}
        </script>

    @endpush

    <main>

        <div class="shell page">

            {{-- Breadcrumb --}}
            <nav class="breadcrumb">

                <a href="{{ route('home') }}">
                    Home
                </a>

                <span>
                    <x-ui-icon name="chevron" size="13" />
                    {{ $product->name }}
                </span>

            </nav>


            {{-- Product Detail --}}
            <div
                class="product-detail"
                x-data="{
                    image: {{ Js::from(
                        $product->images->first()?->image
                            ? asset('storage/' . $product->images->first()->image)
                            : null
                    ) }}
                }"
            >

                {{-- Gallery --}}
                <div class="gallery">

                    <div class="thumbs">

                        @foreach($product->images as $image)

                            @php
                                $imageUrl = $image->image
                                    ? asset('storage/' . $image->image)
                                    : null;
                            @endphp

                            @if($imageUrl)

                                <button
                                    type="button"
                                    @click="image = {{ Js::from($imageUrl) }}"
                                >

                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $image->alt_text ?: $product->name }}"
                                        loading="lazy"
                                    >

                                </button>

                            @endif

                        @endforeach

                    </div>


                    <div class="main-image">

                        @if($product->images->first()?->image)

                            <img
                                :src="image"
                                alt="{{ $product->name }}"
                            >

                        @else

                            <img
                                src="https://images.unsplash.com/photo-1622910076411-b126ff7e469b?auto=format&fit=crop&w=900&q=80"
                                alt="{{ $product->name }}"
                            >

                        @endif

                    </div>

                </div>


                {{-- Product Summary --}}
                <div class="product-summary">

                    <p class="product-brand">
                        {{ $product->brand?->name }}
                    </p>

                    <h1>
                        {{ $product->name }}
                    </h1>


                    {{-- Rating --}}
                    <div class="summary-meta">

                        <div class="rating">

                            <x-ui-icon
                                name="star"
                                size="14"
                            />

                            <strong>
                                {{ number_format($product->approvedReviews->avg('rating') ?: 4.8, 1) }}
                            </strong>

                            <span>
                                ({{ $product->approvedReviews->count() }} reviews)
                            </span>

                        </div>

                        <span>
                            SKU: {{ $product->sku }}
                        </span>

                    </div>


                    {{-- Price --}}
                    <div class="detail-price">

                        ৳{{ number_format($product->current_price) }}

                        @if(
                            $product->sale_price !== null &&
                            $product->sale_price < $product->regular_price
                        )

                            <del>
                                ৳{{ number_format($product->regular_price) }}
                            </del>

                            <span>
                                Save
                                {{ round((1 - $product->sale_price / $product->regular_price) * 100) }}%
                            </span>

                        @endif

                    </div>


                    {{-- Stock --}}
                    <p class="stock">

                        <span></span>

                        {{ $product->stock_quantity
                            ? 'In stock — ready to dispatch'
                            : 'Currently out of stock'
                        }}

                    </p>


                    <p class="short-desc">
                        {{ $product->short_description }}
                    </p>


                    {{-- Delivery --}}
                    <div class="delivery-note">

                        <x-ui-icon name="truck" />

                        <span>

                            <strong>
                                Delivery across Bangladesh
                            </strong>

                            <small>
                                Dhaka 1–2 days · Outside Dhaka 2–4 days
                            </small>

                        </span>

                    </div>


                    {{-- Add to Cart --}}
                    <form
                        action="{{ route('cart.store') }}"
                        method="POST"
                        class="buy-row"
                        x-data="quantity()"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="product_id"
                            value="{{ $product->id }}"
                        >

                        <input
                            type="hidden"
                            name="quantity"
                            :value="value"
                        >

                        <div class="qty">

                            <button
                                type="button"
                                @click="decrement"
                            >
                                <x-ui-icon name="minus" />
                            </button>

                            <span x-text="value"></span>

                            <button
                                type="button"
                                @click="increment"
                            >
                                <x-ui-icon name="plus" />
                            </button>

                        </div>


                        <button
                            class="btn btn-dark grow"
                            type="submit"
                            @disabled(!$product->stock_quantity)
                        >
                            <x-ui-icon name="bag" />
                            Add to cart
                        </button>

                    </form>

                </div>

            </div>


            {{-- Product Tabs --}}
            <section
                class="product-tabs"
                x-data="{ tab: 'description' }"
            >

                <nav>

                    @foreach([
                        'description' => 'Description',
                        'ingredients' => 'Ingredients',
                        'how' => 'How to use',
                        'reviews' => 'Reviews'
                    ] as $key => $label)

                        <button
                            type="button"
                            :class="{ 'active': tab === '{{ $key }}' }"
                            @click="tab = '{{ $key }}'"
                        >
                            {{ $label }}
                        </button>

                    @endforeach

                </nav>


                {{-- Description --}}
                <div x-show="tab === 'description'">

                    <h2>
                        Your daily ritual, refined.
                    </h2>

                    <div class="prose">
                        {!! $product->description !!}

                    </div>

                </div>


                {{-- Ingredients --}}
                <div
                    x-show="tab === 'ingredients'"
                    x-cloak
                >

                    <h2>
                        Ingredients
                    </h2>

                    <p>
                        {{ $product->ingredients }}
                    </p>

                </div>


                {{-- How to use --}}
                <div
                    x-show="tab === 'how'"
                    x-cloak
                >

                    <h2>
                        How to use
                    </h2>

                    <p>
                        {!! $product->how_to_use !!}
                    </p>

                </div>


                {{-- Reviews --}}
                {{-- Reviews --}}
                <div
                    id="reviews"
                    x-show="tab === 'reviews'"
                    x-cloak
                    class="scroll-mt-24"
                >
                    <div class="mb-8">
                        <h2 class="font-serif text-2xl text-[#213d34]">
                            Customer reviews
                        </h2>

                        <p class="mt-2 text-sm text-[#6f746e]">
                            {{ $product->approvedReviews->count() }}
                            {{ Str::plural('review', $product->approvedReviews->count()) }}
                            for {{ $product->name }}.
                        </p>
                    </div>

                    {{-- Approved reviews --}}
                    @forelse($product->approvedReviews as $review)
                        <article class="border-b border-[#dedbd3] py-6 first:pt-0">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-medium text-[#213d34]">
                                        {{ $review->user?->name ?? 'Customer' }}
                                    </p>

                                    <div
                                        class="mt-2 flex items-center gap-1"
                                        aria-label="{{ $review->rating }} out of 5 stars"
                                    >
                                        @for($star = 1; $star <= 5; $star++)
                                            <span
                                                class="{{ $star <= $review->rating ? 'text-[#b38a4c]' : 'text-[#d8d5cd]' }}"
                                            >★</span>
                                        @endfor
                                    </div>
                                </div>

                                <time
                                    class="text-xs text-[#7b817a]"
                                    datetime="{{ $review->created_at->toDateString() }}"
                                >
                                    {{ $review->created_at->format('M d, Y') }}
                                </time>
                            </div>

                            @if($review->title)
                                <h3 class="mt-4 font-medium text-[#213d34]">
                                    {{ $review->title }}
                                </h3>
                            @endif

                            @if($review->comment)
                                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-[#6f746e]">{{ $review->comment }}</p>
                            @endif
                        </article>
                    @empty
                        <div class="border border-[#dedbd3] bg-white px-6 py-8">
                            <h3 class="font-serif text-xl text-[#213d34]">
                                No reviews yet
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#6f746e]">
                                Be the first to share your experience with this product.
                            </p>
                        </div>
                    @endforelse

                    {{-- Review submission --}}
                    <div class="mt-10 border border-[#dedbd3] bg-white p-6 sm:p-8">
                        <h3 class="font-serif text-2xl text-[#213d34]">
                            Write a review
                        </h3>

                        @auth
                            @if($userReview)
                                <div class="mt-5 bg-[#f8f6f1] p-5">
                                    <p class="font-medium text-[#213d34]">
                                        You've already reviewed this product.
                                    </p>

                                    <div class="mt-2 flex items-center gap-1">
                                        @for($star = 1; $star <= 5; $star++)
                                            <span
                                                class="{{ $star <= $userReview->rating ? 'text-[#b38a4c]' : 'text-[#d8d5cd]' }}"
                                            >★</span>
                                        @endfor
                                    </div>

                                    @if(!$userReview->is_approved)
                                        <p class="mt-2 text-sm leading-6 text-[#6f746e]">
                                            Your review is awaiting approval. It will appear publicly once approved.
                                        </p>
                                    @else
                                        <p class="mt-2 text-sm text-[#6f746e]">
                                            Your review has been published.
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p class="mt-2 text-sm leading-6 text-[#6f746e]">
                                    Share your experience to help other customers make informed decisions.
                                    Your review will be visible after approval.
                                </p>

                                @if($errors->has('review'))
                                    <div class="mt-5 border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                                        {{ $errors->first('review') }}
                                    </div>
                                @endif

                                <form
                                    action="{{ route('products.reviews.store', $product) }}"
                                    method="POST"
                                    class="mt-6 space-y-6"
                                >
                                    @csrf

                                    {{-- Rating --}}
                                    <fieldset>
                                        <legend class="mb-3 text-sm font-medium text-[#213d34]">
                                            Your rating <span class="text-red-600">*</span>
                                        </legend>

                                        <div class="flex flex-wrap gap-4">
                                            @for($rating = 1; $rating <= 5; $rating++)
                                                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-[#6f746e]">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="{{ $rating }}"
                                                        class="accent-[#213d34]"
                                                        @checked((string) old('rating') === (string) $rating)
                                                        required
                                                    >

                                                    <span>
                                        {{ $rating }}
                                                        {{ Str::plural('star', $rating) }}
                                    </span>
                                                </label>
                                            @endfor
                                        </div>

                                        @error('rating')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </fieldset>

                                    {{-- Review title --}}
                                    <div>
                                        <label
                                            for="review-title"
                                            class="mb-2 block text-sm font-medium text-[#213d34]"
                                        >
                                            Review title <span class="text-xs font-normal text-[#7b817a]">(optional)</span>
                                        </label>

                                        <input
                                            id="review-title"
                                            type="text"
                                            name="title"
                                            value="{{ old('title') }}"
                                            maxlength="255"
                                            placeholder="Summarize your experience"
                                            class="w-full border border-[#dedbd3] bg-white px-4 py-3 text-sm text-[#213d34] outline-none focus:border-[#213d34]"
                                        >

                                        @error('title')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Review comment --}}
                                    <div>
                                        <label
                                            for="review-comment"
                                            class="mb-2 block text-sm font-medium text-[#213d34]"
                                        >
                                            Your review <span class="text-xs font-normal text-[#7b817a]">(optional)</span>
                                        </label>

                                        <textarea
                                            id="review-comment"
                                            name="comment"
                                            rows="5"
                                            maxlength="3000"
                                            placeholder="What did you like or dislike about this product?"
                                            class="w-full border border-[#dedbd3] bg-white px-4 py-3 text-sm leading-6 text-[#213d34] outline-none focus:border-[#213d34]"
                                        >{{ old('comment') }}</textarea>

                                        @error('comment')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-dark inline-flex"
                                    >
                                        Submit review
                                    </button>
                                </form>
                            @endif
                        @else
                            <p class="mt-3 text-sm leading-6 text-[#6f746e]">
                                Please log in to rate and review this product.
                            </p>

                            <a
                                href="{{ route('login') }}"
                                class="btn btn-dark mt-5 inline-flex"
                            >
                                Log in to review
                            </a>
                        @endauth
                    </div>
                </div>

            </section>

        </div>


        {{-- Related Products --}}
        <section class="section shell">

            <div class="section-heading">

                <p class="eyebrow">
                    PAIR IT WITH
                </p>

                <h2>
                    Frequently bought together
                </h2>

            </div>

            <div class="product-grid">

                @foreach($relatedProducts as $item)

                    <x-product-card :product="$item" />

                @endforeach

            </div>

        </section>

    </main>

@endsection

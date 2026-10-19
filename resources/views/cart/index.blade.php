@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb">

            <a href="{{ route('home') }}">
                Home
            </a>

            <span>
                <x-ui-icon name="chevron" />
                Shopping Bag
            </span>

        </nav>


        <div class="cart-title">

            <h1>
                Your shopping bag
            </h1>

            <span>
                {{ $cart->items->sum('quantity') }} items
            </span>

        </div>


        @php
            $subtotal = $cart->items->sum(
                fn ($item) => $item->unit_price * $item->quantity
            );
        @endphp


        <div class="shipping-progress">

            <div>

                <x-ui-icon name="truck" />

                <span>

                    <strong>
                        {{ $subtotal >= 3000
                            ? 'You unlocked free delivery'
                            : 'You are close to free delivery'
                        }}
                    </strong>

                    <small>
                        Free standard delivery in Dhaka over ৳3,000.
                    </small>

                </span>

            </div>


            <i>
                <b style="width: {{ min(100, $subtotal / 30) }}%"></b>
            </i>

        </div>


        @if($cart->items->isEmpty())

            <section class="bg-[#f8f5ee] px-8 py-20 text-center">

                <h2>
                    Your bag is waiting.
                </h2>

                <p class="text-[#6c716b]">
                    Explore thoughtful skincare and add something you will love.
                </p>

                <a
                    class="btn btn-dark"
                    href="{{ route('products.index') }}"
                >
                    Start shopping
                </a>

            </section>

        @else

            <div class="cart-layout">


                {{-- CART ITEMS --}}

                <section class="cart-items">

                    @foreach($cart->items as $item)

                        @php
                            $productImage = $item->product->primaryImage?->image;

                            $image = $productImage
                                ? asset('storage/' . $productImage)
                                : 'https://images.unsplash.com/photo-1622910076411-b126ff7e469b?auto=format&fit=crop&w=900&q=80';
                        @endphp


                        <article class="cart-item">

                            <img
                                src="{{ $image }}"
                                alt="{{ $item->product->primaryImage?->alt_text ?? $item->product->name }}"
                                loading="lazy"
                            >


                            <div>

                                <p class="product-brand">
                                    {{ $item->product->brand?->name }}
                                </p>


                                <h3>

                                    <a href="{{ route('products.show', $item->product) }}">
                                        {{ $item->product->name }}
                                    </a>

                                </h3>


                                <form
                                    method="POST"
                                    action="{{ route('cart.update', $item) }}"
                                    class="qty small"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="{{ max(1, $item->quantity - 1) }}"
                                    >
                                        <x-ui-icon name="minus" />
                                    </button>


                                    <span>
                                        {{ $item->quantity }}
                                    </span>


                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="{{ $item->quantity + 1 }}"
                                    >
                                        <x-ui-icon name="plus" />
                                    </button>

                                </form>

                            </div>


                            <div class="cart-item-end">

                                <strong>
                                    ৳{{ number_format($item->unit_price * $item->quantity) }}
                                </strong>


                                <form
                                    method="POST"
                                    action="{{ route('cart.destroy', $item) }}"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="text-xs underline"
                                        type="submit"
                                    >
                                        Remove
                                    </button>

                                </form>

                            </div>

                        </article>

                    @endforeach

                </section>


                {{-- ORDER SUMMARY --}}

                <aside class="order-summary">

                    <h2>
                        Order summary
                    </h2>


                    @if($appliedCoupon)
                        <div class="coupon-applied">
                            <div>
                                <p class="font-medium">
                                    Coupon {{ $appliedCoupon->code }} applied
                                </p>

                                <p class="text-sm">
                                    You save ৳{{ number_format($couponDiscount, 2) }}
                                </p>
                            </div>

                            <form
                                action="{{ route('cart.coupon.remove') }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline">
                                    Remove
                                </button>
                            </form>
                        </div>
                    @else
                        <form
                            class="coupon"
                            method="POST"
                            action="{{ route('cart.coupon.apply') }}"
                        >
                            @csrf

                            <input
                                name="coupon_code"
                                value="{{ old('coupon_code') }}"
                                placeholder="Coupon code"
                                maxlength="100"
                                required
                            >

                            <button class="btn btn-outline" type="submit">
                                Apply
                            </button>
                        </form>
                    @endif

                    @error('coupon_code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <dl>
                    <div class="summary-row">
                        <dd>Subtotal</dd>
                        <dt>৳{{ number_format($subtotal, 2) }}</dt>
                    </div>

                    @if($appliedCoupon && $couponDiscount > 0)
                        <div class="summary-row">
                            <dd>Discount ({{ $appliedCoupon->code }})</dd>
                            <dt>-৳{{ number_format($couponDiscount, 2) }}</dt>
                        </div>
                    @endif

                    <div class="summary-row total">
                        <dd>Total before shipping</dd>
                        <dt>৳{{ number_format(max(0, $subtotal - $couponDiscount), 2) }}</dt>
                    </div>
                    </dl>

                    @auth

                        <a
                            href="{{ route('checkout.create') }}"
                            class="btn btn-dark full"
                        >
                            Proceed to checkout

                            <x-ui-icon name="arrow" />

                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="btn btn-dark full"
                        >
                            Sign in to checkout
                        </a>

                    @endauth

                </aside>

            </div>

        @endif

    </main>

@endsection

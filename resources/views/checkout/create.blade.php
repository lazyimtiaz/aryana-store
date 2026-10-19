@extends('layouts.app')

@section('content')

    <div class="checkout-page">

        <header class="checkout-head">

            <a
                href="{{ route('home') }}"
                class="logo"
            >
                <span>ARANYA</span>
                <small>SKIN & BEAUTY</small>
            </a>

            <span>
                <x-ui-icon name="shield" />
                Secure checkout
            </span>

        </header>


        <div
            class="checkout-layout shell"
            x-data="{
                payment: '{{ old('payment_method', 'cod') }}',
                delivery: '{{ old('delivery_method', 'standard') }}',
                subtotal: {{ $subtotal }},
                discount: {{ $couponDiscount }},
                shipping: {
                    standard: 80,
                    express: 150
                },
                get deliveryCost() {
                    return this.shipping[this.delivery];
                },
                get total() {
                    return Math.max(0, this.subtotal - this.discount)
                        + this.deliveryCost;
                }
            }"
        >

            <form
                class="checkout-form"
                action="{{ route('checkout.store') }}"
                method="POST"
            >

                @csrf

                <a
                    href="{{ route('cart.index') }}"
                    class="text-link"
                >
                    Return to bag
                </a>

                <h1>Checkout</h1>


                @if($errors->any())

                    <div class="mb-4 border border-red-300 bg-red-50 p-4 text-red-800">

                        <strong>
                            Please check your information.
                        </strong>

                        <ul class="list-disc pl-5">

                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- STEP 1: CONTACT --}}

                <section>

                    <div class="step-title">
                        <span>1</span>
                        <h2>Contact information</h2>
                    </div>

                    <div class="form-grid">

                        <label class="field">

                            <span>Name</span>

                            <input
                                value="{{ auth()->user()->name }}"
                                disabled
                            >

                        </label>


                        <label class="field">

                            <span>Email address</span>

                            <input
                                type="email"
                                value="{{ auth()->user()->email }}"
                                disabled
                            >

                        </label>


                        <label class="field">

                            <span>Phone number</span>

                            <input
                                value="{{ auth()->user()->phone }}"
                                disabled
                            >

                        </label>

                    </div>


                    <p class="mt-3 text-sm text-gray-500">

                        Need to change your contact information?

                        <a
                            href="{{ route('account.profile') }}"
                            class="text-link"
                        >
                            Edit your profile
                        </a>

                    </p>

                </section>


                {{-- STEP 2: SHIPPING ADDRESS --}}

                <section>

                    <div class="step-title">
                        <span>2</span>
                        <h2>Shipping address</h2>
                    </div>


                    @if($addresses->isEmpty())

                        <div class="border border-gray-200 p-6">

                            <p class="mb-4">
                                You don't have a saved delivery address yet.
                            </p>

                            <a
                                href="{{ route('account.addresses.create') }}"
                                class="btn btn-dark"
                            >
                                Add delivery address
                            </a>

                        </div>

                    @else

                        <div class="choice-list">

                            @foreach($addresses as $address)

                                <label>

                                    <input
                                        type="radio"
                                        name="address_id"
                                        value="{{ $address->id }}"
                                        @checked(
                                            (string) old(
                                                'address_id',
                                                $addresses->firstWhere('is_default', true)?->id
                                                    ?? $addresses->first()->id
                                            ) === (string) $address->id
                                        )
                                    >

                                    <span>

                                        <strong>

                                            {{ $address->label ?: 'Delivery address' }}

                                            @if($address->is_default)
                                                <small>Default</small>
                                            @endif

                                        </strong>


                                        <small>
                                            {{ $address->full_name }}
                                            ·
                                            {{ $address->phone }}
                                        </small>


                                        <small>

                                            {{ $address->address_line }}

                                            @if($address->area)
                                                , {{ $address->area }}
                                            @endif

                                        </small>


                                        <small>

                                            {{ $address->city }},
                                            {{ $address->district }}

                                            @if($address->postal_code)
                                                · {{ $address->postal_code }}
                                            @endif

                                        </small>

                                    </span>

                                </label>

                            @endforeach

                        </div>


                        <div class="mt-4">

                            <a
                                href="{{ route('account.addresses.create') }}"
                                class="text-link"
                            >
                                + Add another address
                            </a>

                        </div>

                    @endif

                </section>


                {{-- STEP 3: DELIVERY --}}

                <section>

                    <div class="step-title">
                        <span>3</span>
                        <h2>Delivery method</h2>
                    </div>


                    <div class="choice-list">

                        <label>

                            <input
                                type="radio"
                                name="delivery_method"
                                value="standard"
                                x-model="delivery"
                            >

                            <span>

                                <strong>
                                    Standard delivery
                                </strong>

                                <small>
                                    1–2 business days inside Dhaka
                                </small>

                            </span>

                            <b>৳80</b>

                        </label>


                        <label>

                            <input
                                type="radio"
                                name="delivery_method"
                                value="express"
                                x-model="delivery"
                            >

                            <span>

                                <strong>
                                    Express delivery
                                </strong>

                                <small>
                                    Same day for selected Dhaka areas
                                </small>

                            </span>

                            <b>৳150</b>

                        </label>

                    </div>

                </section>


                {{-- STEP 4: PAYMENT --}}


                {{-- STEP 4: PAYMENT --}}

                <section>
                    <div class="step-title">
                        <span>4</span>
                        <h2>Payment</h2>
                    </div>

                    <input
                        type="hidden"
                        name="payment_method"
                        :value="payment"
                    >

                    <div class="payment-grid">
                        <button
                            type="button"
                            :class="payment === 'cod' ? 'active' : ''"
                            @click="payment = 'cod'"
                        >
                            Cash on Delivery
                        </button>

                        @if(config('manual-payments.bkash.merchant_number'))
                            <button
                                type="button"
                                :class="payment === 'bkash' ? 'active' : ''"
                                @click="payment = 'bkash'"
                            >
                                bKash
                            </button>
                        @else
                            <button type="button" disabled>
                                bKash — Not configured
                            </button>
                        @endif

                        @if(config('manual-payments.nagad.merchant_number'))
                            <button
                                type="button"
                                :class="payment === 'nagad' ? 'active' : ''"
                                @click="payment = 'nagad'"
                            >
                                Nagad
                            </button>
                        @else
                            <button type="button" disabled>
                                Nagad — Not configured
                            </button>
                        @endif

                        <button type="button" disabled>
                            Card payment — Coming soon
                        </button>
                    </div>

                    <div x-show="payment === 'cod'" x-cloak>
                        <p class="mt-3 text-sm text-gray-500">
                            Pay for your order when it arrives.
                        </p>
                    </div>

                    @if(config('manual-payments.bkash.merchant_number'))
                        <div
                            x-show="payment === 'bkash'"
                            x-cloak
                            class="mt-4 border border-gray-200 p-4"
                        >
                            <h3 class="font-semibold">Pay using bKash</h3>

                            <p class="mt-2 text-sm">
                                Send the exact order total of
                                <strong x-text="'৳' + Number(total).toLocaleString()"></strong>
                                to this merchant number:
                            </p>

                            <p class="mt-2 font-semibold">
                                {{ config('manual-payments.bkash.merchant_number') }}
                            </p>

                            <p class="mt-2 text-sm text-gray-500">
                                Complete your payment first, then enter the transaction ID
                                and the phone number used to make the payment below.
                                Your order will remain unpaid until we verify it.
                            </p>
                        </div>
                    @endif

                    @if(config('manual-payments.nagad.merchant_number'))
                        <div
                            x-show="payment === 'nagad'"
                            x-cloak
                            class="mt-4 border border-gray-200 p-4"
                        >
                            <h3 class="font-semibold">Pay using Nagad</h3>

                            <p class="mt-2 text-sm">
                                Send the exact order total of
                                <strong x-text="'৳' + Number(total).toLocaleString()"></strong>
                                to this merchant number:
                            </p>

                            <p class="mt-2 font-semibold">
                                {{ config('manual-payments.nagad.merchant_number') }}
                            </p>

                            <p class="mt-2 text-sm text-gray-500">
                                Complete your payment first, then enter the transaction ID
                                and the phone number used to make the payment below.
                                Your order will remain unpaid until we verify it.
                            </p>
                        </div>
                    @endif

                    <div
                        x-show="payment === 'bkash' || payment === 'nagad'"
                        x-cloak
                        class="mt-4 space-y-4"
                    >
                        <label class="field">
                            <span>Transaction ID</span>

                            <input
                                type="text"
                                name="payment_reference"
                                value="{{ old('payment_reference') }}"
                                maxlength="255"
                                placeholder="Enter your transaction ID"
                                :required="payment !== 'cod'"
                                :disabled="payment === 'cod'"
                            >
                        </label>

                        <label class="field">
                            <span>Payer phone number</span>

                            <input
                                type="tel"
                                name="payment_payer_number"
                                value="{{ old('payment_payer_number') }}"
                                maxlength="30"
                                placeholder="Phone number used for payment"
                                :required="payment !== 'cod'"
                                :disabled="payment === 'cod'"
                            >
                        </label>
                    </div>
                </section>


                {{-- STEP 5: ORDER NOTE --}}

                <section>

                    <div class="step-title">
                        <span>5</span>
                        <h2>Order note</h2>
                    </div>


                    <label class="field">

                        <span>
                            Delivery instructions (optional)
                        </span>

                        <textarea
                            name="customer_note"
                            rows="3"
                            placeholder="Anything our delivery team should know?"
                        >{{ old('customer_note') }}</textarea>

                    </label>

                </section>


                <button
                    type="submit"
                    class="btn btn-dark full place-order"
                    @disabled($addresses->isEmpty())
                >
                    Place order ·

                    <span
                        x-text="'৳' + Number(total).toLocaleString()"
                    ></span>

                </button>

            </form>


            {{-- ORDER SUMMARY --}}

            <div class="checkout-summary">

                <h2>Your order</h2>


                @foreach($cart->items as $item)

                    @php
                        $productImage = $item->product->primaryImage?->image;

                        $image = $productImage
                            ? asset('storage/' . $productImage)
                            : 'https://images.unsplash.com/photo-1622910076411-b126ff7e469b?auto=format&fit=crop&w=900&q=80';
                    @endphp


                    <div class="checkout-product">

                        <img
                            src="{{ $image }}"
                            alt="{{ $item->product->primaryImage?->alt_text ?? $item->product->name }}"
                            loading="lazy"
                        >

                        <span>

                            <strong>
                                {{ $item->product->name }}
                            </strong>

                            <small>
                                Qty {{ $item->quantity }}
                            </small>

                        </span>

                        <b>
                            ৳{{ number_format($item->unit_price * $item->quantity) }}
                        </b>

                    </div>

                @endforeach


                <dl>
                    <div>
                        <dt>Subtotal</dt>
                        <dd x-text="'৳' + Number(subtotal).toLocaleString()"></dd>
                    </div>

                    @if($appliedCoupon && $couponDiscount > 0)
                        <div>
                            <dt>Discount ({{ $appliedCoupon->code }})</dt>
                            <dd>
                                -৳{{ number_format($couponDiscount, 2) }}
                            </dd>
                        </div>
                    @endif

                    <div>
                        <dt>Delivery</dt>
                        <dd x-text="'৳' + Number(deliveryCost).toLocaleString()"></dd>
                    </div>

                    <div class="total">
                        <dt>Total</dt>
                        <dd x-text="'৳' + Number(total).toLocaleString()"></dd>
                    </div>
                </dl>

            </div>

        </div>

    </div>

@endsection

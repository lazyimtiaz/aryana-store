@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb">

            <a href="{{ route('home') }}">
                Home
            </a>

            <a href="{{ route('account.dashboard') }}">
                Account
            </a>

            <span>
                <x-ui-icon name="chevron" />
                Order #{{ $order->order_number }}
            </span>

        </nav>


        <div class="section-heading">

            <p class="eyebrow">
                ORDER DETAILS
            </p>

            <h1>
                Order #{{ $order->order_number }}
            </h1>

            <p class="section-copy">
                Placed {{ $order->created_at->format('d M Y, g:i A') }}
            </p>

        </div>


        <div class="grid gap-6 lg:grid-cols-[1.4fr_.6fr]">


            {{-- MAIN ORDER CONTENT --}}

            <div class="space-y-6">


                {{-- STATUS --}}

                <section class="panel">

                    <div class="panel-head">

                        <div>

                            <h2>
                                Order status
                            </h2>

                            <p class="text-sm text-[#6c716b]">
                                {{ str($order->order_status)->headline() }}
                            </p>

                        </div>


                        <span class="order-status">
                            {{ str($order->order_status)->headline() }}
                        </span>

                    </div>


                    @php

                        $statusOrder = [
                            'pending' => 0,
                            'confirmed' => 1,
                            'processing' => 2,
                            'packed' => 3,
                            'shipped' => 4,
                            'delivered' => 5,
                        ];

                        $currentPosition =
                            $statusOrder[$order->order_status] ?? 0;

                        $steps = [
                            'confirmed' => 'Confirmed',
                            'processing' => 'Processing',
                            'packed' => 'Packed',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                        ];

                    @endphp


                    <div class="timeline">

                        @foreach($steps as $key => $label)

                            @php

                                $position = $statusOrder[$key];

                                $isDone =
                                    $currentPosition >= $position;

                                $isCurrent =
                                    $order->order_status === $key;

                            @endphp


                            <div class="{{ $isDone ? 'done' : '' }}">

                                <span></span>

                                <p>

                                    <strong>
                                        {{ $label }}
                                    </strong>

                                    <small>

                                        @if($isCurrent)
                                            Current status
                                        @elseif($isDone)
                                            Completed
                                        @else
                                            Pending
                                        @endif

                                    </small>

                                </p>

                            </div>

                        @endforeach

                    </div>

                </section>


                {{-- ITEMS --}}

                <section class="panel">

                    <div class="panel-head">

                        <h2>
                            Items
                        </h2>

                        <span>
                            {{ $order->items->sum('quantity') }} items
                        </span>

                    </div>


                    <div class="space-y-0">

                        @foreach($order->items as $item)

                            @php
                                $productImage = $item->product?->primaryImage?->image;

                                $image = $productImage
                                    ? asset('storage/' . $productImage)
                                    : 'https://images.unsplash.com/photo-1622910076411-b126ff7e469b?auto=format&fit=crop&w=900&q=80';

                                $altText = $item->product?->primaryImage?->alt_text
                                    ?? $item->product_name;
                            @endphp


                            <div class="checkout-product">

                                <img
                                    src="{{ $image }}"
                                    alt="{{ $altText }}"
                                    loading="lazy"
                                >


                                <span>

                                    <strong>
                                        {{ $item->product_name }}
                                    </strong>

                                    <small>
                                        Qty {{ $item->quantity }}
                                    </small>

                                    <small>
                                        ৳{{ number_format($item->unit_price) }}
                                        each
                                    </small>

                                </span>


                                <b>
                                    ৳{{ number_format($item->subtotal) }}
                                </b>

                            </div>

                        @endforeach

                    </div>

                </section>


                {{-- SHIPPING --}}

                <section class="panel">

                    <div class="panel-head">

                        <h2>
                            Delivery address
                        </h2>

                    </div>


                    <div class="space-y-1 text-sm">

                        <strong>
                            {{ $order->customer_name }}
                        </strong>

                        <p>
                            {{ $order->customer_phone }}
                        </p>


                        @if($order->customer_email)

                            <p>
                                {{ $order->customer_email }}
                            </p>

                        @endif


                        <p class="pt-2">
                            {{ $order->shipping_address }}
                        </p>


                        @if($order->shipping_area)

                            <p>
                                {{ $order->shipping_area }}
                            </p>

                        @endif


                        <p>

                            {{ $order->shipping_city }}

                            @if($order->shipping_district)
                                , {{ $order->shipping_district }}
                            @endif

                            @if($order->shipping_postal_code)
                                - {{ $order->shipping_postal_code }}
                            @endif

                        </p>

                    </div>

                </section>


                {{-- ORDER NOTE --}}

                @if($order->customer_notes)

                    <section class="panel">

                        <div class="panel-head">

                            <h2>
                                Order note
                            </h2>

                        </div>


                        <p class="text-sm leading-7 text-[#6c716b]">
                            {{ $order->customer_notes }}
                        </p>

                    </section>

                @endif

            </div>


            {{-- SUMMARY --}}

            <aside class="space-y-6">


                {{-- ORDER SUMMARY --}}

                <section class="panel">

                    <div class="panel-head">

                        <h2>
                            Order summary
                        </h2>

                    </div>


                    <dl class="space-y-4 text-sm">

                        <div class="flex justify-between">

                            <dt>
                                Subtotal
                            </dt>

                            <dd>
                                ৳{{ number_format($order->subtotal) }}
                            </dd>

                        </div>


                        <div class="flex justify-between">

                            <dt>
                                Discount
                            </dt>

                            <dd>
                                − ৳{{ number_format($order->discount) }}
                            </dd>

                        </div>


                        <div class="flex justify-between">

                            <dt>
                                Delivery
                            </dt>

                            <dd>
                                ৳{{ number_format($order->shipping_cost) }}
                            </dd>

                        </div>


                        <div class="flex justify-between border-t pt-4 text-lg font-semibold">

                            <dt>
                                Total
                            </dt>

                            <dd>
                                ৳{{ number_format($order->total) }}
                            </dd>

                        </div>

                    </dl>

                </section>


                {{-- PAYMENT --}}

                <section class="panel">

                    <div class="panel-head">

                        <h2>
                            Payment
                        </h2>

                    </div>


                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between">

                            <span>
                                Method
                            </span>

                            <strong>
                                {{ str($order->payment_method)->headline() }}
                            </strong>

                        </div>


                        <div class="flex justify-between">

                            <span>
                                Status
                            </span>

                            <strong>
                                {{ str($order->payment_status)->headline() }}
                            </strong>

                        </div>

                    </div>

                </section>

            </aside>

        </div>

    </main>

@endsection

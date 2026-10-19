@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb">
            <a href="{{ route('home') }}">
                Home
            </a>

            <span>
            <x-ui-icon name="chevron" />
            Your Orders
        </span>
        </nav>

        <div class="section-heading">
            <p class="eyebrow">MY ACCOUNT</p>

            <h1>Your orders</h1>

            <p class="section-copy">
                View your previous and current orders.
            </p>
        </div>

        <div class="panel">

            <div class="panel-head">
                <h2>Your orders</h2>

                <span>
                {{ $orders->total() }} orders
            </span>
            </div>

            @forelse($orders as $order)

                <a
                    href="{{ route('account.orders.show', $order) }}"
                    class="order-row"
                >

                <span>
                    <small>ORDER</small>

                    <strong>
                        #{{ $order->order_number }}
                    </strong>
                </span>

                    <span>
                    <small>DATE</small>

                    <strong>
                        {{ $order->created_at->format('d M Y') }}
                    </strong>
                </span>

                    <span>
                    <small>TOTAL</small>

                    <strong>
                        ৳{{ number_format($order->total) }}
                    </strong>
                </span>

                    <b>
                        {{ str($order->order_status)->headline() }}
                    </b>

                    <x-ui-icon name="chevron" />

                </a>

            @empty

                <div class="py-12 text-center">

                    <h2>No orders yet.</h2>

                    <p class="mt-2 text-[#6c716b]">
                        You haven't placed an order yet.
                    </p>

                    <a
                        class="btn btn-dark mt-6"
                        href="{{ route('products.index') }}"
                    >
                        Start shopping
                    </a>

                </div>

            @endforelse

            @if($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>

    </main>

@endsection

@extends('layouts.app')

@section('content')

    <main class="success-page shell">

        <div class="success-icon">✓</div>

        <p class="eyebrow">
            ORDER RECEIVED
        </p>

        <h1>
            Thank you, {{ auth()->user()->name }}.
        </h1>

        <p>
            Your order has been received successfully.
        </p>

        <div class="order-number">
            <span>Order number</span>

            <strong>{{ $order->order_number }}</strong>
        </div>

        <div class="success-card">
            @if(in_array($order->payment_method, ['bkash', 'nagad'], true))
                <h2>Payment verification pending</h2>

                <p>
                    Your transaction details have been submitted.
                    We will verify your payment before marking the
                    order as paid.
                </p>

                <div>
                    <span>1</span>
                    <p>
                        <strong>Payment submitted</strong>
                        <small>
                            Transaction ID:
                            {{ $order->payment_reference }}
                        </small>
                    </p>
                </div>

                <div>
                    <span>2</span>
                    <p>
                        <strong>Payment verification</strong>
                        <small>
                            Our team will check your transaction.
                        </small>
                    </p>
                </div>

                <div>
                    <span>3</span>
                    <p>
                        <strong>Order processing</strong>
                        <small>
                            Your order will proceed through our
                            fulfilment process.
                        </small>
                    </p>
                </div>
            @else
                <h2>What happens next?</h2>

                <div>
                    <span>1</span>
                    <p>
                        <strong>Order received</strong>
                        <small>We have received your order.</small>
                    </p>
                </div>

                <div>
                    <span>2</span>
                    <p>
                        <strong>Careful packing</strong>
                        <small>
                            Your products will be verified and packed.
                        </small>
                    </p>
                </div>

                <div>
                    <span>3</span>
                    <p>
                        <strong>Delivery</strong>
                        <small>
                            Pay when your order arrives.
                        </small>
                    </p>
                </div>
            @endif
        </div>

        <div class="hero-actions">
            <a
                href="{{ route('account.orders.show', $order) }}"
                class="btn btn-dark"
            >
                View my order
            </a>

            <a
                href="{{ route('products.index') }}"
                class="btn btn-outline"
            >
                Continue shopping
            </a>
        </div>

    </main>

@endsection

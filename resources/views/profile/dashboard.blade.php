@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb">

            <a href="{{ route('home') }}">
                Home
            </a>

            <span>
                <x-ui-icon name="chevron" />
                Account
            </span>

        </nav>


        <div class="section-heading">

            <p class="eyebrow">MY ACCOUNT</p>

            <h1>
                Welcome back, {{ auth()->user()->name }}
            </h1>

            <p class="section-copy">
                Manage your orders, profile, addresses, and saved products.
            </p>

        </div>


        {{-- Account information --}}
        <div class="panel">

            <div class="panel-head">

                <div>
                    <h2>Account information</h2>

                    <p>
                        Your basic account details.
                    </p>
                </div>

                <a
                    href="{{ route('account.profile') }}"
                    class="btn btn-outline"
                >
                    Edit profile
                </a>

            </div>


            <div class="grid gap-6 md:grid-cols-3">

                <div>
                    <small>NAME</small>

                    <strong>
                        {{ auth()->user()->name }}
                    </strong>
                </div>

                <div>
                    <small>EMAIL</small>

                    <strong>
                        {{ auth()->user()->email }}
                    </strong>
                </div>

                <div>
                    <small>PHONE</small>

                    <strong>
                        {{ auth()->user()->phone ?: 'Not added' }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- Account shortcuts --}}
        <div class="account-cards panel">

            <a href="{{ route('account.orders') }}">

                <x-ui-icon name="bag" />

                <strong>
                    {{ $orders->whereNotIn('status', ['delivered', 'cancelled'])->count() }}
                </strong>

                <span>
                    Active orders
                </span>

            </a>


            <a href="{{ route('account.addresses') }}">

                <x-ui-icon name="shield" />

                <strong>
                    {{ auth()->user()->addresses()->count() }}
                </strong>

                <span>
                    Saved addresses
                </span>

            </a>


            <a href="{{ route('account.profile') }}">

                <x-ui-icon name="user" />

                <strong>
                    Profile
                </strong>

                <span>
                    Account settings
                </span>

            </a>

        </div>


        {{-- Recent orders --}}
        <div class="panel">

            <div class="panel-head">

                <div>
                    <h2>Recent orders</h2>

                    <p>
                        Your latest purchases.
                    </p>
                </div>

                <a href="{{ route('account.orders') }}">
                    View all
                </a>

            </div>


            @forelse($orders as $order)

                <a
                    href="{{ route('account.orders.show', $order) }}"
                    class="order-row"
                >

                    <span>

                        <small>ORDER</small>

                        <strong>
                            #{{ $order->number }}
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
                        {{ str($order->status)->headline() }}
                    </b>


                    <x-ui-icon name="chevron" />

                </a>

            @empty

                <div class="py-10 text-center">

                    <h3>No orders yet.</h3>

                    <p>
                        Your completed purchases will appear here.
                    </p>

                    <a
                        class="btn btn-dark mt-5"
                        href="{{ route('products.index') }}"
                    >
                        Start shopping
                    </a>

                </div>

            @endforelse

        </div>


        {{-- Logout --}}
        <div class="panel">

            <div class="panel-head">

                <div>

                    <h2>Account</h2>

                    <p>
                        Finished shopping for now?
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline"
                    >
                        Log out
                    </button>

                </form>

            </div>

        </div>

    </main>

@endsection

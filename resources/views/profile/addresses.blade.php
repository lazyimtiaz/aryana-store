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
                Addresses
            </span>
        </nav>

        <div class="section-heading">

            <p class="eyebrow">MY ACCOUNT</p>

            <h1>Saved addresses</h1>

            <p class="section-copy">
                Manage the addresses you use for your skincare orders.
            </p>

        </div>

        <div class="panel">

            <div class="panel-head">

                <div>
                    <h2>Saved addresses</h2>

                    <p class="text-sm text-[#6c716b]">
                        Keep your delivery details ready for checkout.
                    </p>
                </div>

                <a
                    href="{{ route('account.addresses.create') }}"
                    class="btn btn-dark"
                >
                    <x-ui-icon name="plus" />
                    Add address
                </a>

            </div>

            <div class="grid gap-4 md:grid-cols-2">

                @forelse($addresses as $address)

                    <article class="address-card">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                @if($address->label)
                                    <span class="eyebrow">
                                        {{ $address->label }}
                                    </span>
                                @endif

                                <h3>
                                    {{ $address->full_name }}
                                </h3>

                            </div>

                            @if($address->is_default)
                                <span>
                                    Default
                                </span>
                            @endif

                        </div>

                        <p>
                            {{ $address->address_line }}

                            @if($address->area)
                                <br>{{ $address->area }}
                            @endif

                            @if($address->city)
                                <br>{{ $address->city }}
                            @endif

                            @if($address->district)
                                <br>{{ $address->district }}
                            @endif

                            @if($address->postal_code)
                                - {{ $address->postal_code }}
                            @endif
                        </p>

                        @if($address->phone)
                            <p>
                                {{ $address->phone }}
                            </p>
                        @endif

                        @if($address->delivery_notes)
                            <p>
                                {{ $address->delivery_notes }}
                            </p>
                        @endif
                        <div class="mt-5 flex flex-wrap gap-4">

                            <a
                                href="{{ route('account.addresses.edit', $address) }}"
                                class="text-sm underline"
                            >
                                Edit
                            </a>

                            @unless($address->is_default)

                                <form
                                    method="POST"
                                    action="{{ route('account.addresses.default', $address) }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="text-sm underline"
                                    >
                                        Make default
                                    </button>

                                </form>

                            @endunless


                            <form
                                method="POST"
                                action="{{ route('account.addresses.destroy', $address) }}"
                                onsubmit="return confirm('Remove this address?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-sm text-red-700 underline"
                                >
                                    Remove
                                </button>

                            </form>

                        </div>

                    </article>

                @empty

                    <div class="py-12 text-center md:col-span-2">

                        <h3>No saved addresses yet.</h3>

                        <p>
                            Add your first delivery address to make checkout faster.
                        </p>

                        <a
                            href="{{ route('account.addresses.create') }}"
                            class="btn btn-dark mt-5"
                        >
                            <x-ui-icon name="plus" />
                            Add your first address
                        </a>

                    </div>

                @endforelse

            </div>

        </div>

    </main>

@endsection

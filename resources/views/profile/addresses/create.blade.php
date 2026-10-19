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

            <a href="{{ route('account.addresses') }}">
                Addresses
            </a>

            <span>
                <x-ui-icon name="chevron" />
                Add address
            </span>

        </nav>


        <div class="section-heading">

            <p class="eyebrow">MY ACCOUNT</p>

            <h1>Add address</h1>

            <p class="section-copy">
                Save a delivery address for faster checkout.
            </p>

        </div>


        <form
            method="POST"
            action="{{ route('account.addresses.store') }}"
            class="panel"
        >

            @csrf

            <div class="form-grid">

                <label class="field">

                    <span>Address label</span>

                    <input
                        name="label"
                        value="{{ old('label') }}"
                        placeholder="Home"
                    >

                </label>


                <label class="field">

                    <span>Full name</span>

                    <input
                        name="full_name"
                        value="{{ old('full_name', auth()->user()->name) }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>Phone</span>

                    <input
                        name="phone"
                        value="{{ old('phone', auth()->user()->phone) }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>City</span>

                    <input
                        name="city"
                        value="{{ old('city') }}"
                        placeholder="Dhaka"
                        required
                    >

                </label>


                <label class="field">

                    <span>District</span>

                    <input
                        name="district"
                        value="{{ old('district') }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>Area</span>

                    <input
                        name="area"
                        value="{{ old('area') }}"
                        placeholder="Mirpur"
                    >

                </label>


                <label class="field span-2">

                    <span>Address</span>

                    <input
                        name="address_line"
                        value="{{ old('address_line') }}"
                        placeholder="House, road, apartment, etc."
                        required
                    >

                </label>


                <label class="field">

                    <span>Postal code</span>

                    <input
                        name="postal_code"
                        value="{{ old('postal_code') }}"
                    >

                </label>


                <label class="field">

                    <span>Delivery notes</span>

                    <input
                        name="delivery_notes"
                        value="{{ old('delivery_notes') }}"
                        placeholder="Optional instructions"
                    >

                </label>

            </div>


            <label class="mt-6 flex items-center gap-3">

                <input
                    type="checkbox"
                    name="is_default"
                    value="1"
                    @checked(old('is_default'))
                >

                <span>
                    Make this my default delivery address
                </span>

            </label>


            <div class="mt-6 flex gap-3">

                <button
                    type="submit"
                    class="btn btn-dark"
                >
                    Save address
                </button>

                <a
                    href="{{ route('account.addresses') }}"
                    class="btn btn-outline"
                >
                    Cancel
                </a>

            </div>

        </form>

    </main>

@endsection

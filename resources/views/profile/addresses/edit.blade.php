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
                Edit address
            </span>

        </nav>


        <div class="section-heading">

            <p class="eyebrow">MY ACCOUNT</p>

            <h1>Edit address</h1>

            <p class="section-copy">
                Update your delivery information.
            </p>

        </div>


        <form
            method="POST"
            action="{{ route('account.addresses.update', $address) }}"
            class="panel"
        >

            @csrf
            @method('PATCH')

            <div class="form-grid">

                <label class="field">

                    <span>Address label</span>

                    <input
                        name="label"
                        value="{{ old('label', $address->label) }}"
                        placeholder="Home"
                    >

                </label>


                <label class="field">

                    <span>Full name</span>

                    <input
                        name="full_name"
                        value="{{ old('full_name', $address->full_name) }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>Phone</span>

                    <input
                        name="phone"
                        value="{{ old('phone', $address->phone) }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>City</span>

                    <input
                        name="city"
                        value="{{ old('city', $address->city) }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>District</span>

                    <input
                        name="district"
                        value="{{ old('district', $address->district) }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>Area</span>

                    <input
                        name="area"
                        value="{{ old('area', $address->area) }}"
                    >

                </label>


                <label class="field span-2">

                    <span>Address</span>

                    <input
                        name="address_line"
                        value="{{ old('address_line', $address->address_line) }}"
                        required
                    >

                </label>


                <label class="field">

                    <span>Postal code</span>

                    <input
                        name="postal_code"
                        value="{{ old('postal_code', $address->postal_code) }}"
                    >

                </label>


                <label class="field">

                    <span>Delivery notes</span>

                    <input
                        name="delivery_notes"
                        value="{{ old('delivery_notes', $address->delivery_notes) }}"
                    >

                </label>

            </div>


            <label class="mt-6 flex items-center gap-3">

                <input
                    type="checkbox"
                    name="is_default"
                    value="1"
                    @checked(old('is_default', $address->is_default))
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
                    Save changes
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

@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb">
            <a href="{{ route('home') }}">
                Home
            </a>

            <span>
                <x-ui-icon name="chevron" />
                Profile
            </span>
        </nav>

        <div class="section-heading">

            <p class="eyebrow">MY ACCOUNT</p>

            <h1>Profile</h1>

            <p class="section-copy">
                Update your personal information.
            </p>

        </div>

        <form
            class="panel"
            method="POST"
            action="{{ route('account.profile.update') }}"
        >

            @csrf
            @method('PATCH')

            <h2>Profile information</h2>

            <div class="form-grid">

                <label class="field">

                    <span>Full name</span>

                    <input
                        name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        required
                    >

                </label>

                <label class="field">

                    <span>Phone</span>

                    <input
                        name="phone"
                        value="{{ old('phone', auth()->user()->phone) }}"
                    >

                </label>

                <label class="field span-2">

                    <span>Email</span>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required
                    >

                </label>

            </div>

            <button
                class="btn btn-dark mt-6"
                type="submit"
            >
                Save changes
            </button>

        </form>

    </main>

@endsection

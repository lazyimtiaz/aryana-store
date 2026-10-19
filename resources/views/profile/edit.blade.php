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
                Profile
            </span>
        </nav>

        <div class="section-heading">

            <p class="eyebrow">MY ACCOUNT</p>

            <h1>Profile settings</h1>

            <p class="section-copy">
                Manage your account information, password, and account settings.
            </p>

        </div>

        <div class="space-y-6">

            <div class="panel">

                @include('profile.partials.update-profile-information-form')

            </div>

            <div class="panel">

                @include('profile.partials.update-password-form')

            </div>

            <div class="panel">

                @include('profile.partials.delete-user-form')

            </div>

        </div>

    </main>

@endsection

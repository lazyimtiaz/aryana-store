
<x-guest-layout>
    <div class="auth-heading">
        <span class="auth-eyebrow">WELCOME TO ARANYA</span>
        <h2>Make yourself at home.</h2>
        <p>Create an account for a more personal shopping experience.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="name" :value="__('Full name')" class="auth-label" />

            <x-text-input
                id="name"
                class="auth-input"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Your full name"
            />

            <x-input-error :messages="$errors->get('name')" class="auth-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="email" :value="__('Email address')" class="auth-label" />

            <x-text-input
                id="email"
                class="auth-input"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="you@example.com"
            />

            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" class="auth-label" />

            <x-text-input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Create a password"
            />

            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div class="auth-field">
            <x-input-label
                for="password_confirmation"
                :value="__('Confirm password')"
                class="auth-label"
            />

            <x-text-input
                id="password_confirmation"
                class="auth-input"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Enter your password again"
            />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="auth-error"
            />
        </div>

        <button type="submit" class="auth-submit">
            <span>{{ __('Create account') }}</span>
            <x-ui-icon name="user-plus" size="19" />
        </button>
    </form>

    <div class="auth-divider">
        <span>ALREADY A MEMBER?</span>
    </div>

    <p class="auth-switch">
        Already have an account?
        <a href="{{ route('login') }}">Sign in</a>
    </p>
</x-guest-layout>

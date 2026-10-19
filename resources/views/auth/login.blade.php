<x-guest-layout>
    <div class="auth-heading">
        <span class="auth-eyebrow">WELCOME BACK</span>
        <h2>Good to see you.</h2>
        <p>Sign in to continue your beauty journey.</p>
    </div>

    <x-auth-session-status
        class="auth-status"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="email" :value="__('Email address')" class="auth-label" />

            <x-text-input
                id="email"
                class="auth-input"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
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
                autocomplete="current-password"
                placeholder="Enter your password"
            />

            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div class="auth-form-options">
            <label for="remember_me" class="auth-checkbox-label">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="auth-checkbox"
                >
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-inline-link" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="auth-submit">
            <span>{{ __('Sign in') }}</span>
            <x-ui-icon name="arrow" size="19" />
        </button>
    </form>

    <div class="auth-divider">
        <span>NEW TO ARANYA?</span>
    </div>

    <p class="auth-switch">
        Don't have an account?
        <a href="{{ route('register') }}">Create an account</a>
    </p>
</x-guest-layout>

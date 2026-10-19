
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ARANYA') }} — Your beauty journey</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
<main class="auth-shell">
    <section class="auth-main">
        <a href="{{ route('home') }}" class="auth-brand" aria-label="ARANYA home">
                <span class="auth-brand-mark">
                    <x-ui-icon name="user" size="25" />
                </span>
            <span class="auth-brand-copy">
                    <span class="auth-brand-name">ARANYA</span>
                    <span class="auth-brand-tagline">SKIN &amp; BEAUTY</span>
                </span>
        </a>

        <div class="auth-card">
            {{ $slot }}
        </div>

        <a href="{{ route('home') }}" class="auth-back-link">
            <span aria-hidden="true">&larr;</span>
            Back to the store
        </a>

        <p class="auth-copyright">
            &copy; {{ date('Y') }} ARANYA Skin &amp; Beauty
        </p>
    </section>


</main>
</body>
</html>

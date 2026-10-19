<x-layouts.app :title="$title ?? 'My Account'">
    <main class="shell page">
        <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a><span><x-icon name="chevron" />My account</span></nav>
        <div class="account-header"><p class="eyebrow">MY ACCOUNT</p><h1>Good afternoon, {{ str(auth()->user()->name)->before(' ') }}.</h1><p>Manage your orders, addresses and personal details.</p></div>
        <div class="account-layout">
            <aside>
                <a class="{{ request()->routeIs('account.dashboard') ? 'active' : '' }}" href="{{ route('account.dashboard') }}">Overview <x-icon name="chevron" /></a>
                <a class="{{ request()->routeIs('account.orders.*') ? 'active' : '' }}" href="{{ route('account.orders.index') }}">Orders <x-icon name="chevron" /></a>
                <a href="{{ route('wishlist') }}">Wishlist <x-icon name="chevron" /></a>
                <a class="{{ request()->routeIs('account.addresses') ? 'active' : '' }}" href="{{ route('account.addresses') }}">Addresses <x-icon name="chevron" /></a>
                <a class="{{ request()->routeIs('account.profile*') ? 'active' : '' }}" href="{{ route('account.profile') }}">Profile <x-icon name="chevron" /></a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="w-full px-3 py-4 text-left text-sm text-[#6c716b]">Logout</button></form>
            </aside>
            <section class="account-content">{{ $slot }}</section>
        </div>
    </main>
</x-layouts.app>

@php
    $content = [
        'about' => [
            'Beauty should feel honest.',
            'Aranya was founded to make authentic, effective beauty easier to discover in Bangladesh.',
        ],
        'contact' => [
            'We are here to help.',
            'Product questions, order updates or routine advice—our care team would love to hear from you.',
        ],
        'faq' => [
            'Questions, answered.',
            'Everything you need to know about orders, authenticity, delivery and returns.',
        ],
        'shipping' => [
            'Shipping & Delivery',
            'Clear timelines and dependable delivery, wherever you are in Bangladesh.',
        ],
        'returns' => [
            'Returns & Refunds',
            'Our policy is designed to be fair, transparent and easy to understand.',
        ],
        'privacy' => [
            'Privacy Policy',
            'How we collect, use and protect your personal information.',
        ],
        'terms' => [
            'Terms & Conditions',
            'The terms that apply when you browse and shop with Aranya.',
        ],
    ][$page];
@endphp

@extends('layouts.app')

@section('content')

    <main>

        <section class="content-hero">

            <div class="shell">

                <nav class="breadcrumb">

                    <a href="{{ route('home') }}">
                        Home
                    </a>

                    <span>
                    <x-ui-icon name="chevron" />
                    {{ $content[0] }}
                </span>

                </nav>

                <p class="eyebrow">
                    ARANYA CARE
                </p>

                <h1>
                    {{ $content[0] }}
                </h1>

                <p>
                    {{ $content[1] }}
                </p>

            </div>

        </section>

        <div class="shell content-layout">

            <aside>
                <strong>On this page</strong>

                <a href="#approach">
                    Our approach
                </a>

                <a href="#promise">
                    What we promise
                </a>

                <a href="#help">
                    Helpful information
                </a>
            </aside>

            <article x-data="{ open: 0 }">

                <h2 id="approach">
                    {{ $page === 'about'
                        ? 'Born from a belief in better beauty.'
                        : 'Everything you need to know' }}
                </h2>

                <p>
                    We believe skincare should be easier to understand and safer to shop.
                    Every product is selected with care, sourced through trusted partners
                    and stored with attention to quality.
                </p>

                @foreach([
                    'Orders',
                    'Shipping',
                    'Payment',
                    'Products & authenticity'
                ] as $index => $heading)

                    <div class="border-b border-[#deddd7]">

                        <button
                            class="flex w-full items-center justify-between py-5 text-left font-serif text-xl"
                            type="button"
                            @click="open = open === {{ $index }} ? -1 : {{ $index }}"
                        >
                            {{ $heading }}

                            <x-ui-icon name="plus" />
                        </button>

                        <p
                            x-show="open === {{ $index }}"
                            x-transition
                        >
                            Orders are carefully verified before dispatch.
                            Our customer care team is available to answer questions
                            and help you make an informed choice.
                        </p>

                    </div>

                @endforeach

            </article>

        </div>

    </main>

@endsection

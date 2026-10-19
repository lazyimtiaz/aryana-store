@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb">

            <a href="{{ route('home') }}">
                Home
            </a>

            <span>
            <x-ui-icon name="chevron" />
            Brands
        </span>

        </nav>


        <div class="section-heading">

            <p class="eyebrow">
                OUR BRAND DIRECTORY
            </p>

            <h1>
                Find your favourites
            </h1>

            <p class="section-copy">
                Authentic, globally loved beauty—carefully sourced and verified.
            </p>

        </div>


        <div class="featured-brands">

            @foreach($featuredBrands as $brand)

                <a href="{{ route('brands.show', $brand) }}">

                    {{ $brand->name }}

                    <small>
                        Explore brand
                    </small>

                </a>

            @endforeach

        </div>


        @foreach($brands as $letter => $group)

            <h2 class="list-title">
                {{ $letter }}
            </h2>

            <div class="brand-list">

                @foreach($group as $brand)

                    <a href="{{ route('brands.show', $brand) }}">

                        {{ $brand->name }}

                        <x-ui-icon name="chevron" />

                    </a>

                @endforeach

            </div>

        @endforeach

    </main>

@endsection

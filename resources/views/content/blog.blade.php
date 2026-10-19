@extends('layouts.app')

@section('content')

    <main class="shell page">

        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>

            <span>
            <x-ui-icon name="chevron" />
            Beauty Journal
        </span>
        </nav>

        <div class="section-heading">

            <p class="eyebrow">
                THE ARANYA JOURNAL
            </p>

            <h1>
                Ideas for skin that feels like yours
            </h1>

            <p class="section-copy">
                Ingredient deep-dives, expert routines and honest conversations about beauty.
            </p>

        </div>

        <div class="article-grid">

            @foreach($posts as $post)

                <article>

                    <img
                        src="{{ $post->featured_image }}"
                        alt=""
                    >

                    <span>SKIN EDUCATION</span>

                    <h3>
                        {{ $post->title }}
                    </h3>

                    <p>
                        {{ $post->excerpt }}
                    </p>

                    <a href="{{ url('/blog/' . $post->slug) }}">
                        Read article
                    </a>

                </article>

            @endforeach

        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>

    </main>

@endsection

@extends('layouts.app')

@section('content')

    @push('head')
        <meta
            name="description"
            content="{{ $post->seo_description ?: $post->excerpt }}"
        >
    @endpush

    <main class="shell page">

        <nav class="breadcrumb">
            <a href="{{ url('/blog') }}">Beauty Journal</a>

            <span>
            <x-ui-icon name="chevron" />
            {{ $post->title }}
        </span>
        </nav>

        <article class="mx-auto max-w-3xl">

            <p class="eyebrow">
                SKIN EDUCATION · {{ $post->published_at->format('d M Y') }}
            </p>

            <h1>{{ $post->title }}</h1>

            <p class="text-lg text-[#6c716b]">
                {{ $post->excerpt }}
            </p>

            @if($post->featured_image)
                <img
                    class="my-10 aspect-[16/9] w-full object-cover"
                    src="{{ $post->featured_image }}"
                    alt=""
                >
            @endif

            <div class="prose max-w-none leading-8 text-[#4f554f]">
                {!! nl2br(e($post->body)) !!}
            </div>

        </article>

    </main>

@endsection

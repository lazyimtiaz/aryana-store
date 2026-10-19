@extends('layouts.app')

@section('content')
    <article class="shell py-12 md:py-16">
        <div class="mx-auto max-w-3xl">
            <p class="eyebrow">ARANYA SKIN & BEAUTY</p>

            <h1 class="mt-3 text-3xl font-semibold text-[#213d34] md:text-4xl">
                {{ $page->title }}
            </h1>

            @if($page->excerpt)
                <p class="mt-4 text-lg leading-7 text-[#6c716b]">
                    {{ $page->excerpt }}
                </p>
            @endif

            <div class="mt-8 border-t border-[#e5e0d7] pt-8">
                <div class="leading-8 text-[#424a43]">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </article>
@endsection

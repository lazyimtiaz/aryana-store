@extends('layouts.app')

@section('content')
    <section class="shell py-12 md:py-16">
        <div class="mx-auto max-w-3xl">
            <p class="eyebrow">HELP & SUPPORT</p>

            <h1 class="mt-3 text-3xl font-semibold text-[#213d34] md:text-4xl">
                Frequently Asked Questions
            </h1>

            <p class="mt-4 leading-7 text-[#6c716b]">
                Find answers to common questions about our products,
                orders, delivery, and shopping with ARANYA.
            </p>

            @if($faqs->isNotEmpty())
                <div class="mt-10 space-y-4">
                    @foreach($faqs as $faq)
                        <article
                            class="border-b border-[#e5e0d7] py-5"
                            @if($faq->category)
                                aria-label="{{ $faq->category }}"
                            @endif
                        >
                            <h2 class="text-lg font-medium text-[#213d34]">
                                {{ $faq->question }}
                            </h2>

                            <div class="mt-3 leading-7 text-[#6c716b]">
                                {!! $faq->answer !!}
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="mt-10 border border-[#e5e0d7] p-6 text-[#6c716b]">
                    Our FAQs are being prepared. Please contact us if you
                    need help with an order or product.
                </p>
            @endif

            <div class="mt-10">
                <p class="text-[#6c716b]">
                    Still have a question?
                    <a
                        href="{{ route('pages.contact') }}"
                        class="font-medium text-[#213d34] underline underline-offset-4"
                    >
                        Contact our team
                    </a>.
                </p>
            </div>
        </div>
    </section>
@endsection

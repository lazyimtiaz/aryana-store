@extends('layouts.app')

@section('content')
    <section class="shell py-12 md:py-16">
        <div class="mx-auto max-w-3xl">
            <p class="eyebrow">WE'RE HERE TO HELP</p>

            <h1 class="mt-3 text-3xl font-semibold text-[#213d34] md:text-4xl">
                Contact Us
            </h1>

            <p class="mt-4 leading-7 text-[#6c716b]">
                {{ $settings?->contact_intro
                    ?: 'Have a question about a product, an order, or delivery? Get in touch with the ARANYA team.' }}
            </p>

            <div class="mt-10 grid gap-5 sm:grid-cols-2">
                @if($settings?->contact_email)
                    <div class="border border-[#e5e0d7] p-6">
                        <h2 class="font-semibold text-[#213d34]">
                            Email
                        </h2>

                        <a
                            href="mailto:{{ $settings->contact_email }}"
                            class="mt-2 inline-block break-words text-[#6c716b] underline underline-offset-4"
                        >
                            {{ $settings->contact_email }}
                        </a>
                    </div>
                @endif

                @if($settings?->contact_phone)
                    <div class="border border-[#e5e0d7] p-6">
                        <h2 class="font-semibold text-[#213d34]">
                            Phone
                        </h2>

                        <a
                            href="tel:{{ $settings->contact_phone }}"
                            class="mt-2 inline-block text-[#6c716b] underline underline-offset-4"
                        >
                            {{ $settings->contact_phone }}
                        </a>
                    </div>
                @endif

                @if($settings?->whatsapp_number)
                    <div class="border border-[#e5e0d7] p-6">
                        <h2 class="font-semibold text-[#213d34]">
                            WhatsApp
                        </h2>

                        <a
                            href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-2 inline-block text-[#6c716b] underline underline-offset-4"
                        >
                            Message us on WhatsApp
                        </a>
                    </div>
                @endif

                @if($settings?->address)
                    <div class="border border-[#e5e0d7] p-6">
                        <h2 class="font-semibold text-[#213d34]">
                            Address
                        </h2>

                        <p class="mt-2 whitespace-pre-line leading-7 text-[#6c716b]">
                            {{ $settings->address }}
                        </p>
                    </div>
                @endif

                @if($settings?->business_hours)
                    <div class="border border-[#e5e0d7] p-6 sm:col-span-2">
                        <h2 class="font-semibold text-[#213d34]">
                            Business Hours
                        </h2>

                        <p class="mt-2 whitespace-pre-line leading-7 text-[#6c716b]">
                            {{ $settings->business_hours }}
                        </p>
                    </div>
                @endif
            </div>

            @if(!$settings || (
                !$settings->contact_email &&
                !$settings->contact_phone &&
                !$settings->whatsapp_number &&
                !$settings->address
            ))
                <p class="mt-8 border border-[#e5e0d7] p-6 text-[#6c716b]">
                    Contact information will be available here soon.
                    Please check back later.
                </p>
            @endif

            <div class="mt-10">
                <a
                    href="{{ route('pages.faq') }}"
                    class="font-medium text-[#213d34] underline underline-offset-4"
                >
                    Browse frequently asked questions
                </a>
            </div>
        </div>
    </section>
@endsection

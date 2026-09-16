@extends('frontend.layouts.app')

@section('title', 'ShopNest – Contact Us')

@section('content')

<div class="page-hero page-hero--contact">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb-custom">
                <li>
                    <a href="{{ route('frontend.home') }}">Home</a>
                </li>
                <li>Contact Us</li>
            </ol>
        </nav>

    <h1 class="page-hero-title">Say Hello 👋</h1>

    <p class="page-hero-sub">
        We'd love to hear from you. Choose your preferred way to reach us.
    </p>
</div>

</div>

<section class="section-pad">
    <div class="container">
    <div class="contact-cards-grid">

        {{-- WhatsApp --}}
        @if(!empty($whatsapp))
            @php
                $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsapp);
                $whatsappUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode('Hi ShopNest! I need help.');
            @endphp

            <a href="{{ $whatsappUrl }}"
               target="_blank"
               rel="noopener noreferrer"
               class="contact-card contact-card--whatsapp">

                <div class="contact-card-icon">
                    <i class="bi bi-whatsapp"></i>
                </div>

                <div class="contact-card-body">
                    <h4>WhatsApp</h4>

                    <p>
                        Chat with us instantly on WhatsApp.
                        Quick replies, every day.
                    </p>

                    <span class="contact-card-action">
                        Open WhatsApp
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </div>

            </a>
        @endif


        {{-- Telegram --}}
        @if(!empty($telegram))

            <a href="{{ $telegram }}"
               target="_blank"
               rel="noopener noreferrer"
               class="contact-card contact-card--telegram">

                <div class="contact-card-icon">
                    <i class="bi bi-telegram"></i>
                </div>

                <div class="contact-card-body">
                    <h4>Telegram</h4>

                    <p>
                        Reach us via Telegram for orders,
                        inquiries and updates.
                    </p>

                    <span class="contact-card-action">
                        Open Telegram
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </div>

            </a>

        @endif


        {{-- Phone Call --}}
        @if(!empty($phone))

            @php
                $phoneNumber = preg_replace('/[^0-9+]/', '', $phone);
            @endphp

            <a href="tel:{{ $phoneNumber }}"
               class="contact-card contact-card--phone">

                <div class="contact-card-icon">
                    <i class="bi bi-telephone-fill"></i>
                </div>

                <div class="contact-card-body">
                    <h4>Phone Call</h4>

                    <p>
                        Prefer to talk? Give us a call
                        during business hours.
                    </p>

                    <span class="contact-card-action">
                        Call Us Now
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </div>

            </a>

        @endif

    </div>


    {{-- Contact Information --}}
    <div class="contact-info-block">

        <div class="row g-4">

            {{-- Business Hours --}}
            <div class="col-12 col-md-4">

                <div class="info-item">

                    <i class="bi bi-clock-fill"></i>

                    <div>

                        <h5>Business Hours</h5>

                        <p>
                            Mon – Fri: 9am – 6pm<br>
                            Sat: 10am – 4pm<br>
                            Sun: Closed
                        </p>

                    </div>

                </div>

            </div>


            {{-- Location --}}
            <div class="col-12 col-md-4">

                <div class="info-item">

                    <i class="bi bi-geo-alt-fill"></i>

                    <div>

                        <h5>Our Location</h5>

                        <p>
                            Kuching, Sarawak<br>
                            Malaysia
                        </p>

                    </div>

                </div>

            </div>


            {{-- Email --}}
            <div class="col-12 col-md-4">

                <div class="info-item">

                    <i class="bi bi-envelope-fill"></i>

                    <div>

                        <h5>Email Us</h5>

                        <p>
                            hello@shopnest.my<br>
                            We reply within 24 hours
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</section>

@endsection

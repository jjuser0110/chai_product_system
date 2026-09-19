@extends('frontend.layouts.app')

@section('title', 'Tsuki – Home')

@section('content')

    <!-- HERO BANNER SLIDER -->
    <section class="hero-slider">
        <div
            id="heroBannerSlider"
            class="carousel slide"
            data-bs-ride="carousel"
            data-bs-interval="4000"
        >

            {{-- Indicators --}}
            <div class="carousel-indicators">

                @foreach($banners as $index => $banner)

                    <button
                        type="button"
                        data-bs-target="#heroBannerSlider"
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}"
                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}"
                    ></button>

                @endforeach

            </div>

            {{-- Slides --}}
            <div class="carousel-inner">

                @forelse($banners as $index => $banner)

                    @php
                        $attachment = $banner->file_attachments->first();

                        $bannerImage = $attachment
                            ? asset('storage/' . ltrim($attachment->file_path, '/'))
                            : asset('images/default-banner.jpg');
                    @endphp

                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">

                        <div
                            class="banner-slide"
                            style="background-image: url('{{ $bannerImage }}');"
                        >

                            <div class="banner-content">

                                @if($banner->box_wording)
                                    <span class="banner-tag">
                                        {{ $banner->box_wording }}
                                    </span>
                                @endif

                                @if($banner->title)
                                    <h2>
                                        {!! nl2br(e($banner->title)) !!}
                                    </h2>
                                @endif

                                @if($banner->description)
                                    <p>
                                        {{ $banner->description }}
                                    </p>
                                @endif

                                @if($banner->button_link)

                                    <a
                                        href="{{ $banner->button_link }}"
                                        class="btn-banner"
                                    >
                                        {{ $banner->button_text ?: 'View More' }}
                                        <i class="bi bi-arrow-right"></i>
                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    {{-- Default banner when there are no banners --}}
                    <div class="carousel-item active">

                        <div class="banner-slide banner-1">

                            <div class="banner-content">

                                <span class="banner-tag">
                                    Welcome
                                </span>

                                <h2>
                                    Welcome to<br>
                                    Tsuki
                                </h2>

                                <p>
                                    Discover our girls
                                </p>

                            </div>

                        </div>

                    </div>

                @endforelse

            </div>

            {{-- Previous --}}
            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#heroBannerSlider"
                data-bs-slide="prev"
            >
                <span class="carousel-control-prev-icon"></span>
            </button>

            {{-- Next --}}
            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#heroBannerSlider"
                data-bs-slide="next"
            >
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </section>


    <!-- ABOUT US SECTION -->
    <section class="about-section">

        <div class="container">

            <div class="row align-items-center g-4">

                <div class="col-12 col-md-5">

                    <div class="about-img-wrap">

                        <div class="about-img-blob"></div>

                        <div class="about-icon-grid">

                            <div class="about-icon-card">
                                <i class="bi bi-shield-check"></i>
                                <span>Trusted</span>
                            </div>

                            <div class="about-icon-card">
                                <i class="bi bi-truck"></i>
                                <span>Fast Delivery</span>
                            </div>

                            <div class="about-icon-card">
                                <i class="bi bi-award"></i>
                                <span>Quality</span>
                            </div>

                            <div class="about-icon-card">
                                <i class="bi bi-headset"></i>
                                <span>24/7 Support</span>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-12 col-md-7">

                    <div class="about-text">

                        <span class="section-tag" id="aboutTag">
                            Who We Are
                        </span>

                        <h2 class="section-title" id="aboutTitle">
                            Your Trusted
                            <span class="accent" id="aboutAcc">Online Store</span>
                        </h2>

                        <p id="aboutP1">
                            At Tsuki, we believe great girls should be accessible
                            to everyone. Founded with a passion for quality and customer
                            satisfaction, we bring you a carefully curated selection of
                            items across every category — from everyday essentials to
                            unique finds.
                        </p>

                        <p id="aboutP2">
                            Our team hand-picks every girls to ensure it meets our high
                            standards. With fast shipping, easy returns, and dedicated
                            support, shopping with us is always a pleasure.
                        </p>

                        <div class="about-stats">

                            <div class="stat">
                                <span class="stat-num">500+</span>
                                <span class="stat-label" id="statProducts">Girls</span>
                            </div>

                            <div class="stat">
                                <span class="stat-num">10K+</span>
                                <span class="stat-label" id="statCustomers">Happy Customers</span>
                            </div>

                            <div class="stat">
                                <span class="stat-num">5★</span>
                                <span class="stat-label" id="statRating">Rating</span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- HIGHLIGHT GIRLS -->
    <section class="highlight-section">

        <div class="container">

            <div class="section-header">

                <span class="section-tag" id="hlTag">
                    Featured
                </span>

                <h2 class="section-title" id="hlTitle">
                    Highlight
                    <span class="accent" id="hlAcc">Girls</span>
                </h2>

                <a
                    href="{{ route('frontend.highlights') }}"
                    class="view-all-btn"
                    id="viewAll"
                >
                    View All
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>


        <div class="highlight-track-wrap">

            <div
                class="highlight-track"
                id="highlightTrack" style="height:300px"
            >

                @php
                    // Duplicate the list so the track can loop seamlessly,
                    // matching the marquee effect used on the static site
                    // (app.js does `[...highlights, ...highlights]`).
                    $highlightLoop = $highlights->count()
                        ? $highlights->concat($highlights)
                        : $highlights;
                @endphp

                @forelse($highlightLoop as $product)

                    @php
                        $attachment = $product->file_attachments->first();

                        $productImage = $attachment
                            ? asset('storage/' . ltrim($attachment->file_path, '/'))
                            : null;

                        $hasPrice = !empty($product->price);
                    @endphp

                    <a
                        href="{{ route('frontend.product', $product->id) }}"
                        class="highlight-card"
                    >

                        <div class="highlight-card-img">

                            @if($productImage)

                                <img
                                    src="{{ $productImage }}"
                                    alt="{{ $product->product_name }}"
                                    class="highlight-card-photo"
                                    loading="lazy" style="height:330px !important"
                                >

                            @else

                                <span class="highlight-emoji">
                                    <i class="bi bi-bag-heart"></i>
                                </span>

                            @endif

                        </div>


                        <div class="highlight-card-body" style="padding-left:14px !important;padding-right:14px !important;padding-top:110px !important;">

                            <h6>
                                {{ $product->product_name }}
                            </h6>

                            @if($hasPrice)

                                <span class="product-price">
                                    {{ $product->price }}
                                </span>

                            @endif

                        </div>

                    </a>

                @empty

                    <p class="text-center">
                        No highlighted girls available.
                    </p>

                @endforelse

            </div>

        </div>

    </section>


    <!-- CONTACT SECTION -->
    <section class="contact-strip">

        <div class="container">

            <div class="contact-strip-inner">

                <div class="contact-strip-text">

                    <h3 id="csTitle">
                        Got Questions?
                        <span class="accent" id="csAcc">We're Here!</span>
                    </h3>

                    <p id="csSub">
                        Reach out via WhatsApp, Telegram, or give us a call.
                        We respond within minutes.
                    </p>

                </div>

                <a
                    href="{{ route('frontend.contact') }}"
                    class="btn-primary-custom"
                    id="csBtn"
                >
                    Contact Us
                    <i class="bi bi-chat-fill"></i>
                </a>

            </div>

        </div>

    </section>

@endsection

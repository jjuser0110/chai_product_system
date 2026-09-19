@extends('frontend.layouts.app')

@section('title', 'Tsuki – Home')

@section('content')
<style>
    /* =========================
    HIGHLIGHT CARD SIZE
    ========================= */

    .highlight-track {
        height: auto !important;
        min-height: 0 !important;
    }

    .highlight-card {
        height: auto !important;
        min-height: 0 !important;
    }

    .highlight-card-img {
        height: 280px !important;
        overflow: hidden;
    }

    .highlight-card-photo {
        width: 100% !important;
        height: 280px !important;
        object-fit: cover !important;
        display: block;
    }

    .highlight-card-body {
        padding-left: 14px !important;
        padding-right: 14px !important;
        min-height: 20px !important;
        height: auto !important;
    }

    .highlight-card-body h6 {
        margin: 0 0 6px 0 !important;
    }
    /* =========================
    HERO BANNER
    ========================= */

    .hero-slider {
        width: 100%;
        overflow: hidden;
    }

    .hero-slider .carousel,
    .hero-slider .carousel-inner,
    .hero-slider .carousel-item {
        width: 100%;
    }

    /* Fixed banner size */
    .banner-slide {
        width: 100%;
        height: 420px;

        /* Make image fill the ENTIRE banner */
        background-size: 100% 100%;
        background-position: center center;
        background-repeat: no-repeat;

        position: relative;
        overflow: hidden;
    }

    /* Optional dark overlay */
    .banner-slide::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.15);
        z-index: 1;
    }

    /* Banner text */
    .banner-content {
        position: relative;
        z-index: 2;
    }
/* =========================
   HIGHLIGHT IMAGE POPUP
========================= */

.highlight-image-popup-trigger {
    width: 100%;
    height: 100%;
    cursor: zoom-in;
    overflow: hidden;
}

.highlight-image-popup-trigger img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}


/* Popup background */
.highlight-image-modal {
    display: none;
    position: fixed;
    z-index: 99999;
    inset: 0;

    width: 100%;
    height: 100%;

    background: rgba(0, 0, 0, 0.85);

    align-items: center;
    justify-content: center;

    padding: 30px;
}


/* Popup image */
.highlight-image-modal img {
    max-width: 95%;
    max-height: 90vh;

    width: auto;
    height: auto;

    object-fit: contain;

    border-radius: 10px;

    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
}


/* Close button */
.highlight-image-modal-close {
    position: absolute;

    top: 20px;
    right: 30px;

    width: 45px;
    height: 45px;

    border: none;
    border-radius: 50%;

    background: rgba(255, 255, 255, 0.9);

    color: #222;

    font-size: 30px;
    line-height: 1;

    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 100000;
}

.highlight-image-modal-close:hover {
    background: #fff;
}


/* Mobile */
@media (max-width: 768px) {
    .banner-slide {
        height: 260px;

        /* Still force image to cover the whole banner */
        background-size: 100% 100%;
    }
    .highlight-image-modal {
        padding: 15px;
    }

    .highlight-image-modal img {
        max-width: 100%;
        max-height: 85vh;
    }

    .highlight-image-modal-close {
        top: 15px;
        right: 15px;
    }
}

</style>
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
    <!-- <section class="about-section">

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

    </section> -->


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
                id="highlightTrack"
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

                        {{-- IMAGE --}}
                        <div class="highlight-card-img">

                            @if($productImage)

                                <div
                                    class="highlight-image-popup-trigger"
                                    data-image="{{ $productImage }}"
                                    data-title="{{ $product->product_name }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); openHighlightImage(this);"
                                >
                                    <img
                                        src="{{ $productImage }}"
                                        alt="{{ $product->product_name }}"
                                        class="highlight-card-photo"
                                        loading="lazy"
                                    >
                                </div>

                            @else

                                <span class="highlight-emoji">
                                    <i class="bi bi-bag-heart"></i>
                                </span>

                            @endif

                        </div>


                        {{-- CARD BODY --}}
                        <div class="highlight-card-body">

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

                    <p class="text-center" id="hlEmpty">
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
    {{-- HIGHLIGHT IMAGE POPUP --}}
    <div
        id="highlightImageModal"
        class="highlight-image-modal"
        onclick="closeHighlightImage(event)"
    >

        <button
            type="button"
            class="highlight-image-modal-close"
            onclick="closeHighlightImage(event)"
            aria-label="Close"
        >
            &times;
        </button>

        <img
            id="highlightPopupImage"
            src=""
            alt=""
        >

    </div>

<script>
    function openHighlightImage(element) {

        const imageUrl = element.getAttribute('data-image');
        const title = element.getAttribute('data-title');

        const modal = document.getElementById('highlightImageModal');
        const popupImage = document.getElementById('highlightPopupImage');

        popupImage.src = imageUrl;
        popupImage.alt = title || '';

        modal.style.display = 'flex';

        document.body.style.overflow = 'hidden';
    }


    function closeHighlightImage(event) {

        // Don't close when clicking the image itself
        if (
            event.target.id === 'highlightPopupImage'
        ) {
            return;
        }

        const modal = document.getElementById('highlightImageModal');

        modal.style.display = 'none';

        document.getElementById('highlightPopupImage').src = '';

        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            const modal = document.getElementById('highlightImageModal');

            if (modal.style.display === 'flex') {
                modal.style.display = 'none';

                document.getElementById('highlightPopupImage').src = '';

                document.body.style.overflow = '';
            }

        }

    });

</script>
@endsection
@extends('frontend.layouts.app')

@section('title', 'ShopNest – ' . $product->product_name)

@section('content')

<!-- PRODUCT PAGE HERO -->

<div class="page-hero">

<div class="container">

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb-custom" id="detailBreadcrumb">

            <li>
                <a href="{{ route('frontend.home') }}">
                    Home
                </a>
            </li>

            @if($product->category)

                <li>
                    <a href="{{ route('frontend.categories', ['category' => $product->category_id]) }}">
                        {{ $product->category->category_name }}
                    </a>
                </li>

            @endif

            <li>
                {{ $product->product_name }}
            </li>

        </ol>

    </nav>

</div>

</div>

<!-- PRODUCT DETAIL -->

<section class="section-pad">

<div class="container">

    <div
        id="productDetail"
        class="product-detail-wrap"
    >

        @php
            $attachments = $product->file_attachments;

            $images = $attachments->map(function ($attachment) {
                return asset('storage/' . ltrim($attachment->file_path, '/'));
            })->values();

            $hasImages = $images->count() > 0;
            $hasMultipleImages = $images->count() > 1;

            // Optional fields — only render if they exist on the model.
            $hasPrice = !empty($product->price);
            $specs    = $product->specs ?? null; // expects an array/JSON column
            $hasSpecs = !empty($specs);

            $whatsappNumber  = config('shopnest.whatsapp_number', '60123456789');
            $whatsappMessage = "Hi! I'm interested in " . $product->product_name;
        @endphp

        <div class="row g-4 align-items-start">

            <!-- PRODUCT IMAGE / SLIDER -->
            <div class="col-12 col-md-5">

                <a href="{{ url()->previous() ?: route('frontend.home') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

                @if($hasImages)

                    <div class="img-slider" id="imgSlider">

                        <div class="img-slider-track" id="imgTrack">

                            @foreach($images as $index => $image)

                                <div class="img-slide">
                                    <img
                                        src="{{ $image }}"
                                        alt="{{ $product->product_name }} photo {{ $index + 1 }}"
                                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    >
                                </div>

                            @endforeach

                        </div>

                        @if($hasMultipleImages)

                            <button class="img-slider-btn img-slider-prev" id="sliderPrev" aria-label="Previous">
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <button class="img-slider-btn img-slider-next" id="sliderNext" aria-label="Next">
                                <i class="bi bi-chevron-right"></i>
                            </button>

                            <div class="img-slider-dots" id="sliderDots">

                                @foreach($images as $index => $image)

                                    <button
                                        class="img-dot {{ $index === 0 ? 'active' : '' }}"
                                        data-index="{{ $index }}"
                                        aria-label="Photo {{ $index + 1 }}"
                                    ></button>

                                @endforeach

                            </div>

                            <div class="img-slider-counter" id="sliderCounter">
                                1 / {{ $images->count() }}
                            </div>

                        @endif

                    </div>

                    @if($hasMultipleImages)

                        <div class="img-thumbnails" id="imgThumbs">

                            @foreach($images as $index => $image)

                                <button
                                    class="img-thumb {{ $index === 0 ? 'active' : '' }}"
                                    data-index="{{ $index }}"
                                    aria-label="Photo {{ $index + 1 }}"
                                >
                                    <img src="{{ $image }}" alt="Thumb {{ $index + 1 }}" loading="lazy">
                                </button>

                            @endforeach

                        </div>

                    @endif

                @else

                    <div class="product-detail-img">

                        <span class="product-detail-emoji">
                            <i class="bi bi-bag-heart"></i>
                        </span>

                        @if($product->tag)

                            <span class="product-badge product-badge--lg">
                                {{ $product->tag }}
                            </span>

                        @endif

                    </div>

                @endif

            </div>

            <!-- PRODUCT INFORMATION -->
            <div class="col-12 col-md-7">

                <div class="product-detail-info">

                    @if($product->category)

                        <span class="product-detail-cat">
                            <i class="bi {{ $product->category->icon ?? 'bi-tag' }}"></i>
                            {{ $product->category->category_name }}
                        </span>

                    @endif

                    @if($product->tag && $hasImages)

                        <span
                            class="product-badge product-badge--lg"
                            style="position:relative;top:auto;left:auto;display:inline-block;margin-bottom:10px;"
                        >
                            {{ $product->tag }}
                        </span>

                    @endif

                    <h1 class="product-detail-name">
                        {{ $product->product_name }}
                    </h1>

                    @if($product->short_description)

                        <p class="product-detail-short">
                            {{ $product->short_description }}
                        </p>

                    @endif

                    @if($hasPrice)

                        <div class="product-detail-price">
                            {{ $product->price }}
                        </div>

                    @endif

                    @if($product->description)

                        <div class="product-detail-desc">
                            {!! nl2br(e($product->description)) !!}
                        </div>

                    @endif

                    @if($hasSpecs)

                        <div class="product-specs">

                            <h6>Key Features</h6>

                            <ul>

                                @foreach($specs as $spec)

                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $spec }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <div class="product-detail-actions">

                        <a
                            href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}"
                            target="_blank"
                            class="btn-buy btn-whatsapp"
                        >
                            <i class="bi bi-whatsapp"></i>
                            Order via WhatsApp
                        </a>

                        <a
                            href="{{ route('frontend.contact') }}"
                            class="btn-buy btn-contact"
                        >
                            <i class="bi bi-chat-dots-fill"></i>
                            Contact Us
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</section>

<!-- RELATED PRODUCTS -->

@if($product->category)

@php

    $relatedProducts = \App\Models\Product::with([
        'category',
        'file_attachments'
    ])
    ->where('category_id', $product->category_id)
    ->where('id', '!=', $product->id)
    ->where('is_active', 1)
    ->orderBy('arrangement')
    ->limit(4)
    ->get();

@endphp


@if($relatedProducts->count())

    <section class="section-pad related-products-section">

        <div class="container">

            <div class="section-header">

                <div>

                    <span class="section-tag">
                        You May Also Like
                    </span>

                    <h2 class="section-title">
                        Related
                        <span class="accent">
                            Products
                        </span>
                    </h2>

                </div>

            </div>


            <div class="product-grid">

                @foreach($relatedProducts as $related)

                    @php

                        $attachment = $related->file_attachments->first();

                        $relatedImage = $attachment
                            ? asset(
                                'storage/' .
                                ltrim($attachment->file_path, '/')
                            )
                            : null;

                    @endphp


                    <a
                        href="{{ route('frontend.product', $related->id) }}"
                        class="product-card"
                    >

                        <!-- PRODUCT IMAGE -->
                        <div class="product-card-img">

                            @if($relatedImage)

                                <img
                                    src="{{ $relatedImage }}"
                                    alt="{{ $related->product_name }}"
                                    class="product-card-photo"
                                    loading="lazy"
                                >

                            @else

                                <span class="product-emoji">
                                    🛍️
                                </span>

                            @endif


                            @if($related->tag)

                                <span class="product-badge">
                                    {{ $related->tag }}
                                </span>

                            @endif

                        </div>


                        <!-- PRODUCT BODY -->
                        <div class="product-card-body">

                            @if($related->category)

                                <span class="product-category">
                                    {{ $related->category->category_name }}
                                </span>

                            @endif


                            <h5>
                                {{ $related->product_name }}
                            </h5>


                            @if($related->short_description)

                                <p>
                                    {{ $related->short_description }}
                                </p>

                            @elseif($related->description)

                                <p>
                                    {{ \Illuminate\Support\Str::limit($related->description, 100) }}
                                </p>

                            @endif


                            <div class="product-card-footer">

                                <span class="product-view-btn">
                                    View
                                    <i class="bi bi-arrow-right"></i>
                                </span>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

        </div>

    </section>

@endif

@endif

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    var slider = document.getElementById('imgSlider');
    if (!slider) return;

    var track   = document.getElementById('imgTrack');
    var dots    = document.querySelectorAll('.img-dot');
    var thumbs  = document.querySelectorAll('.img-thumb');
    var counter = document.getElementById('sliderCounter');
    var prevBtn = document.getElementById('sliderPrev');
    var nextBtn = document.getElementById('sliderNext');

    // If there's only one image, there are no nav controls to wire up.
    if (!prevBtn || !nextBtn) return;

    var total   = track.children.length;
    var current = 0;

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';

        dots.forEach(function (d, i) {
            d.classList.toggle('active', i === current);
        });

        thumbs.forEach(function (t, i) {
            t.classList.toggle('active', i === current);
        });

        if (counter) {
            counter.textContent = (current + 1) + ' / ' + total;
        }
    }

    prevBtn.addEventListener('click', function () { goTo(current - 1); });
    nextBtn.addEventListener('click', function () { goTo(current + 1); });

    dots.forEach(function (d) {
        d.addEventListener('click', function () { goTo(+d.dataset.index); });
    });

    thumbs.forEach(function (t) {
        t.addEventListener('click', function () { goTo(+t.dataset.index); });
    });

    // Touch / swipe support
    var touchStartX = 0;
    slider.addEventListener('touchstart', function (e) {
        touchStartX = e.touches[0].clientX;
    }, { passive: true });

    slider.addEventListener('touchend', function (e) {
        var diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) {
            goTo(diff > 0 ? current + 1 : current - 1);
        }
    });

    // Keyboard arrow support
    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft')  goTo(current - 1);
        if (e.key === 'ArrowRight') goTo(current + 1);
    });

});

</script>

@endpush

@endsection
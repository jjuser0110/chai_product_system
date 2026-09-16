@extends('frontend.layouts.app')

@section('title', 'ShopNest – ' . $product->product_name)

@section('content')

<section class="page-hero page-hero--product">
    <div class="container">

        <div class="page-hero-breadcrumb">
            <a href="{{ route('frontend.home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('frontend.categories') }}">Categories</a>

            @if($product->category)
                <span>/</span>
                <span>{{ $product->category->category_name }}</span>
            @endif
        </div>

        <h1 class="page-hero-title">
            {{ $product->product_name }}
        </h1>

        @if($product->short_description)
            <p class="page-hero-sub">
                {{ $product->short_description }}
            </p>
        @endif

    </div>
</section>


<section class="product-detail-section">

    <div class="container">

        <div class="row g-5">

            {{-- PRODUCT IMAGE --}}
            <div class="col-12 col-md-6">

                @php
                    $attachments = $product->file_attachments;
                    $mainAttachment = $attachments->first();

                    $mainImage = $mainAttachment
                        ? asset('storage/' . ltrim($mainAttachment->file_path, '/'))
                        : asset('images/default-product.jpg');
                @endphp

                <div class="product-detail-image-wrap">

                    <img
                        id="mainProductImage"
                        src="{{ $mainImage }}"
                        alt="{{ $product->product_name }}"
                        class="product-detail-image"
                    >

                    @if($product->tag)
                        <span class="product-detail-badge">
                            {{ $product->tag }}
                        </span>
                    @endif

                </div>


                {{-- PRODUCT THUMBNAILS --}}
                @if($attachments->count() > 1)

                    <div class="product-thumbnails">

                        @foreach($attachments as $attachment)

                            @php
                                $image = asset(
                                    'storage/' . ltrim($attachment->file_path, '/')
                                );
                            @endphp

                            <button
                                type="button"
                                class="product-thumbnail {{ $loop->first ? 'active' : '' }}"
                                onclick="changeProductImage('{{ $image }}', this)"
                            >
                                <img
                                    src="{{ $image }}"
                                    alt="{{ $product->product_name }}"
                                >
                            </button>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- PRODUCT INFORMATION --}}
            <div class="col-12 col-md-6">

                <div class="product-detail-info">

                    @if($product->category)

                        <span class="product-category">
                            {{ $product->category->category_name }}
                        </span>

                    @endif


                    <h1 class="product-detail-title">
                        {{ $product->product_name }}
                    </h1>


                    @if($product->short_description)

                        <p class="product-detail-short-description">
                            {{ $product->short_description }}
                        </p>

                    @endif


                    @if($product->description)

                        <div class="product-detail-description">

                            <h3>
                                Description
                            </h3>

                            <div>
                                {!! nl2br(e($product->description)) !!}
                            </div>

                        </div>

                    @endif


                    {{-- PRODUCT TAG --}}
                    @if($product->tag)

                        <div class="product-detail-meta">

                            <span class="meta-label">
                                Tag
                            </span>

                            <span class="product-badge">
                                {{ $product->tag }}
                            </span>

                        </div>

                    @endif


                    {{-- CONTACT BUTTON --}}
                    <div class="product-detail-actions">

                        <a
                            href="{{ route('frontend.contact') }}"
                            class="btn-primary-custom"
                        >
                            Enquire About This Product
                            <i class="bi bi-chat-fill"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- RELATED PRODUCTS --}}
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

        <section class="related-products-section">

            <div class="container">

                <div class="section-header">

                    <div>
                        <span class="section-tag">
                            You May Also Like
                        </span>

                        <h2 class="section-title">
                            Related <span class="accent">Products</span>
                        </h2>
                    </div>

                </div>


                <div class="product-grid">

                    @foreach($relatedProducts as $related)

                        @php
                            $attachment = $related->file_attachments->first();

                            $relatedImage = $attachment
                                ? asset('storage/' . ltrim($attachment->file_path, '/'))
                                : asset('images/default-product.jpg');
                        @endphp


                        <div class="product-card">

                            <a
                                href="{{ route('frontend.product', $related->id) }}"
                                class="product-card-link"
                            >

                                <div class="product-image">

                                    <img
                                        src="{{ $relatedImage }}"
                                        alt="{{ $related->product_name }}"
                                    >

                                    @if($related->tag)

                                        <span class="product-badge">
                                            {{ $related->tag }}
                                        </span>

                                    @endif

                                </div>


                                <div class="product-info">

                                    @if($related->category)

                                        <span class="product-category">
                                            {{ $related->category->category_name }}
                                        </span>

                                    @endif

                                    <h3>
                                        {{ $related->product_name }}
                                    </h3>

                                    @if($related->short_description)

                                        <p>
                                            {{ $related->short_description }}
                                        </p>

                                    @endif

                                </div>

                            </a>

                        </div>

                    @endforeach

                </div>

            </div>

        </section>

    @endif

@endif


@push('scripts')

<script>
function changeProductImage(image, button) {

    const mainImage = document.getElementById('mainProductImage');

    if (mainImage) {
        mainImage.src = image;
    }

    document.querySelectorAll('.product-thumbnail')
        .forEach(function(item) {
            item.classList.remove('active');
        });

    button.classList.add('active');
}
</script>

@endpush

@endsection
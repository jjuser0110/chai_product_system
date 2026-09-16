@extends('frontend.layouts.app')

@section('title', 'ShopNest – Categories')

@section('content')

<!-- PAGE HERO -->
<div class="page-hero">

    <div class="container">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb-custom">

                <li>
                    <a href="{{ route('frontend.home') }}">
                        Home
                    </a>
                </li>

                <li>
                    Categories
                </li>

                @if($selectedCategory)
                    <li>
                        {{ $selectedCategory->category_name }}
                    </li>
                @endif

            </ol>

        </nav>

        <h1 class="page-hero-title">
            @if($selectedCategory)
                {{ $selectedCategory->category_name }}
            @else
                All Categories
            @endif
        </h1>

        @if($selectedCategory)
            <p class="page-hero-sub">
                {{ $selectedCategory->description }}
            </p>
        @endif

    </div>

</div>


<!-- SEARCH BAR -->
<div class="search-bar-wrap">

    <div class="container">

        <div class="search-bar">

            <i class="bi bi-search search-icon"></i>

            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Search categories or products…"
                autocomplete="off"
            />

            <button
                class="search-clear"
                id="searchClear"
                aria-label="Clear"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

        <div
            class="search-meta"
            id="searchMeta">
        </div>

    </div>

</div>


<!-- CONTENT -->
<section class="section-pad">

    <div class="container">

        @if($selectedCategory)

            <!-- BACK TO CATEGORIES -->
            <div class="mb-4">

                <a
                    href="{{ route('frontend.categories') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Categories
                </a>

            </div>


            <!-- PRODUCTS -->
            <div
                id="productGrid"
                class="product-grid"
            >

                @forelse($products as $product)

                    @php
                        $attachment = $product->file_attachments->first();

                        $imageUrl = $attachment
                            ? asset('storage/' . ltrim($attachment->file_path, '/'))
                            : asset('images/default-product.jpg');
                    @endphp


                    <div class="product-card">

                        <a
                            href="{{ route('frontend.product', $product->id) }}"
                            class="text-decoration-none"
                        >

                            <div class="product-image">

                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $product->product_name }}"
                                >

                            </div>


                            <div class="product-info">

                                @if($product->tag)

                                    <span class="product-tag">
                                        {{ $product->tag }}
                                    </span>

                                @endif

                                <h3>
                                    {{ $product->product_name }}
                                </h3>

                                @if($product->short_description)

                                    <p>
                                        {{ $product->short_description }}
                                    </p>

                                @endif

                                <span class="product-card-action">
                                    View Product
                                    <i class="bi bi-arrow-right"></i>
                                </span>

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i class="bi bi-box-seam fs-1"></i>

                        <p class="mt-3">
                            No products available in this category.
                        </p>

                    </div>

                @endforelse

            </div>

        @else

            <!-- CATEGORY GRID -->
            <div
                id="categoryGrid"
                class="category-grid"
            >

                @forelse($categories as $category)

                    <div class="category-card">

                        <a
                            href="{{ route('frontend.categories', ['category' => $category->id]) }}"
                        >

                            <div class="category-icon">

                                <i class="bi bi-grid-fill"></i>

                            </div>


                            <div class="category-info">

                                <h3>
                                    {{ $category->category_name }}
                                </h3>

                                @if($category->description)

                                    <p>
                                        {{ $category->description }}
                                    </p>

                                @endif

                                <span>

                                    {{ $category->products_count }}

                                    {{ $category->products_count == 1 ? 'Product' : 'Products' }}

                                </span>

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i class="bi bi-grid fs-1"></i>

                        <p class="mt-3">
                            No categories available.
                        </p>

                    </div>

                @endforelse

            </div>

        @endif


        <!-- SEARCH RESULTS -->

        <div
            id="searchResults"
            class="product-grid"
            style="display:none;"
        ></div>


        <div
            id="searchEmpty"
            class="search-empty"
            style="display:none;"
        >

            <i class="bi bi-search"></i>

            <p>
                No results found
            </p>

            <span id="searchEmptyTerm"></span>

        </div>

    </div>

</section>

@endsection
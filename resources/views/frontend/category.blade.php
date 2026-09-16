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

</div>

</div>

<!-- SEARCH BAR -->

<div class="search-bar-wrap" id="searchBarWrap">

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

<!-- CATEGORY GRID OR PRODUCT LIST -->

<section class="section-pad">

<div class="container">

    @if($selectedCategory)

        <!-- PRODUCT GRID -->
        <div
            id="productGrid"
            class="product-grid"
        >

            @forelse($products as $product)

                @php
                    $attachment = $product->file_attachments->first();

                    $imageUrl = $attachment
                        ? asset('storage/' . ltrim($attachment->file_path, '/'))
                        : null;
                @endphp


                <a
                    href="{{ route('frontend.product', $product->id) }}"
                    class="product-card"
                    data-name="{{ strtolower($product->product_name) }}"
                    data-description="{{ strtolower(($product->short_description ?? '') . ' ' . ($product->description ?? '')) }}"
                >

                    <!-- PRODUCT IMAGE -->
                    <div class="product-card-img">

                        @if($imageUrl)

                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $product->product_name }}"
                                class="product-card-photo"
                                loading="lazy"
                            >

                        @else

                            <span class="product-emoji">
                                🛍️
                            </span>

                        @endif


                        @if($product->tag)

                            <span class="product-badge">
                                {{ $product->tag }}
                            </span>

                        @endif

                    </div>


                    <!-- PRODUCT BODY -->
                    <div class="product-card-body">

                        <h5>
                            {{ $product->product_name }}
                        </h5>


                        @if($product->short_description)

                            <p>
                                {{ $product->short_description }}
                            </p>

                        @elseif($product->description)

                            <p>
                                {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                            </p>

                        @endif


                        <div class="product-card-footer">

                            @if(isset($product->price) && $product->price !== null)

                                <span class="product-price">
                                    RM {{ number_format($product->price, 2) }}
                                </span>

                            @else

                                <span class="product-price">
                                    &nbsp;
                                </span>

                            @endif


                            <span class="product-view-btn">
                                View
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>

                    </div>

                </a>

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

                <a
                    href="{{ route('frontend.categories', ['category' => $category->id]) }}"
                    class="category-card"
                    data-name="{{ strtolower($category->category_name) }}"
                    data-description="{{ strtolower($category->description ?? '') }}"
                >

                    <!-- CATEGORY ICON -->
                    <div class="category-card-icon">

                        <i class="bi bi-grid-fill"></i>

                    </div>


                    <!-- CATEGORY BODY -->
                    <div class="category-card-body">

                        <h5>
                            {{ $category->category_name }}
                        </h5>


                        @if($category->description)

                            <p>
                                {{ $category->description }}
                            </p>

                        @endif


                        <span class="cat-count">

                            {{ $category->products_count }}

                            {{ $category->products_count == 1 ? 'Product' : 'Products' }}

                        </span>

                    </div>


                    <!-- ARROW -->
                    <i class="bi bi-chevron-right category-arrow"></i>

                </a>

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


    <!-- SEARCH EMPTY -->
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

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    var input     = document.getElementById('searchInput');
    var clearBtn  = document.getElementById('searchClear');
    var meta      = document.getElementById('searchMeta');
    var empty     = document.getElementById('searchEmpty');
    var emptyTerm = document.getElementById('searchEmptyTerm');

    var productGrid  = document.getElementById('productGrid');
    var categoryGrid = document.getElementById('categoryGrid');

    if (!input) return;

    // Only one of these exists per page load, depending on whether
    // a category is selected — filter whichever is present.
    // NOTE: on the "all categories" view this only matches category
    // name/description, since products from every category aren't
    // rendered into the DOM here (no cross-category search endpoint).
    var activeGrid   = productGrid || categoryGrid;
    var cardSelector = productGrid ? '.product-card' : '.category-card';
    var isProductView = !!productGrid;

    var cards = activeGrid
        ? Array.prototype.slice.call(activeGrid.querySelectorAll(cardSelector))
        : [];

    var debounceTimer;

    function resultLabel(count) {
        if (isProductView) {
            return count === 1 ? 'product' : 'products';
        }
        return count === 1 ? 'category' : 'categories';
    }

    function doSearch(rawValue) {
        var term = rawValue.trim();
        var q    = term.toLowerCase();

        if (clearBtn) {
            clearBtn.classList.toggle('visible', term.length > 0);
        }

        if (!q) {
            cards.forEach(function (card) { card.style.display = ''; });
            if (activeGrid) activeGrid.style.display = 'grid';
            if (empty) empty.style.display = 'none';
            if (meta) meta.textContent = '';
            return;
        }

        var matchCount = 0;

        cards.forEach(function (card) {
            var haystack =
                (card.dataset.name || '') + ' ' +
                (card.dataset.description || '');

            var isMatch = haystack.indexOf(q) !== -1;
            card.style.display = isMatch ? '' : 'none';
            if (isMatch) matchCount++;
        });

        if (matchCount === 0) {
            if (activeGrid) activeGrid.style.display = 'none';
            if (empty) empty.style.display = 'flex';
            if (emptyTerm) emptyTerm.textContent = '"' + term + '"';
            if (meta) meta.textContent = '';
        } else {
            if (activeGrid) activeGrid.style.display = 'grid';
            if (empty) empty.style.display = 'none';
            if (meta) {
                meta.textContent = matchCount + ' ' + resultLabel(matchCount) + ' for "' + term + '"';
            }
        }
    }

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            doSearch(input.value);
        }, 180);
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            input.value = '';
            input.focus();
            doSearch('');
        });
    }

});

</script>

@endpush
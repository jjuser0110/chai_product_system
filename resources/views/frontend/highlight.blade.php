@extends('frontend.layouts.app')

@section('title', 'ShopNest – Highlights')

@section('content')

  <!-- PAGE HERO -->
  <div class="page-hero page-hero--highlight">

    <div class="container">

      <nav aria-label="breadcrumb">

        <ol class="breadcrumb-custom">

          <li>

            <a href="{{ route('frontend.home') }}">
              Home
            </a>

          </li>

          <li>
            Highlights
          </li>

        </ol>

      </nav>


      <h1 class="page-hero-title">
        ✦ Highlights
      </h1>


      <p class="page-hero-sub">
        Our best picks, loved by customers
      </p>

    </div>

  </div>


  <!-- HIGHLIGHTS -->
  <section class="section-pad">

    <div class="container">


      <!-- SEARCH BAR -->
      <div
        class="search-bar-wrap search-bar-wrap--inline"
        id="searchBarWrap">

        <div class="search-bar">

          <i class="bi bi-search search-icon"></i>


          <input
            type="text"
            id="searchInput"
            class="search-input"
            placeholder="Search highlights…"
            autocomplete="off"
          />


          <button
            class="search-clear"
            id="searchClear"
            aria-label="Clear">

            <i class="bi bi-x-lg"></i>

          </button>

        </div>


        <div
          class="search-meta"
          id="searchMeta">
        </div>

      </div>


      <!-- PRODUCT GRID -->
      <div
        id="highlightGrid"
        class="product-grid">


        @forelse($highlights as $product)

          @php

            $attachments = $product->file_attachments;
            $attachment  = $attachments->first();

            $productImage = $attachment
                ? asset('storage/' . ltrim($attachment->file_path, '/'))
                : null;

            $imageCount = $attachments->count();
            $hasPrice   = !empty($product->price);
          @endphp


          <a
            href="{{ route('frontend.product', $product->id) }}"
            class="product-card"
            data-name="{{ strtolower($product->product_name) }}"
            data-description="{{ strtolower(($product->short_description ?? '') . ' ' . ($product->description ?? '')) }}"
            data-category="{{ strtolower($product->category->category_name ?? '') }}">


            <!-- PRODUCT IMAGE -->
            <div class="product-card-img">

              @if($productImage)

                <img
                  src="{{ $productImage }}"
                  alt="{{ $product->product_name }}"
                  class="product-card-photo"
                  loading="lazy"
                />

                @if($imageCount > 1)

                  <span class="product-img-count">
                    <i class="bi bi-images"></i>
                    {{ $imageCount }}
                  </span>

                @endif

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

              @endif


              <div class="product-card-footer">

                @if($hasPrice)

                  <span class="product-price">
                    {{ $product->price }}
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

            <i class="bi bi-star fs-1"></i>

            <p class="mt-3">
              No highlighted products available.
            </p>

          </div>


        @endforelse


      </div>


      <!-- SEARCH EMPTY -->
      <div
        id="searchEmpty"
        class="search-empty"
        style="display:none;">

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
    var grid      = document.getElementById('highlightGrid');
    var empty     = document.getElementById('searchEmpty');
    var emptyTerm = document.getElementById('searchEmptyTerm');

    if (!input || !grid) return;

    // These are server-rendered, so we filter the DOM directly
    // using the data-* attributes already on each card, rather
    // than re-rendering from a client-side product array.
    var cards = Array.prototype.slice.call(grid.querySelectorAll('.product-card'));
    var debounceTimer;

    function doSearch(rawValue) {
        var term = rawValue.trim();
        var q    = term.toLowerCase();

        if (clearBtn) {
            clearBtn.classList.toggle('visible', term.length > 0);
        }

        if (!q) {
            cards.forEach(function (card) { card.style.display = ''; });
            grid.style.display  = 'grid';
            empty.style.display = 'none';
            if (meta) meta.textContent = '';
            return;
        }

        var matchCount = 0;

        cards.forEach(function (card) {
            var haystack =
                (card.dataset.name || '') + ' ' +
                (card.dataset.description || '') + ' ' +
                (card.dataset.category || '');

            var isMatch = haystack.indexOf(q) !== -1;
            card.style.display = isMatch ? '' : 'none';
            if (isMatch) matchCount++;
        });

        if (matchCount === 0) {
            grid.style.display  = 'none';
            empty.style.display = 'flex';
            if (emptyTerm) emptyTerm.textContent = '"' + term + '"';
            if (meta) meta.textContent = '';
        } else {
            grid.style.display  = 'grid';
            empty.style.display = 'none';
            if (meta) {
                meta.textContent = matchCount + ' result' + (matchCount !== 1 ? 's' : '') + ' for "' + term + '"';
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
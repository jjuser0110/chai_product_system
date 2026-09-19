@extends('frontend.layouts.app')

@section('title', 'Tsuki – Highlights')

@section('content')
<style>
/* ========================================
   HIGHLIGHT PRODUCT IMAGE
======================================== */

.product-card-img {
    width: 100%;
    height: 280px !important;
    overflow: hidden;
}


  /* ========================================
   PRODUCT IMAGE POPUP
======================================== */

.product-image-popup-trigger {
    width: 100%;
    height: 100%;
    cursor: zoom-in;
    overflow: hidden;
    display: block;
}

.product-image-popup-trigger .product-card-photo {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

/* Popup overlay */
.product-image-modal {
    display: none;

    position: fixed;
    inset: 0;

    width: 100%;
    height: 100%;

    background: rgba(0, 0, 0, 0.88);

    z-index: 99999;

    align-items: center;
    justify-content: center;

    padding: 30px;

    cursor: zoom-out;
}


/* Popup image */
.product-image-modal img {
    max-width: 95%;
    max-height: 90vh;

    width: auto;
    height: auto;

    object-fit: contain;

    border-radius: 10px;

    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);

    cursor: default;
}


/* Close button */
.product-image-modal-close {
    position: absolute;

    top: 20px;
    right: 25px;

    width: 48px;
    height: 48px;

    border: none;
    border-radius: 50%;

    background: rgba(255, 255, 255, 0.95);

    color: #222;

    font-size: 32px;
    line-height: 1;

    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 100000;

    transition: 0.2s ease;
}

.product-image-modal-close:hover {
    background: #fff;
    transform: scale(1.05);
}


/* Mobile */
@media (max-width: 768px) {

    .product-image-modal {
        padding: 15px;
    }

    .product-image-modal img {
        max-width: 100%;
        max-height: 85vh;
    }

    .product-image-modal-close {
        top: 15px;
        right: 15px;

        width: 42px;
        height: 42px;

        font-size: 28px;
    }
}
</style>
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

                <div
                  class="product-image-popup-trigger"
                  data-image="{{ $productImage }}"
                  data-title="{{ $product->product_name }}"
                  onclick="event.preventDefault(); event.stopPropagation(); openProductImage(this);"
                >

                  <img
                    src="{{ $productImage }}"
                    alt="{{ $product->product_name }}"
                    class="product-card-photo"
                    loading="lazy"
                  />

                </div>


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
              No highlighted girls available.
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
  {{-- PRODUCT IMAGE POPUP --}}
  <div
      id="productImageModal"
      class="product-image-modal"
      onclick="closeProductImage(event)"
  >

      <button
          type="button"
          class="product-image-modal-close"
          onclick="closeProductImage(event)"
          aria-label="Close"
      >
          &times;
      </button>

      <img
          id="productPopupImage"
          src=""
          alt=""
      >

  </div>
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
/* ========================================
   PRODUCT IMAGE POPUP
======================================== */

function openProductImage(element) {

var imageUrl = element.getAttribute('data-image');
var title = element.getAttribute('data-title');

var modal = document.getElementById('productImageModal');
var popupImage = document.getElementById('productPopupImage');

if (!modal || !popupImage) {
    return;
}

popupImage.src = imageUrl;
popupImage.alt = title || '';

modal.style.display = 'flex';

document.body.style.overflow = 'hidden';
}


function closeProductImage(event) {

/*
 * If clicking the actual image,
 * don't close the popup.
 */
if (event.target.id === 'productPopupImage') {
    return;
}

var modal = document.getElementById('productImageModal');
var popupImage = document.getElementById('productPopupImage');

if (!modal) {
    return;
}

modal.style.display = 'none';

if (popupImage) {
    popupImage.src = '';
}

document.body.style.overflow = '';
}


/* Close popup with ESC */
document.addEventListener('keydown', function(event) {

if (event.key === 'Escape') {

    var modal = document.getElementById('productImageModal');

    if (modal && modal.style.display === 'flex') {

        modal.style.display = 'none';

        var popupImage =
            document.getElementById('productPopupImage');

        if (popupImage) {
            popupImage.src = '';
        }

        document.body.style.overflow = '';
    }
}

});
</script>

@endpush
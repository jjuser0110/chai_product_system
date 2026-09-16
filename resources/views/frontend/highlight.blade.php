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

            $attachment = $product->file_attachments->first();

            $productImage = $attachment
                ? asset('storage/' . ltrim($attachment->file_path, '/'))
                : asset('images/default-product.jpg');

          @endphp


          <div
            class="product-card"
            data-name="{{ strtolower($product->product_name) }}"
            data-description="{{ strtolower($product->short_description ?? '') }}"
            data-category="{{ strtolower($product->category->category_name ?? '') }}">


            <a
              href="{{ route('frontend.product', $product->id) }}"
              class="product-card-link">


              <!-- PRODUCT IMAGE -->
              <div class="product-image">

                <img
                  src="{{ $productImage }}"
                  alt="{{ $product->product_name }}"
                />


                @if($product->tag)

                  <span class="product-badge">
                    {{ $product->tag }}
                  </span>

                @endif

              </div>


              <!-- PRODUCT INFO -->
              <div class="product-info">


                @if($product->category)

                  <span class="product-category">
                    {{ $product->category->category_name }}
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


              </div>

            </a>

          </div>


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
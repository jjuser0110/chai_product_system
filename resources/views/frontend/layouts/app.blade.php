<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>
        @yield('title', 'Tsuki')
    </title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
    />

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    />

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    />

    @stack('styles')
</head>
<style>
    .logo-link {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .tsuki-logo {
        width: 180px;
        height: auto;
        display: block;
    }
</style>
<body>

<!-- TOP BAR -->
<header class="topbar">

    <div class="topbar-inner">

    <a href="{{ route('frontend.home') }}" class="logo-link">
        <img src="{{ asset('images/tsuki-logo.png') }}" 
            alt="Tsuki" 
            class="tsuki-logo">
    </a>


        <div class="topbar-actions">

            {{-- LANGUAGE SWITCHER --}}
            <div class="lang-switcher" aria-label="Language selector">
                <button type="button" class="lang-btn" data-lang="en">EN</button>
                <button type="button" class="lang-btn" data-lang="zh">中文</button>
                <button type="button" class="lang-btn" data-lang="ms">BM</button>
            </div>

            <button
                class="sidebar-toggle"
                id="sidebarToggle"
                aria-label="Open menu"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>

    </div>

</header>


<!-- SIDEBAR OVERLAY -->
<div
    class="sidebar-overlay"
    id="sidebarOverlay">
</div>


<!-- SIDEBAR -->
<nav
    class="sidebar"
    id="sidebar"
>

    <div class="sidebar-header">

        <span class="logo-text">
        <a href="{{ route('frontend.home') }}" class="logo-link">
            <img src="{{ asset('images/tsuki-logo.png') }}" 
                alt="Tsuki" 
                class="tsuki-logo">
        </a>
        </span>
        <a
            href="https://wa.me/{{ config('tsuki.whatsapp_number', '60123456789') }}"
            target="_blank"
            class="topbar-icon-btn topbar-whatsapp"
            aria-label="WhatsApp"
        >
            <i class="bi bi-whatsapp"></i>
        </a>

        <a
            href="{{ config('tsuki.telegram_url', '#') }}"
            target="_blank"
            class="topbar-icon-btn topbar-telegram"
            aria-label="Telegram"
        >
            <i class="bi bi-telegram"></i>
        </a>
        <button
            class="sidebar-close"
            id="sidebarClose"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>


    <ul class="sidebar-nav">

        <li>
            <a href="{{ route('frontend.home') }}">
                <i class="bi bi-house-fill"></i>
                Home
            </a>
        </li>


        <li>
            <a href="{{ route('frontend.categories') }}">
                <i class="bi bi-grid-fill"></i>
                Categories
            </a>
        </li>


        <li>
            <a href="{{ route('frontend.highlights') }}">
                <i class="bi bi-star-fill"></i>
                Highlights
            </a>
        </li>


        <li>
            <a href="{{ route('frontend.contact') }}">
                <i class="bi bi-chat-dots-fill"></i>
                Contact Us
            </a>
        </li>

    </ul>


    <div class="sidebar-footer">

        <p>
            © {{ date('Y') }} Tsuki. All rights reserved.
        </p>

    </div>

</nav>


<!-- PAGE CONTENT -->
<main
    class="main-content"
    id="mainContent"
>

    @yield('content')

</main>


<!-- BOTTOM BAR -->
<nav class="bottombar">

    <a
        href="{{ route('frontend.home') }}"
        class="bottom-nav-item"
    >
        <i class="bi bi-house-fill"></i>
        <span>Home</span>
    </a>


    <a
        href="{{ route('frontend.categories') }}"
        class="bottom-nav-item"
    >
        <i class="bi bi-grid-fill"></i>
        <span>Category</span>
    </a>


    <a
        href="{{ route('frontend.highlights') }}"
        class="bottom-nav-item"
    >
        <i class="bi bi-star-fill"></i>
        <span>Highlight</span>
    </a>


    <a
        href="{{ route('frontend.contact') }}"
        class="bottom-nav-item"
    >
        <i class="bi bi-chat-dots-fill"></i>
        <span>Contact Us</span>
    </a>

</nav>


<!-- SCRIPTS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

{{-- Language data must load BEFORE app.js --}}
<script src="{{ asset('js/lang.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

</body>
</html>
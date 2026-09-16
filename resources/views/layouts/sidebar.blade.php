@php

$currentRoute = request()->route()->getName();

@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo" >
        <a href="{{ route('home') }}" class="app-brand-link">
            <img src="{{ asset('assets/logo_horizontal.png') }}" alt="Logo" width="120">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1" style="overflow-y:auto">
        <li class="menu-item {{ Str::contains($currentRoute, 'category.index') ? 'active' : ''}}">
            <a href="{{ route('category.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                <div>Category</div>
            </a>
        </li>
        <li class="menu-item {{ Str::contains($currentRoute, 'product.index') ? 'active' : ''}}">
            <a href="{{ route('product.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                <div>Products</div>
            </a>
        </li>
        <li class="menu-item {{ Str::contains($currentRoute, 'banner.index') ? 'active' : ''}}">
            <a href="{{ route('banner.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                <div>Banners</div>
            </a>
        </li>
        <li class="menu-item {{ Str::contains($currentRoute, 'master_setting.index') ? 'active' : ''}}">
            <a href="{{ route('master_setting.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                <div>Master Setting</div>
            </a>
        </li>
    </ul>
    <div class="p-3 mt-auto border-top">
        <a class="btn btn-outline-secondary btn-sm w-100" href="{{ url('/') }}" target="_blank"><i class="bx bx-link-external me-1"></i> View live site</a>
    </div>
</aside>
<!-- end: sidebar -->

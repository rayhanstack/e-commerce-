<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{ $scripts ?? '' }}
</head>
<body class="bg-body-tertiary">
    
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @if(isset($sidebar))
            {{ $sidebar }}
        @else
            <div class="sidebar-light p-3 vh-100 overflow-auto d-flex flex-column" style="width: 260px; min-width: 260px;">
                <!-- Brand Header -->
                <div class="d-flex align-items-center gap-3 px-2 py-3 mb-2 border-bottom border-light">
                    <div class="icon-box icon-box-indigo shadow-sm rounded-3">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div>
                        <div class="sidebar-brand-light fs-5 lh-1">{{ config('app.name', 'Laravel') }}</div>
                        <span class="badge badge-soft-indigo font-monospace px-2 py-1 mt-1" style="font-size: 0.65rem;">ADMIN CONTROL</span>
                    </div>
                </div>

                <!-- Nav Menu -->
                <ul class="nav flex-column mb-auto pt-2">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>{{ __('app.dashboard') }}</span>
                        </a>
                    </li>

                    <div class="sidebar-section-title-light">Catalog Management</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.catalog.products.index') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.catalog.products.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.catalog.categories.index') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.catalog.categories.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-folder-tree"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.catalog.brands.index') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.catalog.brands.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-copyright"></i>
                            <span>Brands</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.catalog.attributes.index') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.catalog.attributes.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Attributes</span>
                        </a>
                    </li>

                    <div class="sidebar-section-title-light">Sales & POS</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Orders & Invoices</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pos.index') }}" target="_blank" class="sidebar-nav-link-light">
                            <i class="fa-solid fa-cash-register"></i>
                            <span>POS Terminal</span>
                        </a>
                    </li>

                    <div class="sidebar-section-title-light">Inventory Control</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.inventory.stocks.index') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.inventory.stocks.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-warehouse"></i>
                            <span>Stock Levels</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.inventory.warehouses.index') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.inventory.warehouses.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-store"></i>
                            <span>Warehouses</span>
                        </a>
                    </li>

                    <div class="sidebar-section-title-light">Tools & Dev</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.barcode.print') }}" class="sidebar-nav-link-light {{ request()->routeIs('admin.barcode.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-print"></i>
                            <span>Barcode Print</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dev.components') }}" class="sidebar-nav-link-light {{ request()->routeIs('dev.components') ? 'active' : '' }}">
                            <i class="fa-solid fa-palette"></i>
                            <span>{{ __('app.components_gallery') }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Footer Quick System Info -->
                <div class="mt-auto pt-3 border-top border-light">
                    <div class="p-2 rounded-3 bg-light text-muted small d-flex align-items-center justify-content-between">
                        <span class="fw-semibold"><i class="fa-solid fa-server text-success me-1"></i> System v1.0</span>
                        <span class="badge bg-white text-secondary border font-monospace">Laravel 13</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1 w-100 overflow-auto vh-100 d-flex flex-column">
            @if(isset($topbar))
                {{ $topbar }}
            @else
                <header class="glass-header sticky-top px-4 py-3 d-flex align-items-center justify-content-between shadow-sm">
                    <!-- Left: Search input -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="input-group input-group-sm" style="width: 320px;">
                            <span class="input-group-text bg-light border-0 text-muted ps-3"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control bg-light border-0 ps-1 py-2" placeholder="Search catalog, SKU, orders...">
                        </div>
                    </div>

                    <!-- Right Controls -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Notifications -->
                        <button class="btn btn-sm btn-light border position-relative rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;" title="Notifications">
                            <i class="fa-regular fa-bell text-secondary"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        </button>

                        <!-- Language Switcher -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border dropdown-toggle fw-semibold text-secondary px-3 py-2 rounded-3" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-globe me-1 text-primary"></i> {{ strtoupper(app()->getLocale()) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                <li><a class="dropdown-item d-flex align-items-center justify-content-between py-2" href="?lang=en">English <span>🇬🇧</span></a></li>
                                <li><a class="dropdown-item d-flex align-items-center justify-content-between py-2" href="?lang=bn">Bangla <span>🇧🇩</span></a></li>
                            </ul>
                        </div>

                        <!-- Profile Dropdown Card -->
                        <div class="dropdown ms-1">
                            <button class="btn btn-light border p-1 rounded-pill d-flex align-items-center gap-2 pe-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-sm rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:38px; height:38px; background: linear-gradient(135deg, #6366f1, #4f46e5);">
                                    {{ strtoupper(substr(auth('admin')->user()?->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="text-start d-none d-md-block">
                                    <div class="fw-bold text-dark lh-1" style="font-size: 0.85rem;">{{ auth('admin')->user()?->name ?? 'Super Admin' }}</div>
                                    <span class="text-muted font-monospace" style="font-size: 0.7rem;">Super Admin</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
                            </button>
                            
                            <!-- Profile Card Dropdown Menu -->
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 mt-2" style="min-width: 250px;">
                                <li class="px-3 py-2 bg-light rounded-3 mb-2">
                                    <div class="fw-bold text-dark">{{ auth('admin')->user()?->name ?? 'Super Admin' }}</div>
                                    <div class="text-muted font-monospace small text-truncate">{{ auth('admin')->user()?->email ?? 'admin@example.com' }}</div>
                                    <span class="badge badge-soft-indigo mt-1">System Administrator</span>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('customer.profile') }}">
                                        <i class="fa-solid fa-circle-user text-indigo" style="width: 18px;"></i>
                                        <span>My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                                        <i class="fa-solid fa-gear text-secondary" style="width: 18px;"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-2 py-2 text-danger d-flex align-items-center gap-2 fw-semibold">
                                            <i class="fa-solid fa-right-from-bracket text-danger" style="width: 18px;"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>
            @endif

            <main class="container-fluid p-4 flex-grow-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
    </script>
</body>
</html>

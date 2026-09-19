<x-layout.admin title="Admin Dashboard">
    <!-- Header Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Control Center Overview</h1>
            <p class="text-muted small mb-0">Real-time stats and metrics for your e-commerce platform.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-soft-success px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-circle text-success" style="font-size:0.5rem;"></i> System Live & Operational
            </span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <x-ui.card class="card-modern-hover">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Products</span>
                        <div class="h2 fw-extrabold text-dark mt-2 mb-1">{{ $totalProducts }}</div>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-arrow-trend-up me-1"></i> Active Catalog</span>
                    </div>
                    <div class="icon-box icon-box-indigo shadow-sm">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="col-xl-4 col-md-6">
            <x-ui.card class="card-modern-hover">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Active Categories</span>
                        <div class="h2 fw-extrabold text-dark mt-2 mb-1">{{ $totalCategories }}</div>
                        <span class="text-primary small fw-medium"><i class="fa-solid fa-layer-group me-1"></i> Organized hierarchy</span>
                    </div>
                    <div class="icon-box icon-box-emerald shadow-sm">
                        <i class="fa-solid fa-folder-tree"></i>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="col-xl-4 col-md-6">
            <x-ui.card class="card-modern-hover">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Low Stock Alerts</span>
                        <div class="h2 fw-extrabold text-dark mt-2 mb-1">{{ $lowStockCount }}</div>
                        <span class="text-amber small fw-medium"><i class="fa-solid fa-triangle-exclamation me-1"></i> Requires attention</span>
                    </div>
                    <div class="icon-box icon-box-amber shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- Quick Shortcuts Grid -->
    <x-ui.card class="mb-4">
        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-bolt text-indigo me-1"></i> Quick Action Studio
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-light border w-100 p-3 text-start d-flex align-items-center gap-3 rounded-3 card-modern-hover text-decoration-none">
                    <div class="icon-box icon-box-indigo shadow-sm flex-shrink-0" style="width:40px; height:40px; font-size:1rem;">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark small">Add Product</div>
                        <div class="text-muted font-monospace" style="font-size:0.7rem;">New SKU / item</div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.catalog.categories.index') }}" class="btn btn-light border w-100 p-3 text-start d-flex align-items-center gap-3 rounded-3 card-modern-hover text-decoration-none">
                    <div class="icon-box icon-box-emerald shadow-sm flex-shrink-0" style="width:40px; height:40px; font-size:1rem;">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark small">Categories</div>
                        <div class="text-muted font-monospace" style="font-size:0.7rem;">Manage tree</div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.inventory.stocks.index') }}" class="btn btn-light border w-100 p-3 text-start d-flex align-items-center gap-3 rounded-3 card-modern-hover text-decoration-none">
                    <div class="icon-box icon-box-purple shadow-sm flex-shrink-0" style="width:40px; height:40px; font-size:1rem;">
                        <i class="fa-solid fa-warehouse"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark small">Stock Audit</div>
                        <div class="text-muted font-monospace" style="font-size:0.7rem;">Inventory levels</div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.barcode.print') }}" class="btn btn-light border w-100 p-3 text-start d-flex align-items-center gap-3 rounded-3 card-modern-hover text-decoration-none">
                    <div class="icon-box icon-box-sky shadow-sm flex-shrink-0" style="width:40px; height:40px; font-size:1rem;">
                        <i class="fa-solid fa-print"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark small">Label Studio</div>
                        <div class="text-muted font-monospace" style="font-size:0.7rem;">Thermal stickers</div>
                    </div>
                </a>
            </div>
        </div>
    </x-ui.card>
</x-layout.admin>

<x-layout.admin title="{{ __('app.products') }}">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('app.products') }}</h1>
            <p class="text-muted small mb-0">Manage simple and variant products, prices, and thermal barcodes.</p>
        </div>
        <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-primary px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>{{ __('app.add_product') }}</span>
        </a>
    </div>

    <!-- Search & Filter Card -->
    <x-ui.card class="mb-4">
        <form action="{{ route('admin.catalog.products.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by name, SKU, or barcode..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100 fw-semibold">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </x-ui.card>

    <!-- Product Table -->
    <x-ui.card :noPadding="true">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted uppercase small font-bold">Product Item</th>
                        <th class="py-3 text-muted uppercase small font-bold">Type</th>
                        <th class="py-3 text-muted uppercase small font-bold">Category & Brand</th>
                        <th class="py-3 text-muted uppercase small font-bold">SKU / Barcode</th>
                        <th class="py-3 text-muted uppercase small font-bold">Price</th>
                        <th class="py-3 text-muted uppercase small font-bold">Status</th>
                        <th class="text-end pe-4 py-3 text-muted uppercase small font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4 fw-semibold">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box icon-box-indigo rounded-3 flex-shrink-0" style="width:40px; height:40px; font-size:1.1rem;">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold">{{ $product->name }}</div>
                                        <div class="text-muted small">Unit: {{ $product->unit }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($product->type === 'variant')
                                    <span class="badge badge-soft-purple px-25 py-15 fw-semibold"><i class="fa-solid fa-cubes me-1"></i> Variant ({{ $product->variants->count() }})</span>
                                @else
                                    <span class="badge badge-soft-info px-25 py-15 fw-semibold"><i class="fa-solid fa-cube me-1"></i> Simple</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                                <div class="text-muted small"><i class="fa-solid fa-copyright me-1 text-secondary"></i> {{ $product->brand?->name ?? '-' }}</div>
                            </td>
                            <td class="font-monospace small">
                                <div><span class="text-muted">SKU:</span> {{ $product->sku ?: '-' }}</div>
                                <div><span class="text-muted">BC:</span> {{ $product->barcode ?: '-' }}</div>
                            </td>
                            <td class="fw-bold text-dark fs-6">
                                ৳{{ number_format($product->active_price, 2) }}
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge badge-soft-success px-25 py-15 fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                @else
                                    <span class="badge badge-soft-danger px-25 py-15 fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.barcode.print', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" title="Print Sticker Label">
                                        <i class="fa-solid fa-barcode text-primary"></i> Label
                                    </a>
                                    <form action="{{ route('admin.catalog.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-boxes-stacked display-4 text-light-emphasis mb-3 d-block"></i>
                                No products found. Click "+ Add Product" above to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $products->links() }}
        </div>
    </x-ui.card>
</x-layout.admin>

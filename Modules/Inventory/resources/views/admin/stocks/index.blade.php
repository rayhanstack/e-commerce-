<x-layout.admin title="Stock Management">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Inventory & Stock Levels</h1>
            <p class="text-muted small mb-0">Real-time stock counts across warehouses and stock ledger overview.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.inventory.stocks.movements') }}" class="btn btn-outline-secondary fw-bold d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-receipt text-indigo"></i> Stock Ledger
            </a>
            <a href="{{ route('admin.inventory.adjustments.create') }}" class="btn btn-primary fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-sliders"></i> Stock Adjustment
            </a>
        </div>
    </div>

    <!-- Stock Filters -->
    <x-ui.card class="mb-4">
        <form action="{{ route('admin.inventory.stocks.index') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search product name, SKU, barcode..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="warehouse_id" class="form-select">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100 fw-semibold">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </x-ui.card>

    <!-- Stock Table -->
    <x-ui.card :noPadding="true">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted uppercase small font-bold">Product Item</th>
                        <th class="py-3 text-muted uppercase small font-bold">Warehouse</th>
                        <th class="py-3 text-muted uppercase small font-bold">SKU / Barcode</th>
                        <th class="py-3 text-muted uppercase small font-bold">In Stock</th>
                        <th class="py-3 text-muted uppercase small font-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-box text-indigo me-1"></i>
                                    <span>{{ $stock->product?->name }}</span>
                                    @if($stock->variant)
                                        <span class="badge badge-soft-secondary ms-1">{{ implode(', ', $stock->variant->attribute_values ?? []) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-soft-primary fw-medium"><i class="fa-solid fa-store me-1"></i> {{ $stock->warehouse?->name }}</span>
                            </td>
                            <td class="font-monospace small text-muted">
                                {{ $stock->variant?->sku ?? $stock->product?->sku }}
                            </td>
                            <td class="fw-extrabold fs-5 text-dark">
                                {{ $stock->quantity }} <span class="fs-6 font-normal text-muted">{{ $stock->product?->unit ?: 'pcs' }}</span>
                            </td>
                            <td>
                                @if($stock->quantity <= ($stock->product?->stock_alert_quantity ?? 5))
                                    <span class="badge badge-soft-warning fw-semibold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Low Stock</span>
                                @else
                                    <span class="badge badge-soft-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> In Stock</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-warehouse display-4 text-light-emphasis mb-3 d-block"></i>
                                No stock records found. Perform a stock adjustment to add inventory.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $stocks->links() }}
        </div>
    </x-ui.card>
</x-layout.admin>

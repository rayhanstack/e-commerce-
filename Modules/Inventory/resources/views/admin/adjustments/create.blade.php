<x-layout.admin title="Stock Adjustment">
    <div class="mb-4">
        <a href="{{ route('admin.inventory.stocks.index') }}" class="text-decoration-none text-muted small fw-semibold">&larr; Back to Inventory</a>
        <h1 class="h3 fw-bold text-dark mt-1">Manual Stock Adjustment</h1>
        <p class="text-muted small mb-0">Record manual stock intake, damage reduction, or audit adjustments.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <x-ui.card>
                <form action="{{ route('admin.inventory.adjustments.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <x-form.label for="warehouse_id" :required="true">Select Warehouse / Branch</x-form.label>
                        <select name="warehouse_id" id="warehouse_id" class="form-select" required>
                            <option value="">-- Choose Warehouse --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-form.label for="product_id" :required="true">Select Product</x-form.label>
                        <select name="product_id" id="product_id" class="form-select" required>
                            <option value="">-- Choose Product --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} (SKU: {{ $prod->sku }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <x-form.label for="type" :required="true">Adjustment Type</x-form.label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="in">Stock IN (+ Add Stock)</option>
                                <option value="out">Stock OUT (- Reduce Stock)</option>
                                <option value="adjustment">Set Exact Count</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <x-form.label for="quantity" :required="true">Quantity</x-form.label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required value="1">
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-form.label for="note">Adjustment Reason / Reference Note</x-form.label>
                        <textarea name="note" id="note" class="form-control" rows="3" placeholder="e.g. Received new shipment, Damaged box, Physical audit..."></textarea>
                    </div>

                    <x-ui.button type="submit" variant="primary" class="w-100 py-25 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-check"></i> Submit Stock Adjustment
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-layout.admin>

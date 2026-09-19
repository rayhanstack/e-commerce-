<x-layout.admin title="Add New Product">
    <div class="mb-4">
        <a href="{{ route('admin.catalog.products.index') }}" class="text-decoration-none text-muted small">&larr; Back to Products</a>
        <h1 class="h3 font-semibold text-gray-900 mt-1">Add New Product</h1>
    </div>

    <form action="{{ route('admin.catalog.products.store') }}" method="POST" id="productForm">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Core Info -->
            <div class="col-md-8">
                <x-ui.card class="border-0 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3">Product Details</h5>
                    
                    <div class="mb-3">
                        <x-form.label for="name" :required="true">Product Title</x-form.label>
                        <x-form.input name="name" id="name" required placeholder="e.g. Wireless Noise-Canceling Headphones" />
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <x-form.label for="category_id">Category</x-form.label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <x-form.label for="brand_id">Brand</x-form.label>
                            <select name="brand_id" id="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-form.label for="short_description">Short Description</x-form.label>
                        <textarea name="short_description" id="short_description" class="form-control" rows="2" placeholder="Brief summary..."></textarea>
                    </div>

                    <div class="mb-3">
                        <x-form.label for="description">Full Description</x-form.label>
                        <textarea name="description" id="description" class="form-control" rows="5" placeholder="Detailed product specifications & info..."></textarea>
                    </div>
                </x-ui.card>

                <!-- Pricing & Inventory Card -->
                <x-ui.card class="border-0 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3">Pricing & Inventory</h5>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <x-form.label for="buying_price">Buying Cost (৳)</x-form.label>
                            <x-form.input type="number" step="0.01" name="buying_price" id="buying_price" placeholder="0.00" />
                        </div>
                        <div class="col-md-4">
                            <x-form.label for="selling_price" :required="true">Selling Price (৳)</x-form.label>
                            <x-form.input type="number" step="0.01" name="selling_price" id="selling_price" placeholder="0.00" />
                        </div>
                        <div class="col-md-4">
                            <x-form.label for="special_price">Offer Price (৳)</x-form.label>
                            <x-form.input type="number" step="0.01" name="special_price" id="special_price" placeholder="Optional" />
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-form.label for="sku">SKU (Stock Keeping Unit)</x-form.label>
                            <x-form.input name="sku" id="sku" placeholder="Auto-generated if blank" />
                        </div>
                        <div class="col-md-4">
                            <x-form.label for="barcode">Barcode (EAN / Code128)</x-form.label>
                            <x-form.input name="barcode" id="barcode" placeholder="Auto-generated if blank" />
                        </div>
                        <div class="col-md-4">
                            <x-form.label for="initial_stock">Opening Stock Qty</x-form.label>
                            <x-form.input type="number" name="initial_stock" id="initial_stock" value="10" />
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Right Column: Configurations & Submit -->
            <div class="col-md-4">
                <x-ui.card class="border-0 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3">Product Type & Settings</h5>
                    
                    <div class="mb-3">
                        <x-form.label for="type" :required="true">Product Type</x-form.label>
                        <select name="type" id="type" class="form-select">
                            <option value="simple" selected>Simple Product</option>
                            <option value="variant">Variant Product</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-form.label for="unit">Unit</x-form.label>
                        <input type="text" name="unit" id="unit" class="form-control" value="pcs" placeholder="pcs, kg, box">
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                        <label for="is_active" class="form-check-label fw-medium">Active & Visible</label>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1">
                        <label for="is_featured" class="form-check-label fw-medium">Featured on Storefront</label>
                    </div>

                    <hr>

                    <x-ui.button type="submit" variant="primary" class="w-100 py-2 fw-bold">Publish Product</x-ui.button>
                </x-ui.card>
            </div>
        </div>
    </form>
</x-layout.admin>

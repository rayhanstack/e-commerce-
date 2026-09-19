<x-layout.storefront :title="$product->name">
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                @if($product->category)
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Product Image / Gallery -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 text-center bg-light rounded-4">
                    <div class="display-1 py-5 text-secondary">📦</div>
                </div>
            </div>

            <!-- Product Details & Actions -->
            <div class="col-md-6">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-medium mb-2">
                    {{ $product->category?->name ?? 'General' }}
                </span>

                <h1 class="h2 fw-bold text-dark mb-2">{{ $product->name }}</h1>
                <p class="text-muted mb-3">SKU: <span class="font-monospace text-dark">{{ $product->sku }}</span></p>

                <!-- Pricing -->
                <div class="d-flex align-items-baseline gap-3 mb-4">
                    <span class="h2 fw-extrabold text-primary mb-0">৳{{ number_format($product->active_price, 2) }}</span>
                    @if($product->special_price)
                        <span class="h5 text-muted text-decoration-line-through mb-0">৳{{ number_format($product->selling_price, 2) }}</span>
                    @endif
                </div>

                <p class="text-secondary mb-4">{{ $product->short_description ?: 'High quality single-vendor e-commerce product.' }}</p>

                <hr class="my-4">

                <!-- Add to Cart / POS Actions -->
                <div class="d-flex gap-3 mb-4">
                    <div class="input-group" style="max-width: 140px;">
                        <button class="btn btn-outline-secondary" type="button">-</button>
                        <input type="text" class="form-control text-center fw-bold" value="1">
                        <button class="btn btn-outline-secondary" type="button">+</button>
                    </div>
                    <button class="btn btn-primary px-4 py-2 rounded-3 fw-bold flex-grow-1">
                        🛒 Add to Cart
                    </button>
                </div>

                <div class="p-3 bg-light rounded-3 text-sm text-muted">
                    🚚 Fast Local Delivery &nbsp;|&nbsp; 🔒 100% Secure Checkout &nbsp;|&nbsp; 🏷️ Original Guarantee
                </div>
            </div>
        </div>
    </div>
</x-layout.storefront>

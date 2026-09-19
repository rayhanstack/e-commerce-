<x-layout.storefront title="Shopping Cart">
    <div class="container py-5">
        <h1 class="h2 fw-bold text-dark mb-4"><i class="fa-solid fa-cart-shopping me-2 text-primary"></i> Shopping Cart</h1>

        @if(count($cart) > 0)
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-0 rounded-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Product</th>
                                        <th class="py-3">Price</th>
                                        <th class="py-3 text-center">Quantity</th>
                                        <th class="py-3 text-end">Subtotal</th>
                                        <th class="py-3 text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $key => $item)
                                        <tr>
                                            <td class="ps-4 fw-semibold text-dark">
                                                {{ $item['title'] }}
                                                @if($item['variant_name'])
                                                    <span class="badge badge-soft-secondary ms-1">{{ $item['variant_name'] }}</span>
                                                @endif
                                            </td>
                                            <td class="font-monospace">৳{{ number_format($item['price'], 2) }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update') }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                                    @csrf
                                                    <input type="hidden" name="key" value="{{ $key }}">
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm text-center font-monospace" style="width:70px;">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Update Qty"><i class="fa-solid fa-rotate me-0"></i></button>
                                                </form>
                                            </td>
                                            <td class="text-end fw-bold font-monospace">৳{{ number_format($item['subtotal'], 2) }}</td>
                                            <td class="text-end pe-4">
                                                <form action="{{ route('cart.remove', $key) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash-can"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <h5 class="fw-bold mb-3 text-dark">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold text-dark">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Estimated Shipping</span>
                            <span class="fw-bold text-dark">৳60.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5 text-dark">Total</span>
                            <span class="fw-extrabold fs-4 text-primary">৳{{ number_format($subtotal + 60, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                            <span>Proceed to Checkout</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5 card border-0 shadow-sm rounded-4">
                <i class="fa-solid fa-cart-shopping display-1 text-light-emphasis mb-3"></i>
                <h4 class="fw-bold text-secondary">Your Cart is Empty</h4>
                <p class="text-muted mb-4">Explore our catalog and add your favorite items to cart.</p>
                <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2 fw-bold rounded-3">Browse Products</a>
            </div>
        @endif
    </div>
</x-layout.storefront>

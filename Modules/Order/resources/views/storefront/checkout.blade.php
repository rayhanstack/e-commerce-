<x-layout.storefront title="Checkout">
    <div class="container py-5">
        <h1 class="h2 fw-bold text-dark mb-4"><i class="fa-solid fa-credit-card me-2 text-primary"></i> Checkout</h1>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row g-4">
                <!-- Left: Address & Payment Info -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                        <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-truck me-2 text-indigo"></i> Shipping Details</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-form.label for="name" :required="true">Full Name</x-form.label>
                                <x-form.input name="name" id="name" required value="{{ old('name', $user?->name) }}" placeholder="John Doe" />
                            </div>
                            <div class="col-md-6">
                                <x-form.label for="phone" :required="true">Phone Number</x-form.label>
                                <x-form.input name="phone" id="phone" required value="{{ old('phone', $user?->mobile_number) }}" placeholder="+8801700000000" />
                            </div>
                            <div class="col-12">
                                <x-form.label for="email">Email Address</x-form.label>
                                <x-form.input type="email" name="email" id="email" value="{{ old('email', $user?->email) }}" placeholder="john@example.com" />
                            </div>
                            <div class="col-12">
                                <x-form.label for="address" :required="true">Delivery Address</x-form.label>
                                <textarea name="address" id="address" class="form-control" rows="3" required placeholder="House, Road, Area, District..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-wallet me-2 text-primary"></i> Payment Method</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check card p-3 border cursor-pointer">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payCod" value="cod" checked>
                                    <label class="form-check-label fw-bold text-dark w-100 ms-2" for="payCod">
                                        <i class="fa-solid fa-hand-holding-dollar text-success me-1"></i> Cash on Delivery (COD)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check card p-3 border cursor-pointer">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payBkash" value="bkash">
                                    <label class="form-check-label fw-bold text-dark w-100 ms-2" for="payBkash">
                                        <i class="fa-solid fa-mobile-screen text-danger me-1"></i> bKash / Nagad Mobile Banking
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <h5 class="fw-bold mb-3 text-dark">Order Items ({{ count($cart) }})</h5>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($cart as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                    <div>
                                        <div class="fw-semibold text-dark small">{{ $item['title'] }}</div>
                                        <div class="text-muted font-monospace small">Qty: {{ $item['quantity'] }}</div>
                                    </div>
                                    <span class="fw-bold text-dark font-monospace">৳{{ number_format($item['subtotal'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold text-dark">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping Delivery</span>
                            <span class="fw-bold text-dark">৳60.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5 text-dark">Total Amount</span>
                            <span class="fw-extrabold fs-4 text-primary">৳{{ number_format($subtotal + 60, 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Place Order Now</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout.storefront>

<x-layout.admin title="Order Details">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted small fw-semibold">&larr; Back to Orders</a>
            <h1 class="h3 fw-bold text-dark mt-1">Order Details: <span class="font-monospace text-primary">{{ $order->order_number }}</span></h1>
            <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('M d, Y \a\t H:i A') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-primary fw-bold d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-file-invoice"></i> Printable Invoice
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Items & Payment Breakdown -->
        <div class="col-lg-8">
            <x-ui.card :noPadding="true" class="mb-4">
                <div class="p-3 border-bottom bg-light fw-bold text-dark d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-boxes-stacked me-2 text-indigo"></i> Itemized Summary</span>
                    <span class="badge badge-soft-secondary">{{ $order->items->count() }} items</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">
                                        {{ $item->product_name }}
                                        @if($item->variant_name)
                                            <span class="badge badge-soft-secondary ms-1">{{ $item->variant_name }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center font-monospace">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 fw-extrabold font-monospace">৳{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-light border-top text-end">
                    <div class="d-flex justify-content-end mb-1">
                        <span class="text-muted me-3">Subtotal:</span>
                        <span class="fw-bold font-monospace">৳{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-end mb-1 text-danger">
                            <span class="me-3">Discount:</span>
                            <span class="fw-bold font-monospace">-৳{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-end mb-1">
                        <span class="text-muted me-3">Shipping Delivery:</span>
                        <span class="fw-bold font-monospace">৳{{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-end fs-5 fw-extrabold text-primary pt-2 border-top">
                        <span class="me-3">Grand Total:</span>
                        <span class="font-monospace">৳{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Right: Status Update & Customer Info -->
        <div class="col-lg-4">
            <!-- Update Status Card -->
            <x-ui.card class="mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-sliders text-indigo me-1"></i> Update Status</h5>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <x-form.label for="status" :required="true">Order Status</x-form.label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-form.label for="payment_status" :required="true">Payment Status</x-form.label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partially_paid" {{ $order->payment_status === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <x-ui.button type="submit" variant="primary" class="w-100 fw-bold">Update Order</x-ui.button>
                </form>
            </x-ui.card>

            <!-- Customer Info -->
            <x-ui.card>
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-user me-1 text-primary"></i> Customer Details</h5>
                <div class="mb-2 fw-bold text-dark">{{ $order->customer?->name ?? 'Walk-in Customer' }}</div>
                <div class="text-muted small mb-1"><i class="fa-solid fa-envelope me-1"></i> {{ $order->customer?->email ?: 'N/A' }}</div>
                <div class="text-muted small mb-3"><i class="fa-solid fa-phone me-1"></i> {{ $order->customer?->mobile_number ?: 'N/A' }}</div>

                @if($order->shipping_address)
                    <hr>
                    <div class="fw-bold text-dark small mb-1">Shipping Address:</div>
                    <div class="small text-muted">{{ $order->shipping_address['address'] ?? '' }}</div>
                @endif
            </x-ui.card>
        </div>
    </div>
</x-layout.admin>

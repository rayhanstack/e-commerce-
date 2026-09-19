<x-layout.storefront title="Order Confirmed">
    <div class="container py-5 text-center">
        <div class="card border-0 shadow-sm p-5 rounded-4 max-w-lg mx-auto" style="max-width: 600px;">
            <div class="icon-box icon-box-emerald mx-auto mb-3 rounded-circle shadow-sm" style="width:72px; height:72px; font-size:2.2rem;">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h2 class="fw-bold text-dark mb-1">Order Placed Successfully!</h2>
            <p class="text-muted mb-4">Thank you for your order. We have received your purchase details.</p>

            <div class="p-3 bg-light rounded-3 text-start mb-4 border">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Order Number:</span>
                    <strong class="font-monospace text-primary fs-6">{{ $order->order_number }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Payment Status:</span>
                    <span class="badge badge-soft-warning uppercase">{{ $order->payment_status }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Total Paid/Due:</span>
                    <strong class="text-dark font-monospace">৳{{ number_format($order->grand_total, 2) }}</strong>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4 fw-bold">Continue Shopping</a>
                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-primary px-4 fw-bold">Print Invoice</a>
            </div>
        </div>
    </div>
</x-layout.storefront>

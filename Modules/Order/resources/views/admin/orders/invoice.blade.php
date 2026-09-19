<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    @vite(['resources/sass/app.scss'])
    <style>
        body { background: #f8fafc; color: #0f172a; font-family: sans-serif; }
        .invoice-card { background: #ffffff; max-width: 800px; margin: 30px auto; border-radius: 12px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        @media print {
            body { background: #fff; }
            .invoice-card { box-shadow: none; padding: 0; margin: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="container no-print text-end my-3" style="max-width: 800px;">
        <button onclick="window.print()" class="btn btn-primary px-4 fw-bold">🖨️ Print Invoice</button>
    </div>

    <div class="invoice-card">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <h2 class="fw-bold text-primary mb-0">{{ config('app.name', 'STORE') }}</h2>
                <div class="text-muted small">Single-Vendor E-commerce & POS</div>
            </div>
            <div class="text-end">
                <h4 class="fw-bold mb-0">INVOICE</h4>
                <div class="font-monospace text-muted small">#{{ $order->order_number }}</div>
                <div class="small text-muted">Date: {{ $order->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="row g-4 mb-4">
            <div class="col-6">
                <h6 class="fw-bold text-uppercase text-secondary small">Customer Info</h6>
                <div class="fw-bold text-dark">{{ $order->customer?->name ?? 'Walk-in Customer' }}</div>
                <div class="small text-muted">{{ $order->customer?->email }}</div>
                <div class="small text-muted">{{ $order->customer?->mobile_number }}</div>
                @if($order->shipping_address)
                    <div class="small text-muted mt-1">{{ $order->shipping_address['address'] ?? '' }}</div>
                @endif
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold text-uppercase text-secondary small">Order Details</h6>
                <div class="small">Payment Method: <strong class="text-uppercase">{{ $order->payment_method }}</strong></div>
                <div class="small">Payment Status: <strong class="text-uppercase text-success">{{ $order->payment_status }}</strong></div>
                <div class="mt-2">{!! $barcodeSvg !!}</div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="table table-bordered align-middle mb-4">
            <thead class="table-light">
                <tr>
                    <th>Item Description</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td class="fw-semibold">
                            {{ $item->product_name }}
                            @if($item->variant_name)
                                <span class="badge bg-light text-dark border ms-1">{{ $item->variant_name }}</span>
                            @endif
                        </td>
                        <td class="text-center font-monospace">৳{{ number_format($item->price, 2) }}</td>
                        <td class="text-center fw-bold">{{ $item->quantity }}</td>
                        <td class="text-end font-monospace fw-bold">৳{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="row justify-content-end mb-4">
            <div class="col-5">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Subtotal:</span>
                    <span class="font-monospace fw-bold">৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-1 text-danger">
                        <span>Discount:</span>
                        <span class="font-monospace fw-bold">-৳{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Shipping:</span>
                    <span class="font-monospace fw-bold">৳{{ number_format($order->shipping_amount, 2) }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between fs-5 fw-extrabold text-primary">
                    <span>Grand Total:</span>
                    <span class="font-monospace">৳{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="text-center text-muted small border-top pt-3 font-monospace">
            Thank you for your business! If you have questions about this invoice, please contact support.
        </div>
    </div>
</body>
</html>

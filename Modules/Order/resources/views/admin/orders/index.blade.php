<x-layout.admin title="Orders Management">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Orders Management</h1>
            <p class="text-muted small mb-0">Track storefront e-commerce orders and POS cashier sales.</p>
        </div>
        <a href="{{ route('pos.index') }}" target="_blank" class="btn btn-primary fw-bold px-4 shadow-sm d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-cash-register"></i> Open POS Terminal
        </a>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search order number..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="storefront" {{ request('type') == 'storefront' ? 'selected' : '' }}>Storefront</option>
                    <option value="pos" {{ request('type') == 'pos' ? 'selected' : '' }}>POS Terminal</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100 fw-semibold"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </x-ui.card>

    <!-- Orders Table -->
    <x-ui.card :noPadding="true">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted uppercase small font-bold">Order #</th>
                        <th class="py-3 text-muted uppercase small font-bold">Type</th>
                        <th class="py-3 text-muted uppercase small font-bold">Customer / Cashier</th>
                        <th class="py-3 text-muted uppercase small font-bold">Date</th>
                        <th class="py-3 text-muted uppercase small font-bold">Total</th>
                        <th class="py-3 text-muted uppercase small font-bold">Payment</th>
                        <th class="py-3 text-muted uppercase small font-bold">Status</th>
                        <th class="text-end pe-4 py-3 text-muted uppercase small font-bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold font-monospace text-primary">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">{{ $order->order_number }}</a>
                            </td>
                            <td>
                                @if($order->type === 'pos')
                                    <span class="badge badge-soft-purple fw-semibold"><i class="fa-solid fa-cash-register me-1"></i> POS Sale</span>
                                @else
                                    <span class="badge badge-soft-info fw-semibold"><i class="fa-solid fa-globe me-1"></i> Storefront</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->customer?->name ?? 'Walk-in Customer' }}</div>
                                <div class="text-muted small">{{ $order->customer?->email }}</div>
                            </td>
                            <td class="small text-muted font-monospace">
                                {{ $order->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="fw-extrabold text-dark font-monospace">
                                ৳{{ number_format($order->grand_total, 2) }}
                            </td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge badge-soft-success fw-semibold"><i class="fa-solid fa-check me-1"></i> Paid</span>
                                @else
                                    <span class="badge badge-soft-warning fw-semibold"><i class="fa-solid fa-clock me-1"></i> Unpaid</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status === 'completed')
                                    <span class="badge badge-soft-success fw-semibold">Completed</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge badge-soft-primary fw-semibold">Processing</span>
                                @else
                                    <span class="badge badge-soft-warning fw-semibold">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" title="View Order">
                                        <i class="fa-solid fa-eye text-indigo"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" title="Print Invoice">
                                        <i class="fa-solid fa-file-invoice text-primary"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt display-4 text-light-emphasis mb-3 d-block"></i>
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $orders->links() }}
        </div>
    </x-ui.card>
</x-layout.admin>

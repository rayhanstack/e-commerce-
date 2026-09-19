<x-layout.admin title="Stock Movement Ledger">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.inventory.stocks.index') }}" class="text-decoration-none text-muted small fw-semibold">&larr; Back to Stock Levels</a>
            <h1 class="h3 fw-bold text-dark mt-1">Stock Movement Audit Ledger</h1>
            <p class="text-muted small mb-0">Immutable, step-by-step history of every stock entry, exit, and adjustment.</p>
        </div>
    </div>

    <x-ui.card :noPadding="true">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted uppercase small font-bold">Date & Time</th>
                        <th class="py-3 text-muted uppercase small font-bold">Movement Type</th>
                        <th class="py-3 text-muted uppercase small font-bold">Product Item</th>
                        <th class="py-3 text-muted uppercase small font-bold">Warehouse</th>
                        <th class="py-3 text-muted uppercase small font-bold">Before &rarr; After</th>
                        <th class="py-3 text-muted uppercase small font-bold">Delta Qty</th>
                        <th class="py-3 text-muted uppercase small font-bold">Reference / Note</th>
                        <th class="pe-4 py-3 text-muted uppercase small font-bold">User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td class="ps-4 small text-muted font-monospace">
                                <i class="fa-regular fa-clock me-1"></i> {{ $m->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td>
                                @if($m->type === 'in')
                                    <span class="badge badge-soft-success fw-semibold"><i class="fa-solid fa-arrow-down-left me-1"></i> Stock In</span>
                                @elseif($m->type === 'out')
                                    <span class="badge badge-soft-danger fw-semibold"><i class="fa-solid fa-arrow-up-right me-1"></i> Stock Out</span>
                                @else
                                    <span class="badge badge-soft-info fw-semibold"><i class="fa-solid fa-sliders me-1"></i> Adjustment</span>
                                @endif
                            </td>
                            <td class="fw-semibold text-dark">
                                {{ $m->product?->name }}
                            </td>
                            <td class="small text-secondary"><i class="fa-solid fa-store me-1"></i> {{ $m->warehouse?->name }}</td>
                            <td class="font-monospace small">
                                {{ $m->quantity_before }} &rarr; <span class="fw-bold text-dark">{{ $m->quantity_after }}</span>
                            </td>
                            <td class="fw-bold {{ $m->quantity >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $m->quantity >= 0 ? '+'.$m->quantity : $m->quantity }}
                            </td>
                            <td class="small text-muted">
                                <div class="fw-medium text-dark">{{ $m->reference_type ?: 'Manual' }}</div>
                                <div class="text-secondary">{{ $m->note }}</div>
                            </td>
                            <td class="pe-4 small text-muted">
                                <i class="fa-solid fa-user-gear me-1"></i> {{ $m->user?->name ?: 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt display-4 text-light-emphasis mb-3 d-block"></i>
                                No stock movements recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $movements->links() }}
        </div>
    </x-ui.card>
</x-layout.admin>

<x-layout.admin title="Warehouses & Outlets">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Warehouses & Outlets</h1>
            <p class="text-muted small mb-0">Manage physical stores, outlets, and storage locations.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add Warehouse Form -->
        <div class="col-md-4">
            <x-ui.card>
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-store text-indigo"></i> Add Warehouse / Branch
                </h5>
                <form action="{{ route('admin.inventory.warehouses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <x-form.label for="name" :required="true">Location Name</x-form.label>
                        <x-form.input name="name" id="name" required placeholder="e.g. Main Outlet, Central Warehouse" />
                    </div>

                    <div class="mb-3">
                        <x-form.label for="code" :required="true">Branch Code</x-form.label>
                        <x-form.input name="code" id="code" required placeholder="e.g. WH-01, OUTLET-MAIN" />
                    </div>

                    <div class="mb-3">
                        <x-form.label for="phone">Phone</x-form.label>
                        <x-form.input name="phone" id="phone" placeholder="+8801700000000" />
                    </div>

                    <div class="mb-3">
                        <x-form.label for="address">Address</x-form.label>
                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="Full street address..."></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_default" id="is_default" class="form-check-input" value="1">
                        <label for="is_default" class="form-check-label fw-medium">Set as Primary Default Location</label>
                    </div>

                    <x-ui.button type="submit" variant="primary" class="w-100 fw-bold">
                        <i class="fa-solid fa-check me-1"></i> Save Warehouse
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>

        <!-- Warehouses Table -->
        <div class="col-md-8">
            <x-ui.card :noPadding="true">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted uppercase small font-bold">Warehouse</th>
                                <th class="py-3 text-muted uppercase small font-bold">Code</th>
                                <th class="py-3 text-muted uppercase small font-bold">Phone</th>
                                <th class="py-3 text-muted uppercase small font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouses as $wh)
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-building text-indigo me-1"></i>
                                            <span>{{ $wh->name }}</span>
                                            @if($wh->is_default)
                                                <span class="badge badge-soft-primary ms-1"><i class="fa-solid fa-star me-1"></i> Primary Default</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="font-monospace small text-primary fw-bold">{{ $wh->code }}</td>
                                    <td class="text-muted small"><i class="fa-solid fa-phone text-secondary me-1"></i> {{ $wh->phone ?: '-' }}</td>
                                    <td>
                                        @if($wh->is_active)
                                            <span class="badge badge-soft-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                        @else
                                            <span class="badge badge-soft-danger fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-store display-4 text-light-emphasis mb-3 d-block"></i>
                                        No warehouses found. Add your default warehouse on the left.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layout.admin>

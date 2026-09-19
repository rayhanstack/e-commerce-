<x-layout.admin title="Product Attributes">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Product Attributes</h1>
            <p class="text-muted small mb-0">Configure variant attributes like Color, Size, Storage, RAM, etc.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add Attribute Form -->
        <div class="col-md-4">
            <x-ui.card>
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-sliders text-indigo"></i> Add New Attribute
                </h5>
                <form action="{{ route('admin.catalog.attributes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <x-form.label for="name" :required="true">Attribute Title</x-form.label>
                        <x-form.input name="name" id="name" required placeholder="e.g. Color, Size, Material" />
                    </div>
                    <x-ui.button type="submit" variant="primary" class="w-100 fw-bold">
                        <i class="fa-solid fa-plus me-1"></i> Create Attribute
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>

        <!-- Attributes & Values List -->
        <div class="col-md-8">
            <div class="row g-3">
                @forelse($attributes as $attribute)
                    <div class="col-12">
                        <x-ui.card class="card-modern-hover">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-swatchbook text-indigo"></i> {{ $attribute->name }}
                                </h6>
                                <form action="{{ route('admin.catalog.attributes.destroy', $attribute) }}" method="POST" onsubmit="return confirm('Delete attribute?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0">
                                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                                    </button>
                                </form>
                            </div>

                            <!-- Values Badge List -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @forelse($attribute->values as $val)
                                    <span class="badge badge-soft-secondary border px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                                        @if($val->color_code)
                                            <span class="rounded-circle border" style="width:12px; height:12px; background-color: {{ $val->color_code }};"></span>
                                        @endif
                                        {{ $val->value }}
                                    </span>
                                @empty
                                    <span class="text-muted small">No values defined yet. Add values below.</span>
                                @endforelse
                            </div>

                            <!-- Add Value Form -->
                            <form action="{{ route('admin.catalog.attributes.values.store', $attribute) }}" method="POST" class="row g-2 align-items-center">
                                @csrf
                                <div class="col-6">
                                    <input type="text" name="value" class="form-control form-control-sm" placeholder="Value (e.g. Red, XL)" required>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="color_code" class="form-control form-control-sm font-monospace" placeholder="#FF0000 (Optional)">
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </form>
                        </x-ui.card>
                    </div>
                @empty
                    <div class="col-12">
                        <x-ui.card class="text-center py-5 text-muted">
                            <i class="fa-solid fa-sliders display-4 text-light-emphasis mb-3 d-block"></i>
                            No attributes defined yet. Add your first attribute on the left.
                        </x-ui.card>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layout.admin>

<x-layout.admin title="{{ __('app.categories') }}">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('app.categories') }}</h1>
            <p class="text-muted small mb-0">Manage product categories and parent-child hierarchy.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add Category Form -->
        <div class="col-md-4">
            <x-ui.card>
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-folder-plus text-indigo"></i> Add New Category
                </h5>
                <form action="{{ route('admin.catalog.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <x-form.label for="name" :required="true">Category Name</x-form.label>
                        <x-form.input name="name" id="name" required placeholder="e.g. Electronics, Fashion" />
                    </div>

                    <div class="mb-3">
                        <x-form.label for="parent_id">Parent Category</x-form.label>
                        <select name="parent_id" id="parent_id" class="form-select">
                            <option value="">-- Main Category --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-form.label for="description">Description</x-form.label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Category summary..."></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                        <label for="is_active" class="form-check-label fw-medium">Active Category</label>
                    </div>

                    <x-ui.button type="submit" variant="primary" class="w-100 fw-bold">
                        <i class="fa-solid fa-check me-1"></i> Save Category
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>

        <!-- Categories Table -->
        <div class="col-md-8">
            <x-ui.card :noPadding="true">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted uppercase small font-bold">Category</th>
                                <th class="py-3 text-muted uppercase small font-bold">Parent</th>
                                <th class="py-3 text-muted uppercase small font-bold">Slug</th>
                                <th class="py-3 text-muted uppercase small font-bold">Status</th>
                                <th class="text-end pe-4 py-3 text-muted uppercase small font-bold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-folder text-warning fs-5"></i>
                                            <span>{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($category->parent)
                                            <span class="badge badge-soft-secondary font-monospace"><i class="fa-solid fa-turn-up fa-rotate-90 me-1"></i> {{ $category->parent->name }}</span>
                                        @else
                                            <span class="text-muted small">Top Level</span>
                                        @endif
                                    </td>
                                    <td class="text-muted font-monospace small">{{ $category->slug }}</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge badge-soft-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                        @else
                                            <span class="badge badge-soft-danger fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.catalog.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-folder-tree display-4 text-light-emphasis mb-3 d-block"></i>
                                        No categories created yet. Add one on the left.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $categories->links() }}
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layout.admin>

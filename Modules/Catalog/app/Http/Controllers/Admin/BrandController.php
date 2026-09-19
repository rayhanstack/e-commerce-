<?php

namespace Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Catalog\Models\Brand;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::orderBy('name')->paginate(15);

        return view('catalog::admin.brands.index', compact('brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(4);
        $validated['is_active'] = $request->has('is_active');

        Brand::create($validated);

        return redirect()->route('admin.catalog.brands.index')->with('success', 'Brand created successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()->route('admin.catalog.brands.index')->with('success', 'Brand deleted successfully.');
    }
}

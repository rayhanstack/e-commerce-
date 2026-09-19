<?php

namespace Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Catalog\Models\Attribute;

class AttributeController extends Controller
{
    public function index(): View
    {
        $attributes = Attribute::with('values')->orderBy('name')->get();

        return view('catalog::admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $attribute = Attribute::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.catalog.attributes.index')->with('success', 'Attribute created successfully.');
    }

    public function storeValue(Request $request, Attribute $attribute): RedirectResponse
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
        ]);

        $attribute->values()->create($validated);

        return redirect()->route('admin.catalog.attributes.index')->with('success', 'Attribute value added.');
    }

    public function destroy(Attribute $attribute): RedirectResponse
    {
        $attribute->delete();

        return redirect()->route('admin.catalog.attributes.index')->with('success', 'Attribute deleted.');
    }
}

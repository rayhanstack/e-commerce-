<?php

namespace Modules\Inventory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Inventory\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::withCount('stocks')->paginate(15);

        return view('inventory::admin.warehouses.index', compact('warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        if (! empty($validated['is_default'])) {
            Warehouse::query()->update(['is_default' => false]);
        }

        Warehouse::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_default' => $request->has('is_default'),
            'is_active' => true,
        ]);

        return redirect()->route('admin.inventory.warehouses.index')->with('success', 'Warehouse created successfully.');
    }
}

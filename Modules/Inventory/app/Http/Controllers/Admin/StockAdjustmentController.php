<?php

namespace Modules\Inventory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\StockMovementService;

class StockAdjustmentController extends Controller
{
    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::with('variants')->where('is_active', true)->get();

        return view('inventory::admin.adjustments.create', compact('warehouses', 'products'));
    }

    public function store(Request $request, StockMovementService $stockService): RedirectResponse
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        $stockService->recordMovement(
            warehouseId: $validated['warehouse_id'],
            productId: $validated['product_id'],
            variantId: $validated['product_variant_id'] ?? null,
            type: $validated['type'],
            quantity: $validated['quantity'],
            referenceType: 'Manual Adjustment',
            userId: auth()->id(),
            note: $validated['note'] ?? null
        );

        return redirect()->route('admin.inventory.stocks.index')->with('success', 'Stock adjustment recorded successfully.');
    }
}

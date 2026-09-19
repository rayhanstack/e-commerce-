<?php

namespace Modules\Inventory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Inventory\Models\Stock;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $warehouses = Warehouse::all();
        $query = Stock::with(['warehouse', 'product', 'variant']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $stocks = $query->paginate(20);

        return view('inventory::admin.stocks.index', compact('stocks', 'warehouses'));
    }

    public function movements(Request $request): View
    {
        $movements = StockMovement::with(['warehouse', 'product', 'variant', 'user'])
            ->latest()
            ->paginate(25);

        return view('inventory::admin.stocks.movements', compact('movements'));
    }
}

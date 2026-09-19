<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\Stock;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $lowStockCount = Stock::whereColumn('quantity', '<=', 'product_id')->count();

        return view('core::admin.dashboard', compact('totalProducts', 'totalCategories', 'lowStockCount'));
    }
}

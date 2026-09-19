<?php

namespace Modules\Catalog\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

class ProductCatalogController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::with(['category', 'brand', 'variants', 'stocks'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('catalog::storefront.show', compact('product', 'relatedProducts'));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->paginate(12);

        return view('catalog::storefront.category', compact('category', 'products'));
    }
}

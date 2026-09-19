<?php

namespace Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Catalog\Models\Attribute;
use Modules\Catalog\Models\Brand;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductVariant;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\StockMovementService;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'variants', 'stocks']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('catalog::admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        $attributes = Attribute::with('values')->get();

        return view('catalog::admin.products.create', compact('categories', 'brands', 'attributes'));
    }

    public function store(Request $request, StockMovementService $stockService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:simple,variant',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'buying_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required_if:type,simple|nullable|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'stock_alert_quantity' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:20',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'initial_stock' => 'nullable|integer|min:0',
            // Variant payloads
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required_with:variants|string',
            'variants.*.barcode' => 'nullable|string',
            'variants.*.selling_price' => 'required_with:variants|numeric|min:0',
            'variants.*.attribute_values' => 'nullable|array',
        ]);

        $slug = Str::slug($validated['name']).'-'.Str::random(5);
        $sku = $validated['sku'] ?: 'SKU-'.strtoupper(Str::random(8));
        $barcode = $validated['barcode'] ?: rand(100000000000, 999999999999);

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'category_id' => $validated['category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'buying_price' => $validated['buying_price'] ?? 0.00,
            'selling_price' => $validated['selling_price'] ?? 0.00,
            'special_price' => $validated['special_price'] ?? null,
            'sku' => $sku,
            'barcode' => (string) $barcode,
            'stock_alert_quantity' => $validated['stock_alert_quantity'] ?? 5,
            'unit' => $validated['unit'] ?? 'pcs',
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
            'track_inventory' => true,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // If simple product and initial stock is provided
        if ($product->type === 'simple' && ! empty($validated['initial_stock'])) {
            $defaultWarehouse = Warehouse::where('is_default', true)->first() ?? Warehouse::first();
            if ($defaultWarehouse) {
                $stockService->recordMovement(
                    warehouseId: $defaultWarehouse->id,
                    productId: $product->id,
                    variantId: null,
                    type: 'in',
                    quantity: (int) $validated['initial_stock'],
                    referenceType: 'Initial Stock',
                    note: 'Created during product creation'
                );
            }
        }

        // If variant product
        if ($product->type === 'variant' && ! empty($validated['variants'])) {
            foreach ($validated['variants'] as $variantData) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'barcode' => $variantData['barcode'] ?? rand(100000000000, 999999999999),
                    'buying_price' => $variantData['buying_price'] ?? $product->buying_price,
                    'selling_price' => $variantData['selling_price'],
                    'attribute_values' => $variantData['attribute_values'] ?? [],
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('admin.catalog.products.index')->with('success', 'Product created successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.catalog.products.index')->with('success', 'Product deleted successfully.');
    }
}

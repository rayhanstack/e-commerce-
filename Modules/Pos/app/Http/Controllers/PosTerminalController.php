<?php

namespace Modules\Pos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Barcode\Services\BarcodeGeneratorService;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Order\Services\OrderService;

class PosTerminalController extends Controller
{
    public function index(): View
    {
        $warehouse = Warehouse::where('is_default', true)->first() ?? Warehouse::first();
        $categories = Category::where('is_active', true)->get();
        $products = Product::with(['variants', 'category'])
            ->where('is_active', true)
            ->get();
        $customers = User::role('customer', 'web')->get();

        return view('pos::terminal', compact('warehouse', 'categories', 'products', 'customers'));
    }

    public function search(Request $request): JsonResponse
    {
        $query = Product::with(['variants', 'category'])->where('is_active', true);

        if ($request->filled('term')) {
            $term = $request->term;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('barcode', $term);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->take(30)->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function checkout(Request $request, OrderService $orderService, BarcodeGeneratorService $barcodeService): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.product_name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|numeric',
            'discount_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['subtotal'];
        }

        $discount = (float) ($validated['discount_amount'] ?? 0);
        $grandTotal = max(0, $subtotal - $discount);
        $paidAmount = (float) $validated['paid_amount'];
        $changeAmount = max(0, $paidAmount - $grandTotal);

        $orderData = [
            'type' => 'pos',
            'customer_id' => $validated['customer_id'] ?? null,
            'warehouse_id' => $validated['warehouse_id'],
            'user_id' => auth()->id(),
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'grand_total' => $grandTotal,
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
            'items' => $validated['items'],
        ];

        $order = $orderService->createOrder($orderData);

        // Generate thermal barcode/QR for receipt
        $barcodeSvg = $barcodeService->generateCode128Svg($order->order_number, 36, 2);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction completed successfully.',
            'order' => $order->load(['items', 'customer']),
            'barcodeSvg' => $barcodeSvg,
        ]);
    }
}

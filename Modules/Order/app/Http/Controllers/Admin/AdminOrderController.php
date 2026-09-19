<?php

namespace Modules\Order\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Barcode\Services\BarcodeGeneratorService;
use Modules\Order\Models\Order;

class AdminOrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['customer', 'cashier', 'warehouse', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }

        $orders = $query->latest()->paginate(15);

        return view('order::admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['customer', 'cashier', 'warehouse', 'items', 'payments']);

        return view('order::admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'payment_status' => 'required|in:unpaid,paid,partially_paid,refunded',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
    }

    public function invoice(Order $order, BarcodeGeneratorService $barcodeService): View
    {
        $order->load(['customer', 'warehouse', 'items']);
        $barcodeSvg = $barcodeService->generateCode128Svg($order->order_number, 40, 2);

        return view('order::admin.orders.invoice', compact('order', 'barcodeSvg'));
    }
}

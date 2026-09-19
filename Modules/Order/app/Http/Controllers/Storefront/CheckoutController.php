<?php

namespace Modules\Order\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Order\Models\Order;
use Modules\Order\Services\CartService;
use Modules\Order\Services\OrderService;

class CheckoutController extends Controller
{
    public function index(CartService $cartService): View|RedirectResponse
    {
        $cart = $cartService->getCart();
        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('warning', 'Your cart is empty.');
        }

        $subtotal = $cartService->getSubtotal();
        $user = auth()->user();

        return view('order::storefront.checkout', compact('cart', 'subtotal', 'user'));
    }

    public function process(Request $request, CartService $cartService, OrderService $orderService): RedirectResponse
    {
        $cart = $cartService->getCart();
        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('warning', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'payment_method' => 'required|in:cod,bkash,nagad,card',
            'notes' => 'nullable|string',
        ]);

        $subtotal = $cartService->getSubtotal();
        $shipping = 60.00; // Standard local delivery
        $grandTotal = $subtotal + $shipping;

        $items = [];
        foreach ($cart as $c) {
            $items[] = [
                'product_id' => $c['product_id'],
                'product_variant_id' => $c['product_variant_id'],
                'product_name' => $c['title'],
                'variant_name' => $c['variant_name'],
                'sku' => $c['sku'],
                'price' => $c['price'],
                'quantity' => $c['quantity'],
                'subtotal' => $c['subtotal'],
            ];
        }

        $order = $orderService->createOrder([
            'type' => 'storefront',
            'customer_id' => auth()->id(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'shipping_amount' => $shipping,
            'grand_total' => $grandTotal,
            'shipping_address' => [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'],
            ],
            'notes' => $validated['notes'] ?? null,
            'items' => $items,
        ]);

        $cartService->clear();

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success(string $orderNumber): View
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        return view('order::storefront.success', compact('order'));
    }
}

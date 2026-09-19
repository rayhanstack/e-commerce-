<?php

namespace Modules\Order\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Order\Services\CartService;

class CartController extends Controller
{
    public function index(CartService $cartService): View
    {
        $cart = $cartService->getCart();
        $subtotal = $cartService->getSubtotal();

        return view('order::storefront.cart', compact('cart', 'subtotal'));
    }

    public function add(Request $request, CartService $cartService): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $cartService->add(
            productId: (int) $validated['product_id'],
            variantId: ! empty($validated['product_variant_id']) ? (int) $validated['product_variant_id'] : null,
            quantity: (int) ($validated['quantity'] ?? 1)
        );

        return redirect()->route('cart.index')->with('success', 'Item added to shopping cart.');
    }

    public function update(Request $request, CartService $cartService): RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $cartService->update($validated['key'], (int) $validated['quantity']);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(string $key, CartService $cartService): RedirectResponse
    {
        $cartService->remove($key);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}

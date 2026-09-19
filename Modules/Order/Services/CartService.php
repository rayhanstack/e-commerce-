<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\Session;
use Modules\Catalog\Models\Product;

class CartService
{
    protected string $sessionKey = 'shopping_cart';

    public function getCart(): array
    {
        return Session::get($this->sessionKey, []);
    }

    public function add(int $productId, ?int $variantId = null, int $quantity = 1): array
    {
        $cart = $this->getCart();
        $product = Product::with('variants')->findOrFail($productId);
        $key = $variantId ? "{$productId}_{$variantId}" : (string) $productId;

        $variant = null;
        if ($variantId && $product->variants) {
            $variant = $product->variants->firstWhere('id', $variantId);
        }

        $price = $variant ? $variant->active_price : $product->active_price;
        $title = $product->name;
        $variantName = $variant ? implode(', ', $variant->attribute_values ?? []) : null;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
            $cart[$key]['subtotal'] = round($cart[$key]['quantity'] * $cart[$key]['price'], 2);
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'title' => $title,
                'variant_name' => $variantName,
                'sku' => $variant?->sku ?? $product->sku,
                'price' => (float) $price,
                'quantity' => $quantity,
                'subtotal' => round($price * $quantity, 2),
            ];
        }

        Session::put($this->sessionKey, $cart);

        return $cart;
    }

    public function update(string $key, int $quantity): array
    {
        $cart = $this->getCart();

        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
                $cart[$key]['subtotal'] = round($cart[$key]['quantity'] * $cart[$key]['price'], 2);
            }
        }

        Session::put($this->sessionKey, $cart);

        return $cart;
    }

    public function remove(string $key): array
    {
        $cart = $this->getCart();
        unset($cart[$key]);
        Session::put($this->sessionKey, $cart);

        return $cart;
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    public function getSubtotal(): float
    {
        $subtotal = 0;
        foreach ($this->getCart() as $item) {
            $subtotal += $item['subtotal'];
        }

        return round($subtotal, 2);
    }
}

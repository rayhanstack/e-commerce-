<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\Stock;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\StockMovementService;
use Modules\Order\Models\Order;
use Modules\Order\Services\CartService;
use Modules\Order\Services\OrderService;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderAndPosTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_and_deduct_inventory_stock(): void
    {
        $warehouse = Warehouse::create([
            'name' => 'Main Outlet',
            'code' => 'WH-01',
            'is_default' => true,
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Logitech Wireless Mouse',
            'slug' => 'logitech-wireless-mouse',
            'sku' => 'LOG-MSE-01',
            'buying_price' => 1200,
            'selling_price' => 1800,
            'is_active' => true,
        ]);

        // Stock in 20 units
        $stockService = app(StockMovementService::class);
        $stockService->recordMovement(
            warehouseId: $warehouse->id,
            productId: $product->id,
            variantId: null,
            type: 'in',
            quantity: 20
        );

        $orderService = app(OrderService::class);
        $order = $orderService->createOrder([
            'type' => 'pos',
            'warehouse_id' => $warehouse->id,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'subtotal' => 3600,
            'grand_total' => 3600,
            'paid_amount' => 4000,
            'change_amount' => 400,
            'items' => [
                [
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'product_name' => 'Logitech Wireless Mouse',
                    'price' => 1800,
                    'quantity' => 2,
                    'subtotal' => 3600,
                ],
            ],
        ]);

        $this->assertDatabaseHas('orders', [
            'order_number' => $order->order_number,
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);

        // Verify stock deducted from 20 to 18
        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'quantity' => 18,
        ]);
    }

    public function test_storefront_cart_service_add_and_update(): void
    {
        $product = Product::create([
            'name' => 'Keyboard Mechanical',
            'slug' => 'keyboard-mechanical',
            'sku' => 'KEY-01',
            'selling_price' => 2500,
        ]);

        $cartService = app(CartService::class);
        $cartService->clear();
        $cartService->add($product->id, null, 2);

        $this->assertEquals(5000.00, $cartService->getSubtotal());

        $cartService->update((string) $product->id, 3);
        $this->assertEquals(7500.00, $cartService->getSubtotal());
    }

    public function test_admin_can_view_order_details_and_update_status(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        $order = Order::create([
            'order_number' => 'ORD-TEST-100',
            'type' => 'storefront',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'grand_total' => 1500,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->patch("/admin/orders/{$order->id}/status", [
                'status' => 'completed',
                'payment_status' => 'paid',
            ]);

        $response->assertRedirect("/admin/orders/{$order->id}");
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
    }
}

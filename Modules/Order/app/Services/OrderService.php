<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\StockMovementService;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Models\Payment;

class OrderService
{
    public function __construct(
        protected StockMovementService $stockService
    ) {}

    /**
     * Create an order (POS or Storefront) atomically.
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $type = $data['type'] ?? 'storefront';
            $prefix = $type === 'pos' ? 'POS' : 'ORD';
            $orderNumber = $prefix.'-'.date('Ymd').'-'.strtoupper(Str::random(5));

            $warehouseId = $data['warehouse_id'] ?? null;
            if (! $warehouseId) {
                $defaultWh = Warehouse::where('is_default', true)->first() ?? Warehouse::first();
                $warehouseId = $defaultWh?->id;
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'type' => $type,
                'customer_id' => $data['customer_id'] ?? null,
                'warehouse_id' => $warehouseId,
                'user_id' => $data['user_id'] ?? auth()->id(),
                'status' => $data['status'] ?? ($type === 'pos' ? 'completed' : 'pending'),
                'payment_status' => $data['payment_status'] ?? 'unpaid',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'subtotal' => $data['subtotal'] ?? 0.00,
                'tax_amount' => $data['tax_amount'] ?? 0.00,
                'discount_amount' => $data['discount_amount'] ?? 0.00,
                'shipping_amount' => $data['shipping_amount'] ?? 0.00,
                'grand_total' => $data['grand_total'] ?? 0.00,
                'paid_amount' => $data['paid_amount'] ?? 0.00,
                'change_amount' => $data['change_amount'] ?? 0.00,
                'shipping_address' => $data['shipping_address'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Save items & deduct stock
            if (! empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['product_variant_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'variant_name' => $item['variant_name'] ?? null,
                        'sku' => $item['sku'] ?? null,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'tax' => $item['tax'] ?? 0.00,
                        'discount' => $item['discount'] ?? 0.00,
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Deduct stock if order is completed or POS
                    if ($order->status === 'completed' && $warehouseId) {
                        $this->stockService->recordMovement(
                            warehouseId: $warehouseId,
                            productId: $item['product_id'],
                            variantId: $item['product_variant_id'] ?? null,
                            type: 'out',
                            quantity: (int) $item['quantity'],
                            referenceType: 'Order '.$order->order_number,
                            referenceId: (string) $order->id,
                            userId: $order->user_id,
                            note: 'Automatic deduction upon sale'
                        );
                    }
                }
            }

            // Record initial payment if paid
            if ($order->paid_amount > 0) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $order->payment_method,
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'amount' => $order->paid_amount,
                    'status' => 'success',
                    'user_id' => $order->user_id,
                    'note' => 'Payment received at checkout',
                ]);
            }

            return $order;
        });
    }
}

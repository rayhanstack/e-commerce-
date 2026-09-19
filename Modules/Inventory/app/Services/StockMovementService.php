<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\Stock;
use Modules\Inventory\Models\StockMovement;

class StockMovementService
{
    /**
     * Adjust stock level for a product or variant in a warehouse.
     */
    public function recordMovement(
        int $warehouseId,
        int $productId,
        ?int $variantId,
        string $type, // 'in', 'out', 'adjustment', 'transfer'
        int $quantity,
        ?string $referenceType = null,
        ?string $referenceId = null,
        ?int $userId = null,
        ?string $note = null
    ): StockMovement {
        return DB::transaction(function () use (
            $warehouseId,
            $productId,
            $variantId,
            $type,
            $quantity,
            $referenceType,
            $referenceId,
            $userId,
            $note
        ) {
            $stock = Stock::firstOrCreate(
                [
                    'warehouse_id' => $warehouseId,
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                ],
                [
                    'quantity' => 0,
                    'reserved_quantity' => 0,
                ]
            );

            $quantityBefore = $stock->quantity;

            if ($type === 'in') {
                $quantityAfter = $quantityBefore + abs($quantity);
            } elseif ($type === 'out') {
                $quantityAfter = max(0, $quantityBefore - abs($quantity));
            } else {
                // Adjustment set or delta
                $quantityAfter = max(0, $quantity);
            }

            $stock->update(['quantity' => $quantityAfter]);

            $movementQuantity = $quantityAfter - $quantityBefore;

            return StockMovement::create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'type' => $type,
                'quantity' => $movementQuantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'user_id' => $userId,
                'note' => $note,
            ]);
        });
    }
}

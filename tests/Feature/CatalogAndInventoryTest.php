<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Barcode\Services\BarcodeGeneratorService;
use Modules\Catalog\Models\Brand;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\StockMovementService;
use Tests\TestCase;

class CatalogAndInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category_and_product(): void
    {
        $category = Category::create([
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Google',
            'slug' => 'google',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Pixel 8 Pro',
            'slug' => 'pixel-8-pro',
            'type' => 'simple',
            'sku' => 'GGL-PX8-P',
            'barcode' => '8809998887771',
            'buying_price' => 70000,
            'selling_price' => 85000,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Pixel 8 Pro',
            'sku' => 'GGL-PX8-P',
        ]);
    }

    public function test_stock_movement_service_updates_stock_and_ledger(): void
    {
        $warehouse = Warehouse::create([
            'name' => 'Dhaka Warehouse',
            'code' => 'WH-DAC',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Test Gaming Mouse',
            'slug' => 'test-gaming-mouse',
            'sku' => 'MSE-001',
            'buying_price' => 1000,
            'selling_price' => 1500,
        ]);

        $service = app(StockMovementService::class);
        $movement = $service->recordMovement(
            warehouseId: $warehouse->id,
            productId: $product->id,
            variantId: null,
            type: 'in',
            quantity: 50,
            note: 'Test stock addition'
        );

        $this->assertEquals(50, $movement->quantity_after);
        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'quantity' => 50,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'type' => 'in',
            'quantity' => 50,
            'quantity_before' => 0,
            'quantity_after' => 50,
        ]);
    }

    public function test_barcode_generator_service_returns_svg(): void
    {
        $service = new BarcodeGeneratorService;
        $code128Svg = $service->generateCode128Svg('8809998887771');
        $qrSvg = $service->generateQrSvg('8809998887771');

        $this->assertStringContainsString('<svg', $code128Svg);
        $this->assertStringContainsString('<svg', $qrSvg);
    }
}

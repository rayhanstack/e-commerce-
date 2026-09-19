<?php

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\Attribute;
use Modules\Catalog\Models\Brand;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\StockMovementService;

class CatalogDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default Warehouse
        $warehouse = Warehouse::firstOrCreate(
            ['code' => 'WH-MAIN'],
            [
                'name' => 'Central Warehouse',
                'phone' => '+8801700000000',
                'address' => 'Dhaka, Bangladesh',
                'is_default' => true,
                'is_active' => true,
            ]
        );

        // Categories
        $electronics = Category::firstOrCreate(
            ['slug' => 'electronics'],
            [
                'name' => 'Electronics',
                'description' => 'Gadgets, devices, and accessories',
                'is_active' => true,
            ]
        );

        $laptops = Category::firstOrCreate(
            ['slug' => 'laptops-computers'],
            [
                'name' => 'Laptops & Computers',
                'parent_id' => $electronics->id,
                'is_active' => true,
            ]
        );

        $fashion = Category::firstOrCreate(
            ['slug' => 'fashion-apparel'],
            [
                'name' => 'Fashion & Apparel',
                'is_active' => true,
            ]
        );

        // Brands
        $apple = Brand::firstOrCreate(['slug' => 'apple'], ['name' => 'Apple', 'is_active' => true]);
        $samsung = Brand::firstOrCreate(['slug' => 'samsung'], ['name' => 'Samsung', 'is_active' => true]);
        $nike = Brand::firstOrCreate(['slug' => 'nike'], ['name' => 'Nike', 'is_active' => true]);

        // Attributes
        $color = Attribute::firstOrCreate(['slug' => 'color'], ['name' => 'Color']);
        if ($color->values()->count() === 0) {
            $color->values()->createMany([
                ['value' => 'Space Gray', 'color_code' => '#535150'],
                ['value' => 'Silver', 'color_code' => '#E2E3E4'],
                ['value' => 'Midnight Blue', 'color_code' => '#192535'],
            ]);
        }

        $size = Attribute::firstOrCreate(['slug' => 'size'], ['name' => 'Size']);
        if ($size->values()->count() === 0) {
            $size->values()->createMany([
                ['value' => 'Medium (M)'],
                ['value' => 'Large (L)'],
                ['value' => 'Extra Large (XL)'],
            ]);
        }

        // Stock Service
        $stockService = app(StockMovementService::class);

        // Sample Product 1
        $p1 = Product::firstOrCreate(
            ['slug' => 'macbook-pro-16-m3-max'],
            [
                'category_id' => $laptops->id,
                'brand_id' => $apple->id,
                'name' => 'MacBook Pro 16" M3 Max',
                'type' => 'simple',
                'sku' => 'APP-MBP-16-M3',
                'barcode' => '8801234567891',
                'buying_price' => 320000.00,
                'selling_price' => 385000.00,
                'special_price' => 379000.00,
                'stock_alert_quantity' => 3,
                'unit' => 'pcs',
                'is_active' => true,
                'is_featured' => true,
                'short_description' => 'The ultimate pro laptop with M3 Max silicon.',
                'description' => 'Supercharged for pros with liquid retina XDR display and up to 22 hours of battery life.',
            ]
        );

        $stockService->recordMovement(
            warehouseId: $warehouse->id,
            productId: $p1->id,
            variantId: null,
            type: 'in',
            quantity: 15,
            referenceType: 'Initial Seed Stock',
            note: 'Seeded stock count'
        );

        // Sample Product 2
        $p2 = Product::firstOrCreate(
            ['slug' => 'nike-air-zoom-pegasus-40'],
            [
                'category_id' => $fashion->id,
                'brand_id' => $nike->id,
                'name' => 'Nike Air Zoom Pegasus 40',
                'type' => 'simple',
                'sku' => 'NKE-PEG-40',
                'barcode' => '8801234567892',
                'buying_price' => 8500.00,
                'selling_price' => 12500.00,
                'stock_alert_quantity' => 5,
                'unit' => 'pair',
                'is_active' => true,
                'is_featured' => true,
                'short_description' => 'Responsive running shoes for every run.',
            ]
        );

        $stockService->recordMovement(
            warehouseId: $warehouse->id,
            productId: $p2->id,
            variantId: null,
            type: 'in',
            quantity: 25,
            referenceType: 'Initial Seed Stock',
            note: 'Seeded stock count'
        );
    }
}

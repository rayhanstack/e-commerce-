<?php

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Controllers\Admin\AttributeController;
use Modules\Catalog\Http\Controllers\Admin\BrandController;
use Modules\Catalog\Http\Controllers\Admin\CategoryController;
use Modules\Catalog\Http\Controllers\Admin\ProductController;
use Modules\Catalog\Http\Controllers\Storefront\ProductCatalogController;

/*
|--------------------------------------------------------------------------
| Admin Catalog Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth:admin'])->prefix('admin/catalog')->name('admin.catalog.')->group(function () {
    // Categories
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit', 'update']);

    // Brands
    Route::resource('brands', BrandController::class)->except(['create', 'show', 'edit', 'update']);

    // Attributes
    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::post('attributes', [AttributeController::class, 'store'])->name('attributes.store');
    Route::post('attributes/{attribute}/values', [AttributeController::class, 'storeValue'])->name('attributes.values.store');
    Route::delete('attributes/{attribute}', [AttributeController::class, 'destroy'])->name('attributes.destroy');

    // Products
    Route::resource('products', ProductController::class);
});

/*
|--------------------------------------------------------------------------
| Storefront Product Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::get('/product/{slug}', [ProductCatalogController::class, 'show'])->name('product.show');
    Route::get('/category/{slug}', [ProductCatalogController::class, 'category'])->name('category.show');
});

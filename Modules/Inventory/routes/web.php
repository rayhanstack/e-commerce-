<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\Admin\StockAdjustmentController;
use Modules\Inventory\Http\Controllers\Admin\StockController;
use Modules\Inventory\Http\Controllers\Admin\WarehouseController;

/*
|--------------------------------------------------------------------------
| Admin Inventory Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth:admin'])->prefix('admin/inventory')->name('admin.inventory.')->group(function () {
    // Warehouses
    Route::resource('warehouses', WarehouseController::class)->only(['index', 'store', 'destroy']);

    // Stock Levels & Movements
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('stocks/movements', [StockController::class, 'movements'])->name('stocks.movements');

    // Stock Adjustments
    Route::get('adjustments/create', [StockAdjustmentController::class, 'create'])->name('adjustments.create');
    Route::post('adjustments', [StockAdjustmentController::class, 'store'])->name('adjustments.store');
});

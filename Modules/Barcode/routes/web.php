<?php

use Illuminate\Support\Facades\Route;
use Modules\Barcode\Http\Controllers\Admin\BarcodePrintController;

/*
|--------------------------------------------------------------------------
| Admin Barcode & Label Print Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth:admin'])->prefix('admin/barcode')->name('admin.barcode.')->group(function () {
    Route::get('print', [BarcodePrintController::class, 'index'])->name('print');
});

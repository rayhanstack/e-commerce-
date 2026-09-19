<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AdminDashboardController;
use Modules\Core\Http\Controllers\DevController;

/*
|--------------------------------------------------------------------------
| Admin Dashboard & Component Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    Route::get('/dev/components', [DevController::class, 'components'])->name('dev.components');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PosTerminalController;

/*
|--------------------------------------------------------------------------
| POS Terminal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth:admin'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosTerminalController::class, 'index'])->name('index');
    Route::get('search', [PosTerminalController::class, 'search'])->name('search');
    Route::post('checkout', [PosTerminalController::class, 'checkout'])->name('checkout');
});

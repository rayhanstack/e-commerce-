<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\ProfileController;

Route::middleware(['auth', 'verified'])->prefix('customer')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('customer.profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('customer.profile.update');
});

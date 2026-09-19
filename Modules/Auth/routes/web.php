<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AdminLoginController;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.post');
});
Route::middleware('guest:admin')->prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('admin.login.post');
});

Route::post('admin/logout', [AdminLoginController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth:admin');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

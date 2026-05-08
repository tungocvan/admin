<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\DashboardController;
use Modules\Admin\Http\Controllers\MenuController;
use Modules\Admin\Http\Controllers\ProfileController;
use Modules\Admin\Http\Controllers\AdminController;

Route::middleware(['web', 'auth:admin'])->prefix('admin')->name('admin.')->group(function () { // Sau này thêm middleware admin sau
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // === QUẢN LÝ MENU ===
    Route::prefix('menus')->name('menus.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('edit');
    });

    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/themes', [AdminController::class, 'themes'])->name('themes');
    Route::get('/admin-header', [AdminController::class, 'adminHeader'])->name('header');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::middleware(['web','auth:admin'])
    ->prefix('/admin/category')
    ->name('admin.category.')
    ->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
    });

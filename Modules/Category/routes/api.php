<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Api\CategoryController;

// Route::middleware('auth:sanctum')
//     ->controller(CategoryController::class)
//     ->prefix('category')
//     ->group(function () {
//         Route::get('/', 'index');
//     });

Route::prefix('category')
    ->controller(CategoryController::class)
    ->group(function () {
        Route::get('/', 'index');
    });
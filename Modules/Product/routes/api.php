<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\ProductController;

// Route::middleware('auth:sanctum')
//     ->controller(ProductController::class)
//     ->prefix('product')
//     ->group(function () {
//         Route::get('/', 'index');
//     });

Route::prefix('product')
    ->controller(ProductController::class)
    ->group(function () {
        Route::get('/', 'index');
    });
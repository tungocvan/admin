<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\Api\PostController;

// Route::middleware('auth:sanctum')
//     ->controller(PostController::class)
//     ->prefix('post')
//     ->group(function () {
//         Route::get('/', 'index');
//     });

Route::prefix('post')
    ->controller(PostController::class)
    ->group(function () {
        Route::get('/', 'index');
    });
<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\Api\SystemController;

// Route::middleware('auth:sanctum')
//     ->controller(SystemController::class)
//     ->prefix('system')
//     ->group(function () {
//         Route::get('/', 'index');
//     });

Route::prefix('system')
    ->controller(SystemController::class)
    ->group(function () {
        Route::get('/', 'index');
    });
<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Api\AdminController;


// Route::middleware('auth:sanctum')->controller(AdminController::class)->prefix('admin')->group(function(){
//         Route::get('/', 'index');              
// });

Route::prefix('admin')->controller(AdminController::class)->group(function(){
        Route::get('/', 'index');              
});
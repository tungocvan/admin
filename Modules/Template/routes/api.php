<?php

use Illuminate\Support\Facades\Route;
use Modules\Template\Http\Controllers\Api\TemplateController;


// Route::middleware('auth:sanctum')->controller(TemplateController::class)->prefix('template')->group(function(){
//         Route::get('/', 'index');              
// });

Route::prefix('template')->controller(TemplateController::class)->group(function(){
        Route::get('/', 'index');              
});
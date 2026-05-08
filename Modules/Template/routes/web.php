<?php

use Illuminate\Support\Facades\Route;
use Modules\Template\Http\Controllers\TemplateController;

Route::middleware(['web','auth'])->prefix('/template')->name('template.')->group(function(){
    Route::get('/dashboard', [TemplateController::class,'index'])->name('index');
    Route::get('/form-add', [TemplateController::class,'index'])->name('form-add');
    Route::get('/form-basic', [TemplateController::class,'index'])->name('form-basic');
    Route::get('/form-select', [TemplateController::class,'index'])->name('form-select');
    Route::get('/admin-template', [TemplateController::class,'adminTemplate'])->name('admin-template');
    Route::get('/components', [TemplateController::class,'index'])->name('components');
});

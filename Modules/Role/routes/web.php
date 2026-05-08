<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\RoleController;

// Route::middleware(['web','auth'])->prefix('/role')->name('role.')->group(function(){
//     Route::get('/', [RoleController::class,'index'])->name('index');
// });

Route::middleware(['web','auth:admin'])->prefix('admin')->name('admin.role.')->group(function () {
   Route::get('/role', [RoleController::class, 'index'])->name('index');
   Route::get('/role/create', [RoleController::class, 'create'])->name('create');
   Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('edit');
});

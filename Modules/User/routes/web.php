<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

// Route::middleware(['web','auth'])->prefix('/users')->name('users.')->group(function(){
//     Route::get('/', [UsersController::class,'index'])->name('index');
// });
Route::middleware(['web','auth:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::prefix('/system')->name('staff.')->group(function() {
            Route::get('/staff', [UserController::class, 'index'])->name('index');
            Route::get('/staff/create', [UserController::class, 'create'])->name('create');
            Route::get('/staff/{id}/edit', [UserController::class, 'edit'])->name('edit');
        });
});

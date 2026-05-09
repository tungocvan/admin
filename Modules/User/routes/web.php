<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

// Route::middleware(['web','auth'])->prefix('/users')->name('users.')->group(function(){
//     Route::get('/', [UsersController::class,'index'])->name('index');
// });
Route::middleware(['web','auth:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::prefix('/user')->name('user.')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        });
});

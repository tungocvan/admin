<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\PostController;


Route::middleware(['web', 'auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
    });
});

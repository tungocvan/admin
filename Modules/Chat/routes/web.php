<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\ChatController;

Route::middleware(['web','auth:admin'])
    ->prefix('/admin/chat')
    ->name('admin.chat.')
    ->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::view(
            '/internal-chat',
            'Chat::pages.chat.internal'
        );
}); 
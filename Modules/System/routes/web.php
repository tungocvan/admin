<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\SettingController;
use Modules\System\Http\Controllers\EnvConfigController;
use Modules\System\Http\Controllers\DatabaseController;
use Modules\System\Http\Controllers\SystemController;


Route::middleware(['web', 'auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('/system')->name('system.')->group(function () {
        Route::get('/', [SystemController::class, 'index'])
            ->name('index');
        Route::get('/modules', [SettingController::class, 'modules'])->name('modules');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/env', [EnvConfigController::class, 'index'])->name('settings.env');

        Route::prefix('/database')->name('database.')->group(function () {
            Route::get('/', [DatabaseController::class, 'index'])
                ->name('index');
            Route::get('/download/{filename}', [DatabaseController::class, 'download'])
                ->name('download')
                ->where('filename', '.*');
        });
    });
});

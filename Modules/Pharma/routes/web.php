<?php

use Illuminate\Support\Facades\Route;
use Modules\Pharma\Http\Controllers\PharmaController;

Route::middleware(['web','auth:admin'])
    ->prefix('/admin/pharma')
    ->name('pharma.')
    ->group(function () {
        Route::get('/tthc', [PharmaController::class, 'ThongTu'])->name('tthc');
        Route::get('/tracuu', [PharmaController::class, 'TraCuu'])->name('tra-cuu');
        Route::get('/hssp', [PharmaController::class, 'HoSoSanPham'])->name('ho-so-san-pham');
        Route::get('/tanduoc', [PharmaController::class, 'TanDuoc'])->name('tan-duoc');
        Route::get('/dongy', [PharmaController::class, 'DongY'])->name('dong-y');
});

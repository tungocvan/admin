<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\DashboardController;
//use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\ProductController;
use Modules\Admin\Http\Controllers\MenuController;
//use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\OrderController;
use Modules\Admin\Http\Controllers\SettingController;
use Modules\Admin\Http\Controllers\PostController;
use Modules\Admin\Http\Controllers\CustomerController;
//use Modules\Admin\Http\Controllers\CouponController;
//use Modules\Admin\Http\Controllers\RoleController;
//use Modules\Admin\Http\Controllers\StaffController;
// use Modules\Admin\Http\Controllers\AffiliateController;
// use Modules\Admin\Http\Controllers\HomeSettingsController;
// use Modules\Admin\Http\Controllers\BannerController;
// use Modules\Admin\Http\Controllers\FlashSaleController;
// use Modules\Admin\Http\Controllers\HeaderController;
// use Modules\Admin\Http\Controllers\FooterController;
use Modules\Admin\Http\Controllers\ProductCommissionController;
//use Modules\Admin\Http\Controllers\ChatController;
// use Modules\Admin\Http\Controllers\Auth\GoogleController;
use Modules\Admin\Http\Controllers\EnvConfigController;
use Modules\Admin\Http\Controllers\DatabaseController;

Route::middleware(['web'])->group(function () {

    // Auth Routes (Placeholder)
    // Route::get('/admin/login', [AuthController::class, 'login'])->name('admin.login');
    // Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google');
    // Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');



    // Protected Routes
    Route::middleware(['web','auth:admin'])->prefix('admin')->name('admin.')->group(function () { // Sau này thêm middleware admin sau
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        // Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/profile', [SettingController::class, 'profile'])->name('profile');
        Route::get('/modules', [SettingController::class, 'modules'])->name('modules');
        // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');
        // Route::get('/homepage-settings', [HomeSettingsController::class, 'index'])
        // ->name('home.settings');
        // Route::get('/header-settings', [HeaderController::class, 'index'])
        // ->name('header.settings');
        // Route::get('/footer-settings', [FooterController::class, 'index'])
        // ->name('footer.settings');
        // // Banner Manager
        // Route::get('/banners', [BannerController::class, 'index'])->name('banners');
        // // Flash Sale Manager
        // Route::get('/flash-sales', [FlashSaleController::class, 'index'])->name('flash-sales');


        // === QUẢN LÝ MENU ===
        Route::prefix('menus')->name('menus.')->group(function() {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::get('/create', [MenuController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('edit');
        });

        Route::prefix('products')->name('products.')->group(function() {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::prefix('{productId}')->group(function () {
                Route::get('commissions', [ProductCommissionController::class, 'index'])->name('commissions');
            });
        });

        // Route::prefix('product-categories')->name('product-categories.')->group(function() {
        //     Route::get('/', [CategoryController::class, 'index'])->name('index');
        //     Route::get('/create', [CategoryController::class, 'create'])->name('create');
        //     Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        // });



        Route::prefix('orders')->name('orders.')->group(function() {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::get('/{id}/print', [OrderController::class, 'print'])->name('print');
            Route::get('/{id}/pdf', [OrderController::class, 'exportPdf'])->name('pdf');
        });

        // Thêm vào trong group prefix 'admin'
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/env', [EnvConfigController::class, 'index'])->name('settings.env');

        Route::prefix('posts')->name('posts.')->group(function() {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::get('/create', [PostController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        });

        Route::prefix('customers')->name('customers.')->group(function() {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/create', [CustomerController::class, 'create'])->name('create');
            Route::get('/{id}', [CustomerController::class, 'show'])->name('show');

        });
        // Route::prefix('coupons')->name('coupons.')->group(function() {
        //     Route::get('/', [CouponController::class, 'index'])->name('index');
        //     Route::get('/create', [CouponController::class, 'create'])->name('create');
        //     Route::get('/{id}/edit', [CouponController::class, 'edit'])->name('edit');

        // });

        // Route::prefix('/system')->name('roles.')->group(function() {
        //     Route::get('/roles', [RoleController::class, 'index'])->name('index');
        //     Route::get('/roles/create', [RoleController::class, 'create'])->name('create');
        //     Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        // });

        // Route::prefix('/system')->name('staff.')->group(function() {
        //     Route::get('/staff', [StaffController::class, 'index'])->name('index');
        //     Route::get('/staff/create', [StaffController::class, 'create'])->name('create');
        //     Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('edit');
        // });

        Route::prefix('/database')->name('database.')->group(function() {
            Route::get('/', [DatabaseController::class, 'index'])
                ->name('index');
            Route::get('/download/{filename}', [DatabaseController::class, 'download'])
                ->name('download')
                ->where('filename', '.*'); // Quan trọng: Cho phép dấu chấm và các ký tự trong tên file
        });


    });




});




<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Api\CustomerLookupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Staff;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/track/{ticket?}', [Customer\OrderTrackingController::class, 'track'])
    ->middleware('throttle:30,1')
    ->name('track.order');

/*
|--------------------------------------------------------------------------
| Guest-only (auth forms)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Customer routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/orders/request', [Customer\OrderRequestController::class, 'store'])->name('orders.request');
    Route::patch('/orders/{order}/cancel', [Customer\OrderController::class, 'cancel'])->name('orders.cancel');
});

/*
|--------------------------------------------------------------------------
| Staff routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders/create', [Staff\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [Staff\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/search', [Staff\OrderController::class, 'search'])->name('orders.search');
    Route::get('/orders/{order}/edit', [Staff\OrderController::class, 'edit'])->name('orders.edit');
    Route::patch('/orders/{order}/status', [Staff\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/payment', [Staff\OrderController::class, 'updatePayment'])->name('orders.updatePayment');
    Route::get('/orders/{order}/repeat', [Staff\OrderController::class, 'repeat'])->name('orders.repeat');
    Route::patch('/orders/{order}/approve', [Staff\OrderController::class, 'approve'])->name('orders.approve');

    // AJAX
    Route::get('/api/customer-lookup', [CustomerLookupController::class, 'lookup'])->name('api.customer.lookup');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Sales
    Route::get('/sales', [Admin\SalesController::class, 'index'])->name('sales');
    Route::get('/sales/export-pdf', [Admin\SalesController::class, 'exportPdf'])->name('sales.exportPdf');
    Route::get('/sales/export-csv', [Admin\SalesController::class, 'exportCsv'])->name('sales.exportCsv');

    // Users
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Settings
    Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings');
    Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');

    // Deployment diagnostics (admin only)
    Route::get('/runtime-check', Admin\RuntimeCheckController::class)->name('runtimeCheck');
});

/*
|--------------------------------------------------------------------------
| Receipt (shared – staff & admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    Route::get('/receipt/{order}', [ReceiptController::class, 'download'])->name('receipt.download');
});


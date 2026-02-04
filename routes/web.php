<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('events.show');
// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/{category}', [CheckoutController::class, 'index'])->name('index');
        Route::post('/{category}/coupon', [CheckoutController::class, 'validateCoupon'])->name('coupon.validate');
        Route::post('/{category}', [CheckoutController::class, 'process'])->name('process');
        Route::get('/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('confirmation');
    });

    // Admin Panel
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/corridas', [App\Http\Controllers\Admin\RaceController::class, 'index'])->name('corridas.index');
        Route::get('/corridas/create', [App\Http\Controllers\Admin\RaceController::class, 'create'])->name('corridas.create');
        Route::post('/corridas', [App\Http\Controllers\Admin\RaceController::class, 'store'])->name('corridas.store');
        Route::get('/corridas/{event}/edit', [App\Http\Controllers\Admin\RaceController::class, 'edit'])->name('corridas.edit');
        Route::get('/corridas/{event}/dashboard', [App\Http\Controllers\Admin\RaceController::class, 'dashboard'])->name('corridas.dashboard');
        Route::put('/corridas/{event}', [App\Http\Controllers\Admin\RaceController::class, 'update'])->name('corridas.update');
        Route::delete('/corridas/{event}', [App\Http\Controllers\Admin\RaceController::class, 'destroy'])->name('corridas.destroy');

        // Athletes
        Route::get('/atletas', [App\Http\Controllers\Admin\AthleteController::class, 'index'])->name('athletes.index');
        Route::get('/atletas/{athlete}', [App\Http\Controllers\Admin\AthleteController::class, 'show'])->name('athletes.show');
        Route::get('/atletas/{athlete}/edit', [App\Http\Controllers\Admin\AthleteController::class, 'edit'])->name('athletes.edit');
        Route::put('/atletas/{athlete}', [App\Http\Controllers\Admin\AthleteController::class, 'update'])->name('athletes.update');

        // Sales
        Route::get('/vendas', [App\Http\Controllers\Admin\SalesController::class, 'index'])->name('sales.index');

        // Settings
        Route::get('/configuracoes', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('/configuracoes', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });

    // Client Hub
    Route::middleware(['role:cliente'])->prefix('hub')->name('client.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'client'])->name('dashboard');
    });
});

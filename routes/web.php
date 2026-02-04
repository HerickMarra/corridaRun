<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/calendario', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar');
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

    // Profile
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [App\Http\Controllers\ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/{category}', [CheckoutController::class, 'index'])->name('index');
        Route::post('/{category}/coupon', [CheckoutController::class, 'validateCoupon'])->name('coupon.validate');
        Route::post('/{category}', [CheckoutController::class, 'process'])->name('process');
        Route::get('/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('confirmation');
    });

    // Admin Panel
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Principal e Gestão de Admins (Apenas SuperAdmin e Admin)
        Route::middleware(['role:super-admin,admin'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        });

        // Corridas - Listagem e Dashboard (Acesso para Gestor/Organizador tb)
        Route::get('/corridas', [App\Http\Controllers\Admin\RaceController::class, 'index'])->name('corridas.index');
        Route::get('/corridas/{event}/dashboard', [App\Http\Controllers\Admin\RaceController::class, 'dashboard'])->name('corridas.dashboard');

        // Kanban Hub e Geral
        Route::get('/kanban/hub', [App\Http\Controllers\Admin\KanbanController::class, 'hub'])->name('kanban.hub');

        // Kanban por Corrida
        Route::get('/corridas/{event}/kanban', [App\Http\Controllers\Admin\KanbanController::class, 'index'])->name('corridas.kanban');
        Route::post('/corridas/{event}/kanban/columns', [App\Http\Controllers\Admin\KanbanController::class, 'storeColumn'])->name('kanban.columns.store');
        Route::post('/kanban/columns/update-order', [App\Http\Controllers\Admin\KanbanController::class, 'updateColumnOrder'])->name('kanban.columns.update-order');
        Route::post('/corridas/{event}/kanban/tasks', [App\Http\Controllers\Admin\KanbanController::class, 'storeTask'])->name('kanban.tasks.store');
        Route::put('/kanban/tasks/{task}', [App\Http\Controllers\Admin\KanbanController::class, 'updateTask'])->name('kanban.tasks.update');
        Route::post('/kanban/update-order', [App\Http\Controllers\Admin\KanbanController::class, 'updateOrder'])->name('kanban.update-order');

        // Corridas - Escrita (Apenas SuperAdmin e Admin)
        Route::middleware(['role:super-admin,admin'])->group(function () {
            Route::get('/corridas/create', [App\Http\Controllers\Admin\RaceController::class, 'create'])->name('corridas.create');
            Route::post('/corridas', [App\Http\Controllers\Admin\RaceController::class, 'store'])->name('corridas.store');
            Route::get('/corridas/{event}/edit', [App\Http\Controllers\Admin\RaceController::class, 'edit'])->name('corridas.edit');
            Route::put('/corridas/{event}', [App\Http\Controllers\Admin\RaceController::class, 'update'])->name('corridas.update');
            Route::delete('/corridas/{event}', [App\Http\Controllers\Admin\RaceController::class, 'destroy'])->name('corridas.destroy');

            // Admin Users Management (Apenas SuperAdmin e Admin)
            Route::resource('users', App\Http\Controllers\Admin\AdminUserController::class)->names('users');

            // API Routes for Admin UI
            Route::get('/api/corridas/search', [App\Http\Controllers\Admin\RaceController::class, 'search'])->name('api.corridas.search');

            // Email Templates Management
            Route::resource('emails', App\Http\Controllers\Admin\EmailTemplateController::class)
                ->names('emails')
                ->parameters(['emails' => 'user_email_template']);
        });

        // Pagamentos/Vendas e Detalhes Atletas (Admin, SuperAdmin)
        Route::middleware(['role:super-admin,admin'])->group(function () {
            Route::get('/atletas', [App\Http\Controllers\Admin\AthleteController::class, 'index'])->name('athletes.index');
            Route::get('/atletas/{athlete}', [App\Http\Controllers\Admin\AthleteController::class, 'show'])->name('athletes.show');
            Route::get('/atletas/{athlete}/edit', [App\Http\Controllers\Admin\AthleteController::class, 'edit'])->name('athletes.edit');
            Route::put('/atletas/{athlete}', [App\Http\Controllers\Admin\AthleteController::class, 'update'])->name('athletes.update');
            Route::get('/vendas', [App\Http\Controllers\Admin\SalesController::class, 'index'])->name('sales.index');
        });

        // Configurações e Dados Sensíveis (Apenas SuperAdmin)
        Route::middleware(['role:super-admin'])->group(function () {
            Route::get('/configuracoes', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
            Route::put('/configuracoes', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        });
    });

    // Client Hub
    Route::middleware(['role:cliente'])->prefix('hub')->name('client.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'client'])->name('dashboard');
    });
});

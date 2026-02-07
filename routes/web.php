<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/calendario', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar');
Route::get('/seja-parceiro', [HomeController::class, 'partner'])->name('partner');

Route::get('/event/{slug}', [EventController::class, 'show'])->name('events.show');

// Auth Routes - Apenas para usuários não autenticados
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

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
        Route::get('/payment-status/{order}', [CheckoutController::class, 'checkPaymentStatus'])->name('payment.status');
    });

    // Admin Panel
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Principal e Gestão de Admins (Apenas SuperAdmin e Admin)
        Route::middleware(['role:super-admin,admin'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

            // Webhook Logs
            Route::get('/webhook-logs', [App\Http\Controllers\Admin\WebhookLogController::class, 'index'])->name('webhook-logs.index');
            Route::get('/webhook-logs/{id}', [App\Http\Controllers\Admin\WebhookLogController::class, 'show'])->name('webhook-logs.show');
            Route::delete('/webhook-logs/{id}', [App\Http\Controllers\Admin\WebhookLogController::class, 'destroy'])->name('webhook-logs.destroy');
            Route::delete('/webhook-logs', [App\Http\Controllers\Admin\WebhookLogController::class, 'destroyAll'])->name('webhook-logs.destroy-all');
        });

        // Corridas - Listagem e Dashboard (Acesso para Gestor/Organizador tb)
        Route::get('/corridas', [App\Http\Controllers\Admin\RaceController::class, 'index'])->name('corridas.index');
        Route::get('/corridas/{event}/dashboard', [App\Http\Controllers\Admin\RaceController::class, 'dashboard'])->name('corridas.dashboard');

        // Tags de Evento
        Route::resource('tags', App\Http\Controllers\Admin\EventTagController::class)->except(['create', 'show', 'edit']);

        // Kanban Hub e Geral
        Route::get('/kanban/hub', [App\Http\Controllers\Admin\KanbanController::class, 'hub'])->name('kanban.hub');

        // Exportação
        Route::get('/corridas/{event}/export-participants', [App\Http\Controllers\Admin\RaceController::class, 'exportParticipants'])->name('corridas.export-participants');

        // Kanban por Corrida
        Route::get('/corridas/{event}/kanban', [App\Http\Controllers\Admin\KanbanController::class, 'index'])->name('corridas.kanban');
        Route::post('/corridas/{event}/kanban/columns', [App\Http\Controllers\Admin\KanbanController::class, 'storeColumn'])->name('kanban.columns.store');
        Route::post('/kanban/columns/update-order', [App\Http\Controllers\Admin\KanbanController::class, 'updateColumnOrder'])->name('kanban.columns.update-order');
        Route::delete('/kanban/columns/{column}', [App\Http\Controllers\Admin\KanbanController::class, 'deleteColumn'])->name('kanban.columns.delete');
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
            Route::get('/api/media', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('api.media.index');
            Route::post('/api/media', [App\Http\Controllers\Admin\MediaController::class, 'store'])->name('api.media.store');
            Route::delete('/api/media', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('api.media.destroy');

            // Email Templates Management
            Route::resource('emails', App\Http\Controllers\Admin\EmailTemplateController::class)
                ->names('emails')
                ->parameters(['emails' => 'user_email_template']);

            // Landing Pages Management
            Route::resource('landing-pages', App\Http\Controllers\Admin\LandingPageController::class)
                ->names('landing-pages');
        });

        // Pagamentos/Vendas e Detalhes Atletas (Admin, SuperAdmin)
        Route::middleware(['role:super-admin,admin'])->group(function () {
            Route::get('/atletas', [App\Http\Controllers\Admin\AthleteController::class, 'index'])->name('athletes.index');
            Route::get('/atletas/{athlete}', [App\Http\Controllers\Admin\AthleteController::class, 'show'])->name('athletes.show');
            Route::get('/atletas/{athlete}/edit', [App\Http\Controllers\Admin\AthleteController::class, 'edit'])->name('athletes.edit');
            Route::put('/atletas/{athlete}', [App\Http\Controllers\Admin\AthleteController::class, 'update'])->name('athletes.update');
            Route::post('/atletas/{athlete}/send-email', [App\Http\Controllers\Admin\AthleteController::class, 'sendEmail'])->name('athletes.send-email');
            Route::get('/vendas', [App\Http\Controllers\Admin\SalesController::class, 'index'])->name('sales.index');

            // Mail Marketing
            Route::prefix('marketing')->name('marketing.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\MailMarketingController::class, 'index'])->name('index');
                Route::get('/nova-campanha', [App\Http\Controllers\Admin\MailMarketingController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Admin\MailMarketingController::class, 'store'])->name('store');
                Route::get('/recipient-count', [App\Http\Controllers\Admin\MailMarketingController::class, 'getRecipientCount'])->name('recipient-count');
            });
        });

        // Configurações e Dados Sensíveis (Apenas SuperAdmin)
        Route::middleware(['role:super-admin'])->group(function () {
            Route::get('/configuracoes', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
            Route::put('/configuracoes', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        });
    });

    Route::middleware(['role:cliente'])->prefix('hub')->name('client.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'client'])->name('dashboard');
        Route::get('/minhas-inscricoes', [DashboardController::class, 'registrations'])->name('registrations');
        Route::get('/comprovante/{ticket}', [DashboardController::class, 'receipt'])->name('receipt');
    });
});

// Landing Pages Públicas (Coringa - manter no fim)
Route::get('/{slug}', [App\Http\Controllers\LandingPageController::class, 'show'])->name('lp.show');

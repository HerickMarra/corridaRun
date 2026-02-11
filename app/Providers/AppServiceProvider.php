<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

require_once app_path('Helpers/helpers.php');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Carbon::setLocale(config('app.locale'));
        setlocale(LC_TIME, config('app.locale') . '.utf8', config('app.locale'), 'pt_BR.utf-8', 'pt_BR', 'portuguese');
    }
}

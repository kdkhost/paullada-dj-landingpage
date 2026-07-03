<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        config()->set('app.locale', 'pt_BR');
        config()->set('app.timezone', 'America/Sao_Paulo');
        date_default_timezone_set('America/Sao_Paulo');
    }
}

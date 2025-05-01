<?php

namespace App\Providers;

use App\Models\Venda;
use App\Observers\VendaObserver;
use Illuminate\Support\ServiceProvider;

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
        Venda::observe(VendaObserver::class);
    }
}

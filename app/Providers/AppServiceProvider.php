<?php

namespace App\Providers;

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
        // NOTE:: i commented this because for some reason my subscriber event listener already discover, 
        // i dont know if it's a laravel or laravel backpack thing. but if i uncomment this and use
        // artisan event:list it run's twice
        // Event::subscribe(BillEventSubscriber::class);
    }
}

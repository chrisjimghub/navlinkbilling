<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomizeBackpackControllerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->app->bind(
            \Winex01\BackpackPermissionManager\Http\Controllers\UserCrudController::class, 
            \App\Http\Controllers\Admin\UserCrudController::class
        );
    }
}

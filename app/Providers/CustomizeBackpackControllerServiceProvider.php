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
        $this->app->bind(
            \Backpack\PermissionManager\app\Http\Controllers\UserCrudController::class, //this is package controller
            \App\Http\Controllers\Admin\UserCrudController::class //this should be your own controller
        );

    }
}

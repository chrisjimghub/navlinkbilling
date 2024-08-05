<?php

use App\Http\Middleware\CustomerInit;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customer',
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) CustomerInit::class,
    ),
    'namespace' => 'App\Http\Controllers\Customer',
], function () { 
    Route::crud('theme', 'ThemeCrudController');
    Route::crud('dashboard', 'DashboardCrudController');
Route::crud('billing-history', 'BillingHistoryCrudController');
}); 
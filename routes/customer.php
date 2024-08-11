<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customer',
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin'),
    ),
], function () { 
    // Group with 'App\Http\Controllers\Customer' namespace
    Route::group(['namespace' => 'App\Http\Controllers\Customer'], function() {
        Route::crud('theme', 'ThemeCrudController');
        Route::crud('dashboard', 'DashboardCrudController');
        Route::crud('billing-history', 'BillingHistoryCrudController');

        // logout
        Route::get('logout', 'Auth\LoginController@logout')->name('backpack.auth.logout');
        Route::post('logout', 'Auth\LoginController@logout');
    });

    // Group with 'Backpack\CRUD\app\Http\Controllers' namespace
    Route::group(['namespace' => 'Backpack\CRUD\app\Http\Controllers'], function() {
        
    });
    
});

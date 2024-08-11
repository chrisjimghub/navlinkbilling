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
    });

    // Group with 'Backpack\CRUD\app\Http\Controllers' namespace
    Route::group(['namespace' => 'Backpack\CRUD\app\Http\Controllers'], function() {
        Route::get('edit-account-info', 'MyAccountController@getAccountInfoForm')->name('backpack.account.info');
        Route::post('edit-account-info', 'MyAccountController@postAccountInfoForm')->name('backpack.account.info.store');
        Route::post('change-password', 'MyAccountController@postChangePasswordForm')->name('backpack.account.password');

        // logout
        Route::get('logout', 'Auth\LoginController@logout')->name('backpack.auth.logout');
        Route::post('logout', 'Auth\LoginController@logout');
    });
});

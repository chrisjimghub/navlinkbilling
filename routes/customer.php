<?php

use App\Http\Middleware\IfCustomer;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customer',
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin'),
        (array) IfCustomer::class,
    ),
    'namespace' => 'App\Http\Controllers\Customer',
], function () { 
    Route::crud('history', 'HistoryCrudController');
}); 
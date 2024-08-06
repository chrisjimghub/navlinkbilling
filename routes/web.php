<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/customer', function () {
    return redirect('customer/dashboard');
});

Route::get('/customer/logout', function () {
    return redirect(config('backpack.base.route_prefix') . '/logout');
});

require __DIR__.'/customer.php';
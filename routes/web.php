<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Backpack\CRUD\app\Http\Controllers',
    'middleware' => config('backpack.base.web_middleware', 'web'),
],
function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
    Route::post('login', 'Auth\LoginController@login');

    // forgot password
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('backpack.auth.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('backpack.auth.password.reset.token');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backpack.auth.password.email')->middleware('backpack.throttle.password.recovery:'.config('backpack.base.password_recovery_throttle_access'));

    // verify email
    Route::get('email/verify', 'Auth\VerifyEmailController@emailVerificationRequired')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', 'Auth\VerifyEmailController@verifyEmail')->name('verification.verify');
    Route::post('email/verification-notification', 'Auth\VerifyEmailController@resendVerificationEmail')->name('verification.send');
});


Route::get('/dashboard', function () {
    return redirect('customer/dashboard');
});

Route::get('/', function () {
    return redirect('login');
});

Route::get('/customer', function () {
    return redirect('customer/dashboard');
});


require __DIR__.'/customer.php';
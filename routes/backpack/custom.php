<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::post('switch-layout', function (Request $request) {
        $theme = 'backpack.theme-'.$request->get('theme', 'tabler').'::';

        // Session::put('backpack.ui.view_namespace', $theme);

        $data = [
            'backpack.ui.view_namespace' => $theme,
        ];

        if ($theme === 'backpack.theme-tabler::') {
            // Session::put('backpack.theme-tabler.layout', $request->get('layout', 'vertical'));
            $data['backpack.theme-tabler.layout'] = $request->get('layout', 'vertical'); 
        }

        $user = Auth::user();
        $user->theme = $data;
        $user->save();

        return Redirect::back();
    })->name('tabler.switch.layout');

    Route::crud('subscription', 'SubscriptionCrudController');
    Route::crud('planned-application-type', 'PlannedApplicationTypeCrudController');
    Route::crud('planned-application', 'PlannedApplicationCrudController');
    Route::crud('customer', 'CustomerCrudController');
    Route::crud('otc', 'OtcCrudController');
    Route::crud('contract-period', 'ContractPeriodCrudController');
    Route::crud('location', 'LocationCrudController');
    Route::crud('account-status', 'AccountStatusCrudController');
    Route::crud('account', 'AccountCrudController');
    Route::crud('billing', 'BillingCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('account-service-interruption', 'AccountServiceInterruptionCrudController');
    Route::crud('account-credit', 'AccountCreditCrudController');
    Route::crud('widget', 'WidgetCrudController');
    Route::crud('community-string', 'CommunityStringCrudController');
    Route::crud('olts', 'OltsCrudController');
    Route::crud('raisepon2', 'Raisepon2CrudController');
}); // this should be the absolute last line of this file
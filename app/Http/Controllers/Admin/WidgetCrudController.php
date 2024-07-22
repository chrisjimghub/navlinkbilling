<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CutOffAccountExport;
use App\Exports\InstallAccountExport;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class WidgetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WidgetCrudController extends CrudController
{
    protected function setupCutOffAccountsRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/cutOffAccounts', [
            'as'        => $routeName.'.cutOffAccounts',
            'uses'      => $controller.'@cutOffAccounts',
            'operation' => 'cutOffAccounts',
        ]);
    }

    protected function setupInstallAccountsRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/installAccounts', [
            'as'        => $routeName.'.installAccounts',
            'uses'      => $controller.'@installAccounts',
            'operation' => 'installAccounts',
        ]);
    }

    public function cutOffAccounts()
    {
        $name = __('app.dashboard.near_cut_off');

        return (new CutOffAccountExport(title: $name))->download($name.'-'.carbonNow().'.xlsx');
    }
    
    public function installAccounts()
    {
        $name = __('app.dashboard.install_account');
        
        return (new InstallAccountExport(title: $name))->download($name.'-'.carbonNow().'.xlsx');
    }
}

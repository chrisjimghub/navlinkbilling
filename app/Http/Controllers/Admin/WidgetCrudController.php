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
        return (new CutOffAccountExport)->download('cut-off-accounts-'.carbonNow().'.xlsx');
    }
    
    public function installAccounts()
    {
        return (new InstallAccountExport)->download('install-accounts-'.carbonNow().'.xlsx');
    }
}

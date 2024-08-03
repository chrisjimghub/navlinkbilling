<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DashboardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardCrudController extends CrudController
{
    protected function setupExportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment, [
            'as'        => $routeName.'.dashboard',
            'uses'      => $controller.'@dashboard',
            'operation' => 'dashboard',
        ]);
    }    

    public function dashboard()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title

        return view(backpack_view('customer.dashboard'), $this->data);
    }
}

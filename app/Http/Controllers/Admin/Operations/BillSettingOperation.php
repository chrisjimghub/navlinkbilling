<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait BillSettingOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupBillSettingRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/billSetting', [
            'as'        => $routeName.'.billSetting',
            'uses'      => $controller.'@billSetting',
            'operation' => 'billSetting',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBillSettingDefaults()
    {
        CRUD::allowAccess('billSetting');

        CRUD::operation('billSetting', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('top', 'bill_setting', 'view', 'crud::buttons.bill_setting');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function billSetting()
    {
        CRUD::hasAccessOrFail('billSetting');

        

        return;
    }
}
<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Billing;
use App\Rules\ValidAccountHarvest;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait HarvestedOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupHarvestedRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/harvested', [
            'as'        => $routeName.'.harvested',
            'uses'      => $controller.'@harvested',
            'operation' => 'harvested',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupHarvestedDefaults()
    {
        CRUD::allowAccess('harvested');

        Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');

        CRUD::operation('harvested', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('line', 'harvested', 'view', 'crud::buttons.harvested', 'beginning');
        });

    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function harvested($id)
    {
        CRUD::hasAccessOrFail('harvested');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'exists:billings,id',
                new ValidAccountHarvest(),
            ],
        ]);

        if ($validator->fails()) {
            return notyValidatorError($validator);
        }

        $billing = Billing::findOrFail($id);
        $billing->markAsHarvested();
        $billing->saveQuietly();

        alertSuccess('Item mark as harvest.');

        return response()->json([
            'success' => true,
        ]);
    }
}
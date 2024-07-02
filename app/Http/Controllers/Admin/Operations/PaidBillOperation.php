<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Billing;
use function Laravel\Prompts\alert;

use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait PaidBillOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupPaidBillRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/paidBill', [
            'as'        => $routeName.'.paidBill',
            'uses'      => $controller.'@paidBill',
            'operation' => 'paidBill',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupPaidBillDefaults()
    {
        CRUD::allowAccess('paidBill');

        CRUD::operation('paidBill', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'paid_bill', 'view', 'crud::buttons.paid_bill');
            CRUD::addButton('line', 'paid_bill', 'view', 'crud::buttons.paid_bill', 'beginning');
        });
    }

    public function paidBill($id)
    {
        $this->crud->hasAccessOrFail('paidBill');

        $model = Billing::findOrFail($id); 

        $model->billing_status_id = 1;
        $model->save();

        return true;
    }
}
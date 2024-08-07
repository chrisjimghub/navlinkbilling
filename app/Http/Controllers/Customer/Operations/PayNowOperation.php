<?php

namespace App\Http\Controllers\Customer\Operations;

use App\Models\Billing;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Luigel\Paymongo\Facades\Paymongo;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait PayNowOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupPayNowRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/pay-now', [
            'as'        => $routeName.'.payNow',
            'uses'      => $controller.'@payNow',
            'operation' => 'payNow',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupPayNowDefaults()
    {
        CRUD::allowAccess('payNow');

        CRUD::operation('payNow', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation(['list', 'show'], function () {
            CRUD::addButton('line', 'pay_now', 'view', 'crud::buttons.customer.pay_now', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function payNow($id)
    {
        CRUD::hasAccessOrFail('payNow');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'integer',
                Rule::in(Billing::unpaid()->pluck('id')->toArray()),
            ],
        ], [
            'id.required' => 'Invalid billing item.',
            'id.exists' => 'The selected billing item does not exist.', 
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            alertValidatorError($validator);
            return redirect()->back();
        }

        dd('wip');

        // $billing = Billing::findOrFail($id);

        // $link = Paymongo::link()->create([
        //     'amount' => $billing->total,
        //     'description' => 'Bill for the Month of '.$billing->month.' '.$billing->year,
        //     'remarks' => 'laravel-paymongo-link'
        // ]);

        // if ($link) {
        //     debug($link);

        //     $billing->paymongo_reference_number = $link->reference_number;
        //     $billing->billing_status_id = 3; // 3 = Pending...
        //     $billing->saveQuietly();

        //     return redirect($link->checkout_url);
        // }

        alertError('Whoops, something went wrong, Please contact administrator.');
        return redirect()->back();
    }
}
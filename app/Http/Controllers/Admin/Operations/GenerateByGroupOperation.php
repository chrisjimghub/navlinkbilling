<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\BillingGrouping;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Http\Controllers\Admin\Traits\GenerateBill;
use App\Http\Controllers\Admin\Traits\BillingPeriod;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait GenerateByGroupOperation
{
    use FetchOptions;
    use BillingPeriod;
    use GenerateBill;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupGenerateByGroupRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/generate-by-group', [
            'as'        => $routeName.'.generateByGroup',
            'uses'      => $controller.'@generateByGroup',
            'operation' => 'generateByGroup',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupGenerateByGroupDefaults()
    {
        CRUD::allowAccess('generateByGroup');

        CRUD::operation('generateByGroup', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('top', 'generate_by_group', 'view', 'crud::buttons.generate_by_group');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function generateByGroup()
    {
        CRUD::hasAccessOrFail('generateByGroup');
        
        //Validate request data
        $validator = Validator::make(request()->all(), [
            'generate_bill' => [
                'required',
                'array', // We have array rule so dont forget to Extracts the keys from the array below
                Rule::in(array_keys($this->billingGroupingLists())), 
            ],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return notyValidatorError($validator);
        }

        $ids = request()->generate_bill;

        $this->generateBill(
            BillingGrouping::whereIn('id', $ids)->get()
        );

        return notySuccess('Bill generated successfully.');
    }
}
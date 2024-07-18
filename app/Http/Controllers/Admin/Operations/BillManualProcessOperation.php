<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait BillManualProcessOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupManualProcessRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/manualGenerateBill', [
            'as'        => $routeName.'.manualGenerateBill',
            'uses'      => $controller.'@manualGenerateBill',
            'operation' => 'manualGenerateBill',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupManualProcessDefaults()
    {
        CRUD::allowAccess([
            'manual_generate_bill'
        ]);

        CRUD::operation('manualProcess', function () {
            $this->crud->enableBulkActions();
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('top', 'manual_process', 'view', 'crud::buttons.manual_process');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function manualGenerateBill()
    {
        CRUD::hasAccessOrFail('manual_generate_bill');

        //Validate request data
        $validator = Validator::make(request()->all(), [
            'generate_bill' => [
                'required',
                'array',
            ],
            'generate_bill.*' => [
                'in:fiber,p2p',
            ],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }

        foreach (request()->generate_bill as $type) {
            if ($type == 'fiber') {
                Artisan::call('bill:generate', ['--fiber' => true]);
            }else if ($type == 'p2p') {
                Artisan::call('bill:generate', ['--p2p' => true]);
            }
        }

        return response()->json([
            'msg' => '<strong>'.__('Generating Bill').'</strong><br>'.__('Bill generated successfully.'),
        ]);
    }
}
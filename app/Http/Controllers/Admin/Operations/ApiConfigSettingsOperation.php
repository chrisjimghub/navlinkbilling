<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\Traits\Raisepon2Api;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait ApiConfigSettingsOperation
{
    use Raisepon2Api;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupApiConfigSettingsRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/api-config-settings', [
            'as'        => $routeName.'.apiConfigSettings',
            'uses'      => $controller.'@apiConfigSettings',
            'operation' => 'apiConfigSettings',
        ]);


        Route::post($segment.'/api-test-connection', [
            'as'        => $routeName.'.apiTestConnection',
            'uses'      => $controller.'@apiTestConnection',
            'operation' => 'apiTestConnection',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupApiConfigSettingsDefaults()
    {
        CRUD::allowAccess('apiConfigSettings');

        CRUD::operation('apiConfigSettings', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('top', 'api_config_settings', 'view', 'crud::buttons.api_config_settings');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function apiConfigSettings()
    {
        CRUD::hasAccessOrFail('apiConfigSettings');
        
        // Validate request data
        $validator = Validator::make(request()->all(), $this->rules());

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }


        Setting::set('raisepon_url', request()->url);
        Setting::set('raisepon_username', request()->username);
        Setting::set('raisepon_password', request()->password);


        return response()->json([
            'msg' => '<strong>'.__('Success').'</strong><br>'.__('API config settings saved successfully.'),
        ]);
    }

    public function apiTestConnection()
    {
        CRUD::hasAccessOrFail('apiConfigSettings');

        // Validate request data
        $validator = Validator::make(request()->all(), $this->rules());

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422); // HTTP status code for Unprocessable Entity
        }

        $response = $this->testConnection(
            baseUrl: request()->url,
            username: request()->username,
            password: request()->password
        );

        if ($response === true) {
            // Authenticated
            return response()->json([
                'msg' => '<strong>' . __('200') . '</strong><br>' . __('Test Connected Successfully.'),
            ]); // HTTP status code for success
        }

        return response()->json([
            'error' => '<strong>' . __('Failed') . '</strong><br>' . __($response),
        ]); // HTTP status code for unauthorized or failure
    }
    
    private function rules()
    {
        return [
            'url' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait GeneratePortalAccountOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupGeneratePortalAccountRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/generate-portal-account', [
            'as'        => $routeName.'.generatePortalAccount',
            'uses'      => $controller.'@generatePortalAccount',
            'operation' => 'generatePortalAccount',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupGeneratePortalAccountDefaults()
    {
        CRUD::allowAccess('generatePortalAccount');

        CRUD::operation('generatePortalAccount', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');

        CRUD::operation('list', function () {
            CRUD::addButton('line', 'generate_portal_account', 'view', 'crud::buttons.generate_portal_account', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function generatePortalAccount($id)
    {
        CRUD::hasAccessOrFail('generatePortalAccount');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $customer = Customer::findOrFail($id);

        if (!$customer) {
            return notyError('The selected customer is invalid.');
        }

        $validator = Validator::make(['email' => $customer->email], [
            'email' => [
                'required',
                'email',
            ],
        ]);

        if ($validator->fails()) {
            return notyValidatorError($validator);
        }

        $email = $customer->email;
        $password = Str::password(8, true, true, false, false);

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $customer->full_name;
        $user->password = Hash::make($password);
        $user->customer_id = $customer->id;
        $user->saveQuietly();

        return array_merge(notySuccess('Customer portal account generated successfully.'), [
            'email' => $email,
            'password' => $password
        ]);
    }
}
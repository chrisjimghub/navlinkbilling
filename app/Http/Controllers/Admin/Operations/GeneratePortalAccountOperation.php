<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
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

        // validate email customer if it alreaddy exist in users
        // generate password then hash save it.
        // show dialogue or modal the username and password

        debug($id);

        return flashSuccess('Customer portal account generated successfully.');
    }
}
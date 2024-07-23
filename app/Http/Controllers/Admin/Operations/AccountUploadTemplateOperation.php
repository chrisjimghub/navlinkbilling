<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait AccountUploadTemplateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupAccountUploadTemplateRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/account-upload-template', [
            'as'        => $routeName.'.accountUploadTemplate',
            'uses'      => $controller.'@accountUploadTemplate',
            'operation' => 'accountUploadTemplate',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupAccountUploadTemplateDefaults()
    {
        CRUD::allowAccess('accountUploadTemplate');

        CRUD::operation('accountUploadTemplate', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'account_upload_template', 'view', 'crud::buttons.account_upload_template');
            // CRUD::addButton('line', 'account_upload_template', 'view', 'crud::buttons.account_upload_template');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function accountUploadTemplate()
    {
        CRUD::hasAccessOrFail('accountUploadTemplate');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = CRUD::getTitle() ?? 'Account Upload Template '.$this->crud->entity_name;

        // load the view
        return view('crud::operations.account_upload_template', $this->data);
    }
}
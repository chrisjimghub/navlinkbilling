<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Exports\UploadTemplateExport;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait UploadTemplateExportOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupUploadTemplateExportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/upload-template-export', [
            'as'        => $routeName.'.uploadTemplateExport',
            'uses'      => $controller.'@uploadTemplateExport',
            'operation' => 'uploadTemplateExport',
        ]);
    }
    
    // default: just override this to customize
    public function uploadTemplateExport()
    {
        $this->crud->hasAccessOrFail('import');

        $fileName = 'Test Upload Template.xlsx';
        
        $headers = $this->getColumns('users');

        return (new UploadTemplateExport($headers))->download($fileName);
    }
}
<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Customer;
use App\Exports\UploadTemplateExport;
use Illuminate\Support\Facades\Route;
use App\Exports\AccountOptionsColumnExport;
use App\Models\Traits\SchemaTableColumn;

trait AccountUploadTemplateExportOperation
{
    use SchemaTableColumn;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupAccountUploadTemplateExportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/account-upload-template-export', [
            'as'        => $routeName.'.accountUploadTemplateExport',
            'uses'      => $controller.'@accountUploadTemplateExport',
            'operation' => 'accountUploadTemplateExport',
        ]);

        Route::get($segment.'/account-option-column-export', [
            'as'        => $routeName.'.accountOptionColumnExport',
            'uses'      => $controller.'@accountOptionColumnExport',
            'operation' => 'accountOptionColumnExport',
        ]);
    }

    protected function accountOptionColumnExport()
    {
        $this->crud->hasAccessOrFail('import');

        $fileName = 'Account Options Column.xlsx';
        
        return (new AccountOptionsColumnExport)->download($fileName);
    }

    protected function accountUploadTemplateExport()
    {
        $this->crud->hasAccessOrFail('import');

        $fileName = 'Account Upload Template.xlsx';
        
        $headers = $this->getColumns('accounts');

        $headers[] = 'one_time_charge';
        $headers[] = 'contract_period';

        // Modify headers by reference to remove '_id' suffix
        foreach ($headers as &$header) {
            if (str_ends_with($header, '_id')) {
                $header = substr($header, 0, -3); // Remove '_id' from the end
            }
        }
        unset($header); // Break the reference

        $entries = Customer::all();

        return (new UploadTemplateExport($headers, $entries))->download($fileName);
    }
}
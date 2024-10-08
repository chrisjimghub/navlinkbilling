<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

/**
 * Class ReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReportCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    use CrudExtend;
    use FilterOperation;
    use ExportOperation;
    use FetchOptions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Temp::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/report');
        CRUD::setEntityNameStrings('report', 'reports');

        $this->userPermissions('reports');
    }

    protected function setupListOperation()
    {
        $this->filterQueries(function ($query) {
            // TODO::
        });
    }

    public function setupFilterOperation()
    {
        $this->crud->field([
            'name'  => 'month',
            'type'  => 'select_from_array',
            'options' => $this->monthLists(),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);

        $this->crud->field([
            'name'  => 'year',
            'type'  => 'select_from_array',
            'options' => $this->yearLists(2020),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);
    }
}

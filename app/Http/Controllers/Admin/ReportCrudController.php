<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Http\Requests\ReportRequest;
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
    // use ExportOperation;
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
    }

    protected function setupListOperation()
    {
        $this->filterQueries(function ($query) {
            // $this->billingFilterQueries($query);
        });

        CRUD::setFromDb(); 
        
    }

    public function setupFilterOperation()
    {
        $this->crud->field([
            'name' => 'period',
            'label' => __('Billing Period'),
            'type' => 'date_range',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->field([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select_from_array',
            'options' => $this->billingStatusLists([4,5]),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->field([
            'name' => 'type',
            'label' => __('Type'),
            'type' => 'select_from_array',
            'options' => $this->billingTypeLists(3),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);
    }

    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // 

        return view(backpack_view('admin.report'), $this->data);
    }
}

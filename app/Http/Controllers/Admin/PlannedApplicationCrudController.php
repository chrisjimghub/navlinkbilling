<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location;
use App\Models\PlannedApplication;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\Operations\ExportOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PlannedApplicationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PlannedApplicationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    use ExportOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PlannedApplication::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/planned-application');
        CRUD::setEntityNameStrings('planned application', 'planned applications');
    
        $this->userPermissions();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->relationshipColumn('location');
        $this->relationshipColumn('plannedApplicationType');
        $this->crud->column('mbps');
        $this->currencyColumn('price');

        $this->crud->modifyColumn('price', [
            'decimals' => false
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {   
        CRUD::setValidation([
            'plannedApplicationType' => 'required|integer|min:1',
            'location' => 'required|integer|min:1',
            'mbps' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);
        CRUD::setFromDb(); // set fields from db columns.
        
        $this->crud->removeField('planned_application_type_id');
        $this->crud->removeField('location_id');

        $this->crud->removeFields([
            'planned_application_type_id',
            'location_id',
        ]);

        $this->crud->field('plannedApplicationType')->label(__('app.planned_application_type'))->before('mbps');
        $this->crud->field('location')->before('mbps');
    
        $this->currencyFormatField('price');
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupFetchOptGroupOptionRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/fetchOptGroupOption', [
            'as'        => $routeName.'.fetchOptGroupOption',
            'uses'      => $controller.'@fetchOptGroupOption',
            'operation' => 'fetchOptGroupOption',
        ]);
    }

    public function fetchOptGroupOption()
    {
        $locations = Location::orderBy('name')->get();

        $data = [];

        foreach ($locations as $location) {

            $plannedApps = PlannedApplication::where('location_id', $location->id)->get();

            foreach ($plannedApps as $plan) {
                $data[$location->name][$plan->id] = $plan->option_label;
            }
        }

        return $data;
    }
}

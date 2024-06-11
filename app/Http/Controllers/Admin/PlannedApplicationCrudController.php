<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlannedApplicationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
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
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
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
            'planned_application_type_id' => 'required|integer|min:1',
            'barangay_id' => 'required|integer|min:1',
        ]);
        CRUD::setFromDb(); // set fields from db columns.
        
        $this->crud->modifyField('barangay_id', [
            'type'=> 'select',
        ]);

            // TODO:: fix error later
        $this->crud->modifyField('planned_application_type_id', [
            'type'      => 'select',
            // optional
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'plannedApplicationType',

            // optional - manually specify the related model and attribute
            'model'     => "App\Models\PlannedApplicationType", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
        ]);

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
}

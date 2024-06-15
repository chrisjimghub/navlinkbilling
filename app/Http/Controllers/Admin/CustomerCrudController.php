<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\PlannedApplicationType;
use App\Http\Requests\CustomerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use PlannedApplicationType;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('customer', 'customers');
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
        
        $this->crud->removeColumn('user_id');

        $this->crud->modifyColumn('subscription_id', [
            'type' => 'select'
        ]);


        $this->plannedApplicationTypeColumn();
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
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'date',
            'contact_number' => 'required',
            'email' => ['nullable', 'email'],
            'bill_recipients' => 'required|min:2',
            'planned_application_type_id' => 'required|integer|min:1',
            'subscription_id' => 'required|integer|min:1',
        ]);
        
        CRUD::setFromDb(); // set fields from db columns.

        $this->crud->modifyField('notes', [
            'type' => 'summernote',
        ]);

        $this->crud->removeField('user_id');

        $this->crud->modifyField('subscription_id', [
            'type' => 'select'
        ]);
        
        $this->plannedApplicationTypeField();


        // TODO:: show application type of installation such as 10 mbs -- 9999 and etc. but use onchange event and filter it using barangay and planned application type he choose above
        /* 
            NOTE:: use radio button
            ex:
                10 Mbps ----- 999
                12 Mbps ----- 1199
                15 Mbps ----- 1,299
                20 Mbps ----- 1,399
                30 Mbps ----- 1,599
                etc...
        */
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

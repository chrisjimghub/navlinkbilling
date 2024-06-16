<?php

namespace App\Http\Controllers\Admin;

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

        $this->crud->removeColumns($this->removeFK());
        
        $this->crud->addColumn([
            'label' => 'Planned Application Type',
            'name' => 'plannedApplicationType',
            'limit' => 100
        ])->beforeColumn('notes');

        $this->crud->addColumn('subscription')->beforeColumn('notes');
        
        foreach ($this->checkboxFields() as $name => $label) {
            $this->crud->column([
                'label' => $label,
                'name' => $name,
                'type'     => 'closure',
                'function' => function($entry) use($name) {
    
                    return $entry->{$name}()->pluck('name')->implode("<br>");
                },
                'escaped' => false,
            ])->before('notes');
        }

        $this->crud->modifyColumn('signature', [
            'type' => 'image',
                'height' => '150px',
            'width'  => '150px',
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
        // TODO:: add validation for OTC and contract period
        // TODO:: add tabs
        // TODO:: fix(refer sample file) input form view

        CRUD::setValidation([
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'date',
            'contact_number' => 'required',
            'email' => ['nullable', 'email'],
            'bill_recipients' => 'required|min:2',
            'plannedApplicationType' => 'required|integer|min:1',
            'subscription' => 'required|integer|min:1',
        ]);
        
        CRUD::setFromDb(); // set fields from db columns.

        $this->crud->removeFields($this->removeFK());

        $this->crud->modifyField('notes', ['type' => 'textarea']);
        $this->crud->modifyField('date_of_birth', ['type' => 'date']);        


        $this->crud->field('plannedApplicationType')->before('notes');
        $this->crud->field('subscription')->before('notes');
        
        foreach ($this->checkboxFields() as $name => $label) {
            $this->crud->field([
                'label' => $label,
                'name' => $name,
                'type' => 'checklist',
                'number_of_columns' => 1,
            ])->before('notes');
        }

        // TODO:: signature
        $this->crud->field([
            'name' => 'signature',
            'label' => 'Please sign here',
            'type' => 'signature',
            'view_namespace' => 'signature-field-for-backpack::fields',
        ]);

        // dd($this->crud);
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

    private function checkboxFields()
    {   
        return [
            'otcs' => 'One-Time Charge',
            'contractPeriods' => 'Contact Periods',
        ];
    }

    private function removeFK()
    {
        return [
            'user_id',
            'subscription_id',
            'planned_application_type_id',
        ];
    }
}

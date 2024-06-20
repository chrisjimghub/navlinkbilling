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
        
        $this->crud->modifyColumn('signature', [
            'type' => 'image',
            'height' => '150px',
            'width'  => '150px',
        ]);
        

        // $this->crud->with('plannedApplication');

        // $this->crud->removeColumns($this->removeFK());
        
        // $this->crud->column([
        //     'label' => 'Planned Application Type',
        //     'name' => 'plannedApplicationType',
        //     'limit' => 100
        // ])->before('planned_application_id');

        // TODO:: remove later once transfered
        // $this->crud->addColumn('subscription')->beforeColumn('notes');
        
        // foreach ($this->checkboxFields() as $name => $label) {
        //     $this->crud->column([
        //         'label' => $label,
        //         'name' => $name,
        //         'type'     => 'closure',
        //         'function' => function($entry) use($name) {
    
        //             return $entry->{$name}()->pluck('name')->implode("<br>");
        //         },
        //         'escaped' => false,
        //     ])->before('notes');
        // }

        

        // $this->crud->modifyColumn('planned_application_id', [
        //     'type' => 'closure',
        //     'function' => function($entry) {
        //         return $entry->plannedApplication->mbpsPrice;
        //     },
        // ]);
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
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'date',
            'contact_number' => 'required',
            'email' => 'nullable|email',


            // TODO:: remove later once transfered
            // 'plannedApplicationType' => 'required|integer|min:1',
            // 'planned_application_id' => 'required|integer|min:1',
            // 'subscription' => 'required|integer|min:1',
            // 'bill_recipients' => 'required|min:2',
        ]);
        
        CRUD::setFromDb(); // set fields from db columns.

        $this->crud->field([
            'name' => 'signature',
            'label' => 'Please sign here',
            'type' => 'signature',
            'view_namespace' => 'signature-field-for-backpack::fields',
        ]);

        $this->crud->modifyField('notes', ['type' => 'textarea']);
        $this->crud->modifyField('date_of_birth', ['type' => 'date']);        


        // TODO:: dont remove comments, remove later once transfered
        // $this->crud->removeFields($this->removeFK());

        // Planned Application Type
        // $this->crud->field('plannedApplicationType')->label('Planned Application Type')->before('planned_application_id');
        // $this->crud->field('subscription')->before('notes');
        
        // foreach ($this->checkboxFields() as $name => $label) {
        //     $this->crud->field([
        //         'label' => $label,
        //         'name' => $name,
        //         'type' => 'checklist',
        //         'number_of_columns' => 1,
        //     ])->before('notes');
        // }

        // // Planned Application
        // $this->crud->modifyField('planned_application_id', [
        //     'type'      => 'select_grouped', //https://github.com/Laravel-Backpack/CRUD/issues/502
        //     // 'name'      => '',
        //     'entity'    => 'plannedApplication',

        //     'attribute' => 'mbpsPrice', // accessor

        //     'model' => 'App\Models\PlannedApplication',  // Parent model
            
        //     'group_by'  => 'location', // the relationship to entity you want to use for grouping
        //     'group_by_attribute' => 'name', // the attribute on related model, that you want shown
        //     'group_by_relationship_back' => 'plannedApplications', // relationship from related model back to this model

        //     'relation_type' => 'BelongsTo',
        // ]); 

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

    // TODO:: remove later once transfered
    // private function checkboxFields()
    // {   
    //     return [
    //         'otcs' => 'One-Time Charge',
    //         'contractPeriods' => 'Contact Periods',
    //     ];
    // }

    // private function removeFK()
    // {
    //     return [
    //         'user_id',
    //         'subscription_id',
    //         'planned_application_type_id',
    //     ];
    // }
}

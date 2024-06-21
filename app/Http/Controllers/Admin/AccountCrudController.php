<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccountRequest;
use Backpack\CRUD\app\Library\Widget;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AccountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccountCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Account::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/account');
        CRUD::setEntityNameStrings('account', 'accounts');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->with('plannedApplication');

        $this->crud->column([
            'name' => 'customer.full_name',
            'label' => 'Acount Name (Customer)',
        ]);

        $this->crud->column([
            'name' => 'plannedApplication.columnDisplay',
            'label' => 'Planned Applicaton',
            'limit' => 100
        ]);

        $this->crud->column('subscription');

        $this->crud->column([
            'name' => 'otcs',
            'label' => 'One-Time Charge'
        ]);

        $this->crud->column([
            'name' => 'contractPeriods',
            'label' => 'contract Periods',
        ]);

        $this->crud->column('installed_date');
        $this->crud->column('installed_address');
        $this->crud->column('notes');
        
        $this->crud->column([
            'name' => 'accountStatus',
            'label' => 'Account Status',
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    return $entry->accountStatus->badge_css;
                },
            ],
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
        Widget::add()->type('script')->content(asset('assets/js/admin/forms/planned_application.js'));

        $this->crud->setValidation(AccountRequest::class);
        
        foreach ([
            'customer_id' => 'Account Name (Customer)',
            'planned_application_id' => 'Planned Application',
            'subscription' => 'Subscription',
        ] as $name => $label) {
            $this->crud->field([
                'name' => $name,
                'label' => $label,
            ]);
        }

        $this->crud->modifyField('customer_id', [
            'attribute' => 'full_name', // accessor
            'allows_null' => true,
        ]);


        $this->crud->modifyField('planned_application_id', [
            'type'      => 'select_grouped_planned_application', //https://github.com/Laravel-Backpack/CRUD/issues/502
            'entity'    => 'plannedApplication',

            'attribute' => 'optionLabel', // accessor

            'model' => 'App\Models\PlannedApplication',  // Parent model
            
            'group_by'  => 'location', // the relationship to entity you want to use for grouping
            'group_by_attribute' => 'name', // the attribute on related model, that you want shown
            'group_by_relationship_back' => 'plannedApplications', // relationship from related model back to this model

            'relation_type' => 'BelongsTo',

            // custom option attribute, i created a custome field that append a custom model attribute
            'data-location' => 'dataLocation',
        ]); 


        foreach ([
            'otcs' => 'One-Time Charge',
            'contractPeriods' => 'Contract Periods',
        ] as $name => $label) {
            $this->crud->field([
                'label' => $label,
                'name' => $name,
                'type' => 'checklist',
                'number_of_columns' => 1,
            ]);
        }

        $this->crud->field([
            'name' => 'installed_date',
            'type' => 'date'
        ]);

        $this->crud->field([
            'name' => 'installed_address',
            'type' => 'text'
        ]);

        $this->crud->field([
            'name' => 'notes',
            'type' => 'textarea',
        ]);

        $this->crud->field([
            'name' => 'accountStatus',
            'label' => 'Account Status'
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
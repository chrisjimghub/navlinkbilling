<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Requests\BillingGroupingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BillingGroupingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BillingGroupingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\BillingGrouping::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/billing-grouping');
        CRUD::setEntityNameStrings('billing grouping', 'billing groupings');
        
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
        CRUD::setFromDb(); 
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
            'name' => 'required|min:2',
            // TODO::
        ]);
        
        $this->crud->field('name');
        
        $this->crud->field([
            'name'  => 'separator',
            'type'  => 'custom_html',
            'value' => '<label>Automate Process</label>',
            'wrapper' => [
                'class' => 'form-group col-sm-12 mb-n1'
            ]
        ]);

        foreach ([
            'auto_generate_bill',
            'auto_send_bill_notification',
            'auto_send_cut_off_notification'
        ] as $name) {
            $this->crud->field([
                'name' => $name,
                'attributes' => [
                    'class' => 'ml-n3'
                ],
                'wrapper' => [
                    'class' => 'form-group col-sm-12 mb-2'
                ]
            ]);
        }

        $this->crud->field('billingPeriod')->label(__('Billing Period'));

        $this->crud->field([
            'name' => 'day_start',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        
        $this->crud->field([
            'name' => 'day_end',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->field([
            'name' => 'day_cut_off',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->field([
            'name' => 'bill_generate_days_before_end_of_billing_period',
        ]);

        $this->crud->field([
            'name' => 'bill_notification_days_after_the_bill_created',
        ]);

        $this->crud->field([
            'name' => 'bill_cut_off_notification_days_before_cut_off_date',
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

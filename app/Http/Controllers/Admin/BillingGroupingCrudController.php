<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\Widget;
use App\Http\Requests\BillingGroupingRequest;
use App\Http\Controllers\Admin\Traits\CrudExtend;
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
        Widget::add()->type('script')->content('assets/js/admin/forms/billing_grouping.js');

        CRUD::setValidation([
            'name' => $this->validateUniqueRule(),
            // TODO::
        ]);
        
        $this->crud->field('name');

        $this->crud->field('billingCycle')->label(__('Billing Cycle'));

        $this->crud->field([
            'name' => 'day_start',
            'label' => 'Date start',
            'type' => 'select_from_array',
            'options' => $this->dayOptions(),
            'allows_null' => true,
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        
        $this->crud->field([
            'name' => 'day_end',
            'label' => 'Date end',
            'type' => 'select_from_array',
            'options' => $this->dayOptions(),
            'allows_null' => true,
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->field([
            'name' => 'day_cut_off',
            'label' => 'Date cut off',
            'type' => 'select_from_array',
            'options' => $this->cutOffOptions(),
            'allows_null' => true,
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

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

        $this->crud->field([
            'name' => 'bill_generate_days_before_end_of_billing_period',
            'label' => __('When should the bill be auto-generated?'),
            'type' => 'select_from_array',
            'options' => $this->billGenerateOptions(),
            'allows_null' => true,
        ]);

        $this->crud->field([
            'name' => 'bill_notification_days_after_the_bill_created',
            'label' => __('When should we send customer notifications?'),
            'type' => 'select_from_array',
            'options' => $this->billNotificationOptions(),
            'allows_null' => true,
        ]);

        $this->crud->field([
            'name' => 'bill_cut_off_notification_days_before_cut_off_date',
            'label' => __('When should we send cut-off notifications?'),
            'type' => 'select_from_array',
            'options' => $this->cutOffNotificationOptions(),
            'allows_null' => true,
        ]);
    }

    public function cutOffNotificationOptions()
    {
        $options = [];
        
        $options[0] = 'Same day as date cut off.';
        for($day = 1; $day <= 3; $day++) {
            $label = $day. ' '. ($day == 1 ? 'day' : 'days') .' before the date cut off.';
        
            $options[$day] = $label;
        }

        return $options;
    }

    public function billNotificationOptions()
    {
        $options = [];
        
        $options[0] = 'Same day as the bill created.';
        for($day = 1; $day <= 10; $day++) {
            $label = $day. ' '. ($day == 1 ? 'day' : 'days') .' after the bill is created.';
        
            $options[$day] = $label;
        }

        return $options;
    }

    public function billGenerateOptions()
    {
        $options = [];
        
        $options[0] = 'Same day as date end.';
        for($day = 1; $day <= 10; $day++) {
            $label = $day. ' '. ($day == 1 ? 'day' : 'days') .' before date end.';
        
            $options[$day] = $label;
        }

        return $options;
    }

    public function cutOffOptions()
    {
        $options = [];
        
        $options[0] = 'Same day as date end.';
        for($day = 1; $day <= 10; $day++) {
            $label = $day. ' '. ($day == 1 ? 'day' : 'days') .' after date end.';
            $options[$day] = $label;
        }

        return $options;
    }

    public function dayOptions()
    {
        $options = [];

        for ($day = 1; $day <= 31; $day++) {
            $options[$day] = $day;
        }

        return $options;
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

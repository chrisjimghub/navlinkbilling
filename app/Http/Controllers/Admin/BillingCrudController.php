<?php

namespace App\Http\Controllers\Admin;

use App\Models\BillingType;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BillingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BillingCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Billing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/billing');
        CRUD::setEntityNameStrings('billing', 'billings');
        
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
        $this->accountColumn(label: __('app.account'));
        
        
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        Widget::add()->type('script')->content('assets/js/admin/forms/billing.js');

        $rules = [
            'account_id' => 'required|integer|min:1',
            'billing_type_id' => 'required|exists:billing_types,id',
            'date_start' => function ($attribute, $value, $fail) {
                // Check if billing_type_id is 2 (Monthly Fee)
                if (request()->input('billing_type_id') == 2) {
                    // Required validation
                    if (empty($value)) {
                        $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_start'))]));
                    }
                }
            },
            'date_end' => function ($attribute, $value, $fail) {
                if (request()->input('billing_type_id') == 2) {
                    // Required validation
                    if (empty($value)) {
                        $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_end'))]));
                    }
                    // Additional validation: date_end must be greater than date_start
                    $date_start = request()->input('date_start');
                    if (!empty($date_start) && $value <= $date_start) {
                        $fail(__('validation.after', ['attribute' => strtolower(__('app.billing_date_end')), 'date' => strtolower(__('app.billing_date_start'))]));
                    }
                }
            },
            'date_cut_off' => function ($attribute, $value, $fail) {
                if (request()->input('billing_type_id') == 2) {
                    // Required validation
                    if (empty($value)) {
                        $fail(__('validation.required', ['attribute' => strtolower(__('app.billing_date_cut_off'))]));
                    }
                    // Additional validation: date_cut_off must be greater than date_end
                    $date_end = request()->input('date_end');
                    if (!empty($date_end) && $value <= $date_end) {
                        $fail(__('validation.after', ['attribute' => strtolower(__('app.billing_date_cut_off')), 'date' => strtolower(__('app.billing_date_end'))]));
                    }
                }
            },
        ];

        $messages = [
            'account_id.required' => __('app.account_field_validation'),
            'billing_type_id.required' => __('validation.required', ['attribute' => strtolower(__('app.billing_type'))]),
            'billing_type_id.exists' => __('validation.exists', ['attribute' => strtolower(__('app.billing_type'))]),
        ];

        $this->crud->setValidation($rules, $messages);

        $this->crud->field([
            'name' => 'account_id',
            'allows_null' => true,
            'attribute' => 'details', // accessor
        ]);

        $this->crud->field([
            'name'        => 'billing_type_id', // the name of the db column
            'label'       => __('app.billing_type'), // the input label
            'type'        => 'radio',
            'options'     =>  BillingType::all()->pluck('name', 'id')->toArray(),
            // optional
            'inline'      => false, // show the radios all on the same line?
        ]);

        $this->crud->field([
            'name'  => 'date_start',
            'label' => __('app.billing_date_start'),
            'type'  => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-3 mb-3 d-none' // d-none = hidden
            ]
        ]);

        $this->crud->field([
            'name'  => 'date_end',
            'label' => __('app.billing_date_end'),
            'type'  => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-3 mb-3 d-none' // d-none = hidden
            ]
        ]);

        $this->crud->field([
            'name'  => 'date_cut_off',
            'label' => __('app.billing_date_cut_off'),
            'type'  => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-3 mb-3 d-none' // d-none = hidden
            ]
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

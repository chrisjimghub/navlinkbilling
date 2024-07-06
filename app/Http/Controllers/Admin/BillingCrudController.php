<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Operations\BillingGroupButtonsOperation;
use App\Models\Billing;
use App\Models\BillingType;
use App\Models\ContractPeriod;
use App\Http\Requests\BillingRequest;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { edit as traitEdit;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    use BillingGroupButtonsOperation;

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

        $this->overrideButtonDeleteUpdate();
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
        // $this->relationshipColumn(column: 'billing_type_id', label: __('app.billing_type')); // NOTE:: uncomment this if you want to show column for billing type
        
        $this->crud->modifyColumn('account_id', [
            'function' => function($entry)  {
                if ($entry->accountDetails) {
                    return $entry->accountDetails;
                }

                return;
            },
            'escaped' => false,
            'wrapper' => false
        ]);


        $this->crud->column([
            'name' => 'billing_period',
            'label' => __('app.billing_period'),
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->billingPeriodDetails;
            },
            'escaped' => false
        ]);

        $this->crud->column([
            'name' => 'particulars',
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->particularDetails;
            },
            'escaped' => false
        ]);

        $this->currencyFormatColumn(fieldName: 'total', label: __('app.billing_total'));


        $this->crud->column([
            'name' => 'billing_status_id',
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->billingStatus->badge;
            },
            'escaped' => false
        ]);

        $this->crud->column('created_at');
    }

    protected function autoSetupShowOperation()
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
        Widget::add()->type('script')->content('assets/js/admin/forms/billing.js');

        $this->crud->setValidation(BillingRequest::class);

        $this->accountField(label: __('app.account'));

        $this->crud->field([
            'name'        => 'billing_type_id', // the name of the db column
            'label'       => __('app.billing_type'), // the input label
            'type'        => 'radio',
            'options'     =>  BillingType::all()->pluck('name', 'id')->toArray(),
            // optional
            'inline'      => false, // show the radios all on the same line?
            'hint'        => __('app.billing_type_id_hint')
        ]);

        $this->crud->field([
            'name'  => 'date_start',
            'label' => __('app.billing_date_start'),
            'type'  => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-4 mb-3 d-none' // d-none = hidden
            ],
        ]);

        $this->crud->field([
            'name'  => 'date_end',
            'label' => __('app.billing_date_end'),
            'type'  => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-4 mb-3 d-none' // d-none = hidden
            ],
        ]);

        $this->crud->field([
            'name'  => 'date_cut_off',
            'label' => __('app.billing_date_cut_off'),
            'type'  => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-4 mb-3 d-none' // d-none = hidden
            ],
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

        $this->crud->field([   // repeatable
            'name'  => 'particulars',
            'label' => __('app.billing_particulars'),
            'type'  => 'repeat',
            'fields' => [ // also works as: "fields"
                [
                    'name'    => 'description',
                    'type'    => 'text',
                    'label'   => __('app.billing_description'),
                    'wrapper' => ['class' => 'form-group col-sm-6'],
                ],
                [
                    'name'    => 'amount',
                    'type'    => 'number',
                    'label'   => 'Amount',
                    'wrapper' => ['class' => 'form-group col-sm-6'],
                    'attributes' => ["step" => "any"],
                ],
                
            ],
            'hint' => '<span class="text-success">Use "'.ContractPeriod::find(1)->name.'" description if you want to automatically add account credit.</span>',
            'init_rows' => 0, // number of empty rows to be initialized, by default 1
            // 'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
        ]);
    }

    private function overrideBUttonDeleteUpdate()
    {
        // override buttons and hide if status is paid
        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButton('line', 'delete', 'view', 'crud::buttons.delete_bill', 'end');
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButton('line', 'update', 'view', 'crud::buttons.update_bill', 'end');
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->denyAccessIfPaid($id);

        $response = $this->traitEdit($id);

        return $response;
    }

    public function destroy($id)
    {
        $this->denyAccessIfPaid($id);

        $response = $this->traitDestroy($id);

        return $response;
    }

    private function denyAccessIfPaid($id)
    {
        $bill = Billing::findOrFail($id);
        // if already paid, then dont allow

        if ($bill->isPaid()) { 
            $this->crud->denyAccess('update');

            // add this in case they type it in address bar, show alert
            \Alert::warning('Whooops, you\'re not allowed to do that.');
        }
    }
    
}

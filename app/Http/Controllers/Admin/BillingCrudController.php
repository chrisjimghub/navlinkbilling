<?php

namespace App\Http\Controllers\Admin;

use App\Models\Billing;
use App\Models\BillingType;
use App\Models\ContractPeriod;
use App\Http\Requests\BillingRequest;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\FilterQueries\BillingFilterQueries;
use App\Http\Controllers\Admin\Operations\GenerateByGroupOperation;
use App\Http\Controllers\Admin\Operations\BillingGroupButtonsOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

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
    use FetchOptions;
    use FilterOperation;
    use ExportOperation;
    use BillingFilterQueries;
    use GenerateByGroupOperation;
    use BillingGroupButtonsOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Billing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/billing');
        CRUD::setEntityNameStrings('Billing', 'Billings');
        
        $this->userPermissions();

        $this->crud->query->billingCrud();
    }

    /**
     * Define filters here
     * 
     * @return void
     */
    public function setupFilterOperation()
    {
        // $this->crud->field([
        //     'name' => 'period',
        //     'label' => __('Billing Period'),
        //     'type' => 'date_range',
        //     'wrapper' => [
        //         'class' => 'form-group col-md-3'
        //     ]
        // ]);
        
        $this->crud->field([
            'name' => 'my',
            'label' => __('Month Year'),
            'type' => 'month',
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->field([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select_from_array',
            'options' => $this->billingStatusLists([4,5]),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->field([
            'name' => 'type',
            'label' => __('Type'),
            'type' => 'select_from_array',
            'options' => $this->billingTypeLists(3),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->field([
            'name' => 'paymentMethod',
            'label' => __('app.payment_method'),
            'type' => 'select_from_array',
            'options' => $this->paymentMethodLists(),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->filterQueries(function ($query) {
            $this->billingFilterQueries($query);
        });

        if (! $this->crud->getRequest()->has('order')){
            $this->crud->orderBy('billing_status_id', 'desc'); //default order unpaid
        }

        $this->accountColumnDetails(label: __('app.account'));
        
        // $this->relationshipColumn(column: 'billing_type_id', label: __('app.billing_type')); // NOTE:: uncomment this if you want to show column for billing type

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

                $this->denyAccessIf($entry->id);

                return $entry->billingStatus->badge;
            },
            'escaped' => false
        ]);

        $this->crud->column([
            'name' => 'paymentMethod',
            'label' => __('app.billing_payment_method'),
            'type' => 'closure',
            'function' => function ($entry) {
                $return = '';
                if ($entry->paymentMethod) {
                    $return = $entry->paymentMethod->name;
                }
                
                if ($entry->isPaid() && $entry->payment_method_id == 4) {
                    if ($entry->payment_details) {
                        $return .= '<br>';
                        foreach ($entry->payment_details as $field => $value) {
                            $return .= '<strong>'.strHumanreadable($field).'</strong>: '.$value ;
                            $return .= '<br>';
                        }
                    }
                }

                return $return;
            
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
        $this->crud->setValidation(BillingRequest::class);

        $this->accountFieldBilling(label: __('app.account'));

        $this->crud->field([
            'name'        => 'billing_type_id', // the name of the db column
            'label'       => __('app.billing_type'), // the input label
            'type'        => 'radio',
            'options'     =>  BillingType::whereIn('id', [1,2])->pluck('name', 'id')->toArray(),
            // optional
            'inline'      => false, // show the radios all on the same line?
            'hint'        => __('app.billing_type_id_hint'),
        ]);

        $this->fieldDatePeriods();
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

        $this->fieldDatePeriods();

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
            'hint' =>   '<span class="text-success">Adding an Advance Payment: 
                            <br><strong>Advance Payment ('.now()->addMonth()->format('F').')</strong>
                            <br><strong>Advance Payment for '.now()->addMonth()->format('F').'</strong>
                        </span>
                      ',
            'init_rows' => 0, // number of empty rows to be initialized, by default 1
            // 'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
        ]);
    }

    private function fieldDatePeriods()
    {
        Widget::add()->type('script')->content('assets/js/admin/forms/billing.js');

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->denyAccessIf($id);

        $response = $this->traitEdit($id);

        return $response;
    }

    public function destroy($id)
    {
        $this->denyAccessIf($id);

        $response = $this->traitDestroy($id);

        return $response;
    }

    private function denyAccessIf($id)
    {
        $bill = Billing::findOrFail($id);

        if ($bill->isPaid()) { 
            $this->crud->denyAccess(['update', 'delete']);
        }
    }
}

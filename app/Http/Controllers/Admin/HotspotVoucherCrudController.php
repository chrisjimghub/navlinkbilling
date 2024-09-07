<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Rules\BankCheckRepeatField;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\Widgets;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\FilterQueries\DateColumnFilterQueries;
use App\Http\Controllers\Admin\FilterQueries\StatusColumnFilterQueries;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

/**
 * Class HotspotVoucherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HotspotVoucherCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { edit as traitEdit;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    use FilterOperation;
    use Widgets;
    use DateColumnFilterQueries;
    use StatusColumnFilterQueries;
    use FetchOptions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\HotspotVoucher::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/hotspot-voucher');
        CRUD::setEntityNameStrings('hotspot voucher', 'hotspot vouchers');
        
        $this->userPermissions();
    }

    public function setupFilterOperation()
    {
        $this->dateColumnFilterField();
        $this->statusColumnFilterField();

        $this->crud->field([
            'name' => 'paymentMethod',
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
            $this->dateColumnFilterQueries($query);
            $this->statusColumnFilterQueries($query);

            $method = request()->input('paymentMethod');

            if ($method) {
                $query->where('payment_method_id', $method);
            }

        });

        $this->notice();

        $this->widgetHotspotVoucher();

        $this->accountColumn();
        $this->crud->modifyColumn('account_id', [
            'function' => function($entry)  {

                $this->denyAccessIf($entry->id);

                if ($entry->account) {
                    return $entry->account->details_all;
                }
                
                return;
            },
            'escaped' => false,
            'wrapper' => false
        ]);

        $this->crud->column('date')->type('date');
        $this->crud->column('receiver')->label(__('app.receiver'));
        $this->crud->column('category');
        $this->currencyFormatColumn('amount');
        $this->crud->column('status');
        $this->crud->column('paymentMethod');

        $this->crud->modifyColumn('paymentMethod', [
            'label' => __('app.payment_method'),
            'type' => 'closure',
            'function' => function ($entry) {
                $return = '';
                if ($entry->paymentMethod) {
                    $return = $entry->paymentMethod->name;
                }
                
                if ($entry->isPaid() && $entry->payment_method_id == 4) {
                    $return .= '<br>';
                    
                    if ($entry->bank_details) {
                        foreach ($entry->bank_details[0] as $field => $value) {
    
                            $return .= '<strong>'.strHumanreadable($field).'</strong>: '.$value ;
                            $return .= '<br>';
                        }
                    }
                }

                return $return;
            },
            'escaped' => false
        ]);
        
        $this->crud->modifyColumn('status', [
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->status->badge;
            },
            'escaped' => false
        ]);
    }

    public function notice()
    {
        if (!auth()->user()->can('expenses_notice')) {
            return;
        }

        if ($this->crud->getOperation() == 'list') {
            $contents[] = [
                'type'         => 'alert',
                'class'        => 'alert alert-default mb-3 text-dark',
                'heading'      => __('app.notice_alert'),
                'content'      => __('app.notice_alert_content'),
            ];

            Widget::add()->to('before_content')->type('div')->content($contents);
        }
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
        Widget::add()->type('view')->view('crud::forms.hotspot_voucher');

        $this->crud->setValidation([
            'date' => 'required|date',
            'amount' => 'required|numeric|gt:0',
            'status' => 'required|numeric|in:1,2',
            'paymentMethod' => ['sometimes', 'required_if:status,1'],
            'bank_details' => [
                'sometimes', 
                'required_if:paymentMethod,4,', 
                new BankCheckRepeatField(),
            ],
        ], [
            'paymentMethod.required_if' => __('app.vouchers.validation.payment_method'),
        ]);
        
        CRUD::setFromDb(); 

        $this->crud->removeFields([
            'category_id',
            'user_id',
            'payment_method_id',
            'amount',
            'status_id',
        ]);

        $this->accountField();
        $this->crud->modifyField('account_id', [
            'options'   => (function ($query) {
                return $query
                    ->allowedBill()
                    ->withSubscription(4) //voucher
                    ->get(); 
            }), 
        ]);

        $this->crud->field('category')->after('date');
        $this->crud->field([
            'name' => 'receiver',
            'label' => __('app.receiver'),
            'options'   => (function ($query) {
                return $query->adminUsersOnly()->orderBy('name', 'ASC')->get();
            }),
        ])->after('date');

        $this->crud->field([   
            'name' => 'amount',
            'type' => 'number',
            'attributes' => ["step" => "any"], 
        ]);

        $this->crud->field('status');
        $this->crud->field('paymentMethod');

        $this->crud->modifyField('status', [
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->modifyField('paymentMethod', [
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->field([   // repeatable
            'name'  => 'bank_details',
            'type'  => 'repeat',
            'fields' => [ // also works as: "fields"
                [
                    'name'    => 'check_issued_date',
                    'type'    => 'date',
                    'wrapper' => ['class' => 'form-group col-sm-4'],
                ],
                [
                    'name'    => 'check_number',
                    'type'    => 'number',
                    'wrapper' => ['class' => 'form-group col-sm-8'],
                ],
                
            ],
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 1,
        ])->after('paymentMethod');

        // dd(request()->all());
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
        $model = modelInstance('HotspotVoucher')->findOrFail($id);

        $this->userPermissions();

        if ($model->created_at->lt(now()->subDay())) {
            if (!auth()->user()->can('hotspot_vouchers_edit_old_data')) {
                $this->crud->denyAccess(['update', 'delete']);
            }
        }elseif ($model->isPaid()) {
            $this->crud->denyAccess(['update', 'delete']);
        }
    }
}

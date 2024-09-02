<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\FilterQueries\DateColumnFilterQueries;
use Illuminate\Support\Carbon;
use App\Models\HotspotVoucher;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\Widgets;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
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
        $this->crud->column('paymentMethod')->after('date');
        $this->crud->column('category')->after('date');
        $this->crud->column('receiver')->label(__('app.receiver'))->after('date');
        $this->currencyFormatColumn('amount');
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
        CRUD::setValidation([
            'date' => 'required|date',
            'amount' => 'required|numeric|gt:0',
        ]);
        CRUD::setFromDb(); 

        $this->crud->removeFields([
            'category_id',
            'user_id',
            'payment_method_id',
            'amount'
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

        $this->crud->field('paymentMethod')->after('date');
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
        }
    }
}

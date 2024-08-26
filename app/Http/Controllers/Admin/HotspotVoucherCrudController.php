<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Carbon;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Models\HotspotVoucher;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->notice();

        $this->widgets();

        CRUD::setFromDb(); 

        $this->crud->removeColumns([
            'category_id',
            'payment_method_id',
            'user_id',
        ]);

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

        $this->crud->column('paymentMethod')->after('date');
        $this->crud->column('category')->after('date');
        $this->crud->column('receiver')->label(__('app.receiver'))->after('date');
        $this->currencyFormatColumn('amount');
    }

    public function widgets()
    {
        if ($this->crud->getOperation() == 'list') {

            $date = Carbon::now();
            $month = $date->month;
            $year = $date->year;

            $total = HotspotVoucher::whereDate('date', $date)->get()->sum('amount');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.todays_voucher_income'),
                'progress'      => widgetProgress(now()->hour, 24), 
                'progressClass' => 'progress-bar bg-info',
                'hint'          => now()->format(dateHumanReadable()),
            ];

            $total = HotspotVoucher::whereMonth('date', $month)->get()->sum('amount');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.months_voucher_income'),
                'progress'      => widgetProgress(now()->day, now()->daysInMonth()), 
                'progressClass' => 'progress-bar bg-warning',
                'hint'          => now()->format('M, Y'),
            ];

            $total = HotspotVoucher::whereYear('date', $year)->get()->sum('amount');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.years_voucher_income'),
                'progress'      => widgetProgress(now()->month, 12), 
                'progressClass' => 'progress-bar bg-dark',
                'hint'          => 'Jan - Dec '.date('Y'),
            ];

            Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
        }
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

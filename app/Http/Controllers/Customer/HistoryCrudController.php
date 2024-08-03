<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Carbon;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\Operations\BillingGroupButtonsOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

/**
 * Class HistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HistoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use BillingGroupButtonsOperation;
    use CrudExtend;
    use ExportOperation;
    use FilterOperation;
    use FetchOptions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        config(['backpack.base.route_prefix' => 'customer']); // TODO:: transfer to middleware

        CRUD::setModel(\App\Models\Billing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/history');
        CRUD::setEntityNameStrings('history', 'histories');

        $this->crud->denyAllAccess();
        $this->crud->allowAccess([
            'list',
            'show',
            'filters',
            'export',
            'downloadInvoice',
        ]);
    }

    protected function setupBillingGroupButtonsDefaults()
    {
        CRUD::allowAccess([
            'downloadInvoice',
        ]);

        // load
        $this->myWidgets();

        CRUD::operation('billingGroupButtons', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
            Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');
        });

        CRUD::operation(['list'], function () {
            CRUD::addButton('line', 'billingGroupButtons', 'view', 'crud::buttons.customer.download_invoice', 'beginning');
        });
    }

    public function setupFilterOperation()
    {
        // TODO:: replace with year & month filter
        $this->crud->field([
            'name' => 'period',
            'label' => __('Billing Period'),
            'type' => 'date_range',
        ]);

        $this->crud->field([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select',
            'options' => $this->billingStatusLists(),
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
            $status = request()->input('status');
            $type = request()->input('type');
            $period = request()->input('period');

            if ($status) {
                $query->{$status == 1 ? 'paid' : 'unpaid'}();
            
            }

            if ($type) {
                $query->{$type == 1 ? 'installment' : 'monthly'}();
            }

            if ($period) {
                $dates = explode('-', $period);
                $dateStart = Carbon::parse($dates[0]);
                $dateEnd = Carbon::parse($dates[1]);
                $query->withinBillingPeriod($dateStart, $dateEnd);
            }
        });

        if (! $this->crud->getRequest()->has('order')){
            $this->crud->orderBy('billing_status_id', 'desc'); //default order unpaid
        }

        $this->accountColumn(label: __('app.account'));
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
                return $entry->billingStatus->badge;
            },
            'escaped' => false
        ]);

        $this->crud->column([
            'name' => 'paymentMethod',
            'label' => __('app.billing_payment_method')
        ]);

        $this->crud->column('created_at');
    }
    
    public function setupShowOperation()
    {
        $this->setupListOperation();
    }
}

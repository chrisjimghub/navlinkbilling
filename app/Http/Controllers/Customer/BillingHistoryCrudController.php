<?php

namespace App\Http\Controllers\Customer;

use App\Exports\BillingExport;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Http\Controllers\Customer\Operations\GcashOperation;
use App\Http\Controllers\Customer\Traits\CustomerPermissions;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\Operations\BillingGroupButtonsOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;
use App\Http\Controllers\Admin\BillingCrudController as AdminBillingCrudController;

/**
 * Class HistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BillingHistoryCrudController extends AdminBillingCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use GcashOperation;
    use BillingGroupButtonsOperation;
    use CrudExtend;
    use ExportOperation;
    use FilterOperation;
    use FetchOptions;
    use CustomerPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Billing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/billing-history');
        CRUD::setEntityNameStrings('invoice', 'invoices');

        $this->customerPermissions([
            'list',
            'show',
            'filters',
            'export',
            'downloadInvoice',
            'gcash'
        ]);

        $this->crud->query->whereHas('account', function ($query) {
            $query->billingCrud();
        });

        $this->data['breadcrumbs'] = [
            'Dashboard' => backpack_url('dashboard'),
            $this->crud->entity_name => false,
        ];
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
            CRUD::addButton('line', 'billingGroupButtons', 'view', 'crud::buttons.customer.download_invoice');
        });
    }

    public function downloadInvoiceType($invoice)
    {
        return $invoice->download();
    }

    public function setupFilterOperation()
    {
        // TODO:: replace with year & month filter
        $this->crud->field([
            'name' => 'period',
            'label' => __('Billing Period'),
            'type' => 'date_range',
            'wrapper' => [
                'class' => 'form-group col-md-3'
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
    }
    
    // we need this, because in the original BillingCrudController it was overrided so we will override the trait setupShowOperation here too
    // if we dont override it here it will cause an error because of the json columns, so by doing this we make sure that the preview
    // will use the setupListOperation columns define
    public function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function exportClass()
    {
        $name = strHumanReadable($this->crud->entity_name);
        
        return (new BillingExport)->download($name.'-'.carbonNow().'.xlsx');
    }
}

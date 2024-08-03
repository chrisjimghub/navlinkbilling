<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Carbon;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Http\Controllers\Admin\BillingCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\Operations\BillingGroupButtonsOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

/**
 * Class HistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HistoryCrudController extends BillingCrudController
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
        config(['backpack.base.route_prefix' => 'customer']); // TODO::

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

        $this->data['breadcrumbs'] = [
            'Dashboard' => backpack_url('dashboard'),
            $this->crud->entity_name => true,
            'List' => false,
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class WifiHarvestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WifiHarvestCrudController extends CrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/wifi-harvest');
        CRUD::setEntityNameStrings('wifi harvest', 'wifi harvests');
    
        $this->userPermissions('wifi_harvests');

        $this->crud->query->whereHas('account', function ($query) {
            $query->harvestCrud();
        });
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->accountColumnDetails(label: __('app.account'));
        
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

        $this->currencyFormatColumn(fieldName: 'total', label: __('app.wifi_harvest.total'));

        $this->crud->column([
            'name' => 'account.installed_address',
            'label' => __('app.account_installed_address'),
            'limit' => 255,
        ]);

        $this->crud->column([
            'name' => 'billing_status_id',
            'label' => __('app.wifi_harvest.status'),
            'type' => 'closure',
            'function' => function ($entry) {
                if ($entry->billing_status_id != '4') {
                    return;
                }

                return $entry->billingStatus->badge;
            },
            'escaped' => false
        ]);

        $this->crud->column('created_at');
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
            // 'name' => 'required|min:2',
        ]);
        // CRUD::setFromDb(); // set fields from db columns.

        // TODO::
        // particulars:
        //     Revenue
        //     Monthly Fee
        //     Electric Bill
        //     Lessor 20%
        //     Others:
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

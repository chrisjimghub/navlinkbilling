<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Admin\WifiHarvestCrudController;
use App\Http\Controllers\Customer\Traits\CustomerPermissions;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PisoWifiCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PisoWifiCrudController extends WifiHarvestCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CustomerPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Billing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/piso-wifi');
        CRUD::setEntityNameStrings('piso wifi', 'piso wifis');

        $this->customerPermissions([
            'list',
            'show',
        ]);

        $this->crud->query->harvestCrud();

        // $this->data['breadcrumbs'] = [
        //     'Dashboard' => backpack_url('dashboard'),
        //     $this->crud->entity_name => false,
        // ];
    }

    public function setupShowOperation()
    {
        $this->setupListOperation();
    }

    // override this function to not display widgets
    public function widgets()
    {
        return;
    }
}

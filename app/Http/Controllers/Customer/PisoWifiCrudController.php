<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Admin\Traits\CurrencyFormat;
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
    use CurrencyFormat;

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

        $this->crud->query->harvestCrud()->harvested();
    }
    
    public function setupListOperation()
    {
        parent::setupListOperation();

        $this->crud->removeColumns([
            'billing_status_id',
            'total',
            'particulars'
        ]);

        // $this->crud->modifyColumn('particulars', [
        //     'function' => function($entry) {
        //         return $entry->particular_details;
        //     },
        // ]);

        $this->crud->column([
            'name' => 'earnings',
            'type' => 'closure',            
            'function' => function($entry) {
                $total = 0;

                foreach ($entry->particulars as $particular) {
                    if (str_contains(strtolower($particular['description']), 'lessor') || 
                        str_contains(strtolower($particular['description']), 'electric bill')
                    ) {
                        $total += $particular['amount'];
                    }
                }

                return $this->currencyFormatAccessor(abs($total));
            },
            'wrapper' => [
                'class' => function ($crud, $col) {
                    $val = $col['value'];
                    $css = '';                    
                    if ($val > 0) {
                        $css = 'text-success';
                    }elseif ($val < 0) {
                        $css = 'text-danger';
                    }
                    return $css;
                }
            ],
        ])->afterColumn('date_start');
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

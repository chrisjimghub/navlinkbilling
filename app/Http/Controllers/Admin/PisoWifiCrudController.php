<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PisoWifiCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PisoWifiCrudController extends CrudController
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
        CRUD::setModel(\App\Models\PisoWifi::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/piso-wifi');
        CRUD::setEntityNameStrings('piso wifi', 'piso wifis');

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
        CRUD::setFromDb(); 

        $this->accountColumn();

        $this->crud->modifyColumn('schedule', [
            'type'     => 'closure',
            'function' => function($entry) {
                return 'Every <span class="text-success">'.ordinal($entry->schedule) .'</span> day of the month.';
            },
            'escaped' => false,            
        ]);

        $this->crud->column([
            'name' => 'users',
            'label' => __('app.piso_wifi.harvestor'),
        ]);
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
        $id = null;
        if ($this->crud->getOperation() == 'update') {
            $id = $this->crud->getCurrentEntryId();
        }

        $this->crud->setValidation([
            'schedule' => 'required|integer|min:1|max:31', 
            'users' => 'required|array|min:1', 
            'account_id' => 'required|integer|exists:accounts,id|unique:piso_wifis,account_id,'.$id,
        ], [
            'users.required' => __('app.piso_wifi.harvestor_required')
        ]);
        
        $this->accountField(label: __('app.account'));

        $this->crud->field([
            'name' => 'schedule',
            'type' => 'number',
            'hint' => __('app.piso_wifi.schedule_hint'),
            'attributes' => [
                "step" => "any",
                'min' => '1',
                'max' => '31',
            ],
        ]);

        $this->crud->field([ 
            'label' => __('app.piso_wifi.harvestor'),
            'type'  => 'select_multiple',
            'name'  => 'users',
             
            'options'   => (function ($query) {
                return $query
                    ->adminUsersOnly()
                    ->orderBy('name', 'ASC')
                    ->get();
            }),
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
}

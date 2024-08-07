<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OneTimeChargeExport;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OtcCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OtcCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    use CrudExtend;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Otc::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/otc');
        CRUD::setEntityNameStrings('one-time charge', 'one-time charges');

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
        // CRUD::setFromDb(); // set columns from db columns.
        // $this->crud->modifyColumn('name', ['limit' => 100]);
        // $this->currencyColumn('amount');

        // TODO:: fix search logic and order logic
        $this->crud->column([
            'name' => 'amount_name',
            'label' => __('app.otc'),
            'limit' => 255,
        ]);
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
            'name' => $this->validateUniqueRule(),
            'amount' => 'required|numeric|min:0',
        ]);
        CRUD::setFromDb(); // set fields from db columns.

        $this->currencyFormatField('amount');

        $this->crud->modifyField('amount', [
            'default' => 0,
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

    public function exportClass()
    {
        $name = strHumanReadable($this->crud->entity_name);
        
        return (new OneTimeChargeExport)->download($name.'-'.carbonNow().'.xlsx');
    }

    protected function exportRoute()
    {
        return route('otc.export');
    }
}

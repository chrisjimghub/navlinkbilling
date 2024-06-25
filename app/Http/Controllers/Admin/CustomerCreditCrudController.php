<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use App\Http\Controllers\Admin\Traits\UserPermissions;
use App\Http\Requests\CustomerCreditRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCreditCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCreditCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use UserPermissions;
    use CurrencyFormat;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CustomerCredit::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer-credit');
        CRUD::setEntityNameStrings('customer credit', 'customer credits');
    
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
        $this->crud->column([
            'name' => 'customer.full_name',
            'label' => __('navlink.customer'),
        ]);

        $this->crud->column('amount');
        $this->currencyFormatColumn('amount');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $rules = [
            'customer_id' => 'required|integer|min:1',
            'amount' => 'required|numeric',
        ];
        $messages = [
            'customer_id.required' => __('navlink.customer_select_field'),
        ];
        $this->crud->setValidation($rules, $messages);

        CRUD::setFromDb();

        $this->crud->field([
            'name' => 'customer_id',
            'label' => __('navlink.customer'),
            'attribute' => 'full_name', // accessor
            'allows_null' => true,
        ]);

        $this->crud->field('amount');
        $this->currencyFormatField('amount');
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

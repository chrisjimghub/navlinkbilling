<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use App\Http\Controllers\Admin\Traits\UserPermissions;
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
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use UserPermissions;
    use CurrencyFormat;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(CustomerCredit::class);
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
        // Set the model for the CRUD operation
        $this->crud->setModel(Customer::class);

        // Apply the scope to filter customers with remaining credits > 0
        $this->crud->addClause('hasRemainingCredits');
        $this->crud->orderBy('last_name');
        $this->crud->orderBy('first_name');

        $this->crud->column([
            'name' => 'full_name',
            'label' => __('navlink.customer'),
            'type' => 'text',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('last_name', 'like', '%'.$searchTerm.'%');
            },
        ]);


        $this->crud->column([
            'name' => 'remaining_credits',
            'label' => __('navlink.remaining_credits'),
        ]);
        $this->currencyFormatColumn('remaining_credits');

        $this->crud->column([
            'name' => 'credits_latest_updated',
            'label' => __('navlink.latest_updated'),
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

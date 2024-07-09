<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AccountCreditCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccountCreditCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\AccountCredit::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/account-credit');
        CRUD::setEntityNameStrings('account credit', 'account credits');

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
        $this->crud->setModel(Account::class);

        // Apply the scope to filter only the rows that has remaining credits > 0
        $this->crud->addClause('hasRemainingCredits');

        $this->customerNameColumn(label: __('app.account_name'));
        $this->crud->modifyColumn('customer_id', [
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->details;
            },
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('account/'.$entry->id.'/show');
                },
                // 'target' => '_blank'
            ]
        ]);

        $this->currencyColumn(column: 'remaining_credits', label: __('app.remaining_credits'));

        $this->crud->column([
            'name' => 'credits_latest_updated',
            'label' => __('app.account_credit_latest_udpated'),
        ]);
    }

    protected function autoSetupShowOperation()
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
            'account_id' => 'required|integer|min:1',
            'amount' => 'required|numeric'
        ]);
        CRUD::setFromDb(); // set fields from db columns.

        $this->accountField();
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

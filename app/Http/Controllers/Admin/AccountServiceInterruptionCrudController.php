<?php

namespace App\Http\Controllers\Admin;

use App\Rules\UniqueServiceInterruption;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AccountServiceInterruptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccountServiceInterruptionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\AccountServiceInterruption::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/account-service-interruption');
        CRUD::setEntityNameStrings('service interruption', 'service interruptions');

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
        CRUD::setFromDb(); // set columns from db columns.

        $this->accountColumn();
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
        $rules = $this->getBaseValidationRules();

    // Add specific rules for create operation
        $rules['account_id'][] = new UniqueServiceInterruption(
            request()->input('account_id'),
            request()->input('date_start'),
            request()->input('date_end')
        );

        $this->updateCreate($rules);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $rules = $this->getBaseValidationRules();

        // Add specific rules for update operation
        $rules['account_id'][] = new UniqueServiceInterruption(
            request()->input('account_id'),
            request()->input('date_start'),
            request()->input('date_end'),
            $this->crud->getCurrentEntry()->getKey()
        );

        $this->updateCreate($rules);
    }
    
    public function updateCreate($rules)
    {
        // Define custom error messages
        $messages = [
            'account_id.required' => __('app.account_field_validation'),
            'date_end.after_or_equal' => 'The end date must be after the start date.',
        ];

        // Set validation rules and messages
        $this->crud->setValidation($rules, $messages);

        // Set fields from database columns
        CRUD::setFromDb();

        // Add custom fields or configurations
        $this->accountField(label: __('app.account'));
    }

    protected function getBaseValidationRules()
    {
        return [
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
            'account_id' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}

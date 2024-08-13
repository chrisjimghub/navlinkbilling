<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Events\BillProcessed;
use App\Imports\AccountImport;
use App\Http\Requests\AccountRequest;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\FilterQueries\AccountFilterQueries;
use RedSquirrelStudio\LaravelBackpackImportOperation\ImportOperation;
use App\Http\Controllers\Admin\Operations\AccountUploadTemplateExportOperation;
use RedSquirrelStudio\LaravelBackpackImportOperation\Requests\ImportFileRequest;
use Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

/**
 * Class AccountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccountCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    use FetchOptions;
    use FilterOperation;
    use ExportOperation;
    use ImportOperation;
    use AccountFilterQueries;
    use AccountUploadTemplateExportOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Account::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/account');
        CRUD::setEntityNameStrings('account', 'accounts');

        $this->userPermissions();
    }

    public function setupFilterOperation()
    {
        $this->crud->field([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select_from_array',
            'options' => $this->accountStatusLists(),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->field([
            'name' => 'subscription',
            'label' => __('Subscription'),
            'type' => 'select_from_array',
            'options' => $this->subscriptionLists(),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->field([
            'name' => 'grouping',
            'label' => __('Billing Grouping'),
            'type' => 'select_from_array',
            'options' => $this->billingGroupingLists(),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->filterQueries(function ($query) {
            $this->accountFilterQueries($query);
        });

        // eager loading improves performance
        $this->crud->with('customer');
        $this->crud->with('plannedApplication');
        $this->crud->with('subscription');
        $this->crud->with('accountStatus');
        $this->crud->with('otcs');
        $this->crud->with('contractPeriods');

        $this->customerNameColumn(label: __('app.account_name'));

        $this->plannedApplicationColumn(__('app.planned_application'));

        $this->relationshipColumn(column: 'subscription', label: __('app.subscription'));
        
        $this->relationshipColumn(column: 'account_status', label: __('app.account_status'));
        $this->crud->modifyColumn('account_status', [
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    return $entry->accountStatus->badge_css;
                },
            ],
        ]);

        $this->crud->column([
            'type' => 'google_map_coordinates',
            'name' => 'google_map_coordinates',
            'label' => __('app.account_google_map_coordinates'),
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    if ($entry->google_map_coordinates) {
                        return $this->googleMapLink($entry->google_map_coordinates);
                    }

                    return '';
                },
                'target' => '_blank',
            ],
        ]);

        $this->crud->column('installed_date');
        $this->crud->column('installed_address');       

        $this->crud->column([
            'name' => 'otcs',
            'label' => __('app.otc'),
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->otcDetails;
            },
            'escaped' => false

        ]);

        $this->crud->column([
            'name' => 'contractPeriods',
            'label' => __('app.contract_period'),
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->contractPeriodDetails;
            },
            'escaped' => false
        ]);

        $this->crud->column('notes');

        $this->crud->column('billingGrouping')->label(__('app.billing_grouping'));
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
        Widget::add()->type('script')->content(asset('assets/js/admin/forms/planned_application.js'));

        $this->crud->setValidation(AccountRequest::class);
        
        foreach ([
            'customer_id' => __('app.account_name'),
            'planned_application_id' => __('app.planned_application'),
            'subscription' => __('app.subscription'),
        ] as $name => $label) {
            $this->crud->field([
                'name' => $name,
                'label' => $label,
            ]);
        }

        $this->crud->modifyField('customer_id', [
            'attribute' => 'full_name', // accessor
            'allows_null' => true,
        ]);


        $this->crud->modifyField('planned_application_id', [
            'type'      => 'select_grouped_planned_application', //https://github.com/Laravel-Backpack/CRUD/issues/502
            'entity'    => 'plannedApplication',

            'attribute' => 'optionLabel', // accessor

            'model' => 'App\Models\PlannedApplication',  // Parent model
            
            'group_by'  => 'location', // the relationship to entity you want to use for grouping
            'group_by_attribute' => 'name', // the attribute on related model, that you want shown
            'group_by_relationship_back' => 'plannedApplications', // relationship from related model back to this model

            'relation_type' => 'BelongsTo',

            // custom option attribute, i created a custom field that append a custom model attribute
            'data-location' => 'dataLocation',
        ]); 


        $this->crud->field([
            'name' => 'otcs',
            'label' => __('app.otc'),
            'type' => 'checklist',
            'number_of_columns' => 1,
            'attribute' => 'amountName',
        ]);

        $this->crud->field([
            'name' => 'contractPeriods',
            'label' => __('app.contract_period'),
            'type' => 'checklist',
            'number_of_columns' => 1,
        ]);

        $this->crud->field([
            'name' => 'installed_date',
            'type' => 'date'
        ]);

        $this->crud->field([
            'name' => 'installed_address',
            'type' => 'text'
        ]);

        $this->crud->field([
            'name' => 'google_map_coordinates',
            'type' => 'text',
        ]);

        $this->crud->field([
            'name' => 'notes',
            'type' => 'textarea',
        ]);

        $this->crud->field([
            'name' => 'billingGrouping',
            'label' => __('app.billing_grouping')
        ]);

        $this->crud->field([
            'name' => 'accountStatus',
            'label' => __('app.account_status')
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

    // NOTE:: override the backpack update operation to dispatch event
    public function update()
    {
        $response = $this->traitUpdate();

        if (request()->has('id')) {
            $account = Account::findOrFail(request()->id);

            event(new BillProcessed($account));
        }

        return $response;
    }

    // override hint
    protected function setupImportFileUpload(): void
    {
        $this->crud->hasAccessOrFail('import');
        CRUD::setValidation(ImportFileRequest::class);

        CRUD::addField([
            'name' => 'file',
            'label' => __('import-operation::import.select_a_file'),
            'type' => 'upload',
            'hint' => __('import-operation::import.accepted_types') . '. ' .
                ($this->example_file_url ? 
                '<a target="_blank" download title="' . __('import-operation::import.download_example') . '" href="' . $this->example_file_url . '">' . __('import-operation::import.download_example') . '</a>
                <br>
                Here are the values or options for columns:
                <a target="_blank" download href="'.route('account.accountOptionColumnExport').'">Account options column file.</a>

                <br>
                <span class="text-danger">
                    For one-time charges and contract periods, the only delimiter accepted is the pipe symbol (|). For example, you can enter multiple values like this: 12 months Lock-in | Advance 1-month monthly payment.
                </span>
                ' : ''),
        ]);
    }

    protected function setupImportOperation()
    {
        $this->setExampleFileUrl(route('account.accountUploadTemplateExport'));
        $this->disableUserMapping();
        $this->withoutPrimaryKey();
        $this->setImportHandler(AccountImport::class);
    }

}

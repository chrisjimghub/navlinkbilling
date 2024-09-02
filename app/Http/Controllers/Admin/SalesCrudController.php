<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\FilterQueries\DateColumnFilterQueries;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;

/**
 * Class SalesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SalesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    use ExportOperation;
    use DateColumnFilterQueries;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Sales::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sales');
        CRUD::setEntityNameStrings('sales', 'sales');

        $this->userPermissions();
    }

    public function setupFilterOperation()
    {
        $this->dateColumnFilterField();
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
            $this->dateColumnFilterQueries($query);
        });

        $this->notice();

        CRUD::setFromDb(); 

        $this->crud->removeColumns([
            'category_id',
            'user_id',
        ]);
        
        $this->crud->column('category')->after('description');
        $this->crud->column('receiver')->label(__('app.receiver'))->after('description');
        $this->currencyFormatColumn('amount');

        $this->crud->modifyColumn('amount', [
            'type'     => 'closure',
            'function' => function($entry) {
                $this->denyAccessIf($entry->id);
                return $entry->amount;
            }
        ]);

        // CRUD::setFromDb(); this automatically create a field too, so we remove it manually. 
        // or you can remove the CRUD::setFromDb and create the col manually
        $this->crud->removeFields([
            'description',
            'category_id',
            'user_id',
            'amount'
        ]);
    }

    public function notice()
    {
        if (!auth()->user()->can('sales_notice')) {
            return;
        }

        if ($this->crud->getOperation() == 'list') {
            $contents[] = [
                'type'         => 'alert',
                'class'        => 'alert alert-default mb-3 text-dark',
                'heading'      => __('app.notice_alert'),
                'content'      => __('app.notice_alert_content'),
            ];

            Widget::add()->to('before_content')->type('div')->content($contents);
        }
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        $this->crud->modifyColumn('description', [
            'limit' => 999,
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
            'date' => 'required|date',
            'description' => 'required|min:2',
            'amount' => 'required|numeric|gt:0',
        ]);
        CRUD::setFromDb(); // set fields from db columns.
    
        $this->crud->modifyField('description', [
            'type' => 'textarea',
        ]);

        $this->crud->removeFields([
            'category_id',
            'user_id',
            'amount'
        ]);

        $this->crud->field('category')->after('description');
        $this->crud->field([
            'name' => 'receiver',
            'label' => __('app.receiver'),
            'options'   => (function ($query) {
                return $query->adminUsersOnly()->orderBy('name', 'ASC')->get();
            }),
        ])->after('description');

        $this->crud->field([   
            'name' => 'amount',
            'type' => 'number',
            'attributes' => ["step" => "any"], 
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

    public function edit($id)
    {
        $this->denyAccessIf($id);

        $response = $this->traitEdit($id);

        return $response;
    }

    public function destroy($id)
    {
        $this->denyAccessIf($id);

        $response = $this->traitDestroy($id);

        return $response;
    }

    private function denyAccessIf($id)
    {
        $model = modelInstance('Sales')->findOrFail($id);

        $this->userPermissions();

        if ($model->created_at->lt(now()->subDay())) {
            if (!auth()->user()->can('sales_edit_old_data')) {
                $this->crud->denyAccess(['update', 'delete']);
            }
        }
    }
}

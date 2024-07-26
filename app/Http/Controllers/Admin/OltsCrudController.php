<?php

namespace App\Http\Controllers\Admin;

use App\Models\CommunityString;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\Traits\ValidateUniqueRule;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OltsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OltsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use ValidateUniqueRule;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Olt::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/olts');
        CRUD::setEntityNameStrings('OLT Device', 'OLT Device');
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

        $this->crud->removeColumns($this->removeColumnFields());

        $this->crud->column([
            'name' => 'communityRead',
            'label' => __('app.olt_community_read'),
        ]);

        $this->crud->column([
            'name' => 'communityWrite',
            'label' => __('app.olt_community_write'),
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
        CRUD::setValidation([
            'name' => 'required|min:2',
            'model' => 'required|min:2',
            'ip_address' => $this->validateUniqueRule('ip_address'),

            'communityRead' => [
                'required',
                Rule::in(CommunityString::pluck('id')->toArray()),
            ],

            'communityWrite' => [
                'required',
                Rule::in(CommunityString::pluck('id')->toArray()),
            ],

            'base_oid' => 'required|min:5',
        ]);

        CRUD::setFromDb(); // set fields from db columns.

        $this->crud->removeFields($this->removeColumnFields());

        $this->crud->modifyField('base_oid', [
            'hint' => __('app.olt_base_oid_hint')
        ]);

        $this->crud->field([
            'name' => 'communityRead',
            'label' => __('app.olt_community_read'),
            'default' => 1,
        ]);

        $this->crud->field([
            'name' => 'communityWrite',
            'label' => __('app.olt_community_write'),
            'default' => 2,
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

    private function removeColumnFields()
    {
        return [
            'community_read_id',
            'community_write_id'
        ];
    }
}

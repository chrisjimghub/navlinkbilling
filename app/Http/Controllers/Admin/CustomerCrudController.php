<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\Operations\ExportOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use RedSquirrelStudio\LaravelBackpackImportOperation\ImportOperation;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use CrudExtend;
    use ExportOperation;
    // use ImportOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('customer', 'customers');

        // dont delete
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
        $this->crud->orderBy('last_name');
        $this->crud->orderBy('first_name');

        CRUD::setFromDb(); // set columns from db columns.
        
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'orderable' => false,
        ]);

        $this->crud->modifyColumn('signature', [
            'type' => 'image',
            'height' => '150px',
            'width'  => '150px',
        ]);

        $this->crud->removeColumn('facebook_messenger_id');
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
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'date',
            'contact_number' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        CRUD::setFromDb(); // set fields from db columns.

        $this->crud->modifyField('photo', [
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);

        $this->crud->field([
            'name' => 'signature',
            'lable' => ('navlink.customer_signature'),
            'type' => 'signature',
            'view_namespace' => 'signature-field-for-backpack::fields',
        ]);

        $this->crud->modifyField('date_of_birth', ['type' => 'date']);      
        
        $this->crud->removeField('facebook_messenger_id');
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

    protected function setupImportOperation()
    {
        $this->setExampleFileUrl('https://example.com/link-to-your-download/file.csv');

        $this->withoutPrimaryKey();

        CRUD::addColumn([
           'name' => 'last_name',
           'type' => 'text',
        ]);
        
        CRUD::addColumn([
            'name' => 'first_name',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'date_of_birth',
            'type' => 'date',
        ]);

        CRUD::addColumn([
            'name' => 'contact_number',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'block_street',
            'type' => 'text',
        ]);
        
        CRUD::addColumn([
            'name' => 'barangay',
            'type' => 'text',
        ]);
        
        CRUD::addColumn([
            'name' => 'city_or_municipality',
            'type' => 'text',
        ]);
        
        CRUD::addColumn([
            'name' => 'social_media',
            'type' => 'text',
        ]);


    }
}

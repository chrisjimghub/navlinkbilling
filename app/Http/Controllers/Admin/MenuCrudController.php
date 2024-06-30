<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuRequest;
use Backpack\PermissionManager\app\Models\Permission;
use App\Http\Controllers\Admin\Traits\UserPermissions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    use UserPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Menu::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/menu');
        CRUD::setEntityNameStrings('menu', 'menus');

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

        $this->crud->removeColumns($this->removeDbColumn());

        $this->crud->modifyColumn('permissions', [
            'type'     => 'closure',
            'function' => function($entry) {

                if (!$entry->permissions) {
                    return;
                }

                return implode(', ', $entry->permissions);
            },
            'escaped' => false
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
            'label' => 'required|min:2',
            // 'icon' => 'required|min:2',
            // 'permissions' => 'required|array|min:1',

        ]);
        CRUD::setFromDb(); // set fields from db columns.

        $this->crud->removeFields($this->removeDbColumn());

        $this->crud->modifyField('icon', [
            'hint' => __('app.menu_icon_hint')
        ]);

        $this->crud->modifyField('permissions', [
            'type'        => 'select_from_array',
            'allows_null' => true,
            'options'     => config('backpack.permissionmanager.models.permission')
                            ::where('name', 'like', '%_list%')
                            ->orWhere('name', 'like', '%admin_%')
                            ->orWhere('name', 'like', '%menu_separator_%')
                            ->pluck('name', 'name'),
            'allows_multiple' => true, 
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

    private function removeDbColumn()
    {
        return [
            'parent_id',
            'lft',
            'rgt',
            'depth',
        ];
    }

    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements
        CRUD::set('reorder.label', 'label');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        CRUD::set('reorder.max_level', 2);

        // if you don't fully trust the input in your database, you can set 
        // "escaped" to true, so that the label is escaped before being shown
        // you can also enable it globally in config/backpack/operations/reorder.php
        CRUD::set('reorder.escaped', true);
    }
}

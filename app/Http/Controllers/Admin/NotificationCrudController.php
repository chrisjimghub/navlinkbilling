<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Operations\NotificationMarkedAsReadOperation;
use App\Http\Controllers\Admin\Traits\UserPermissions;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class NotificationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NotificationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    use UserPermissions;
    use NotificationMarkedAsReadOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Notification::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/notification');
        CRUD::setEntityNameStrings('notification', 'notifications');

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
        $this->crud->orderBy('created_at', 'desc');
        $this->crud->query->forAuthenticatedUser();
        $this->crud->query->unread();

        $this->crud->column([
            'name' => 'type_human_readable',
            'label' => 'Type'
        ]);

        $this->crud->column([
            'name' => 'created_at',
            'label' => 'Date'
        ]);

        $this->crud->column([
            'name' => 'message',
            'label' => 'Message',
            'limit' => true,
            'escaped' => false,
        ]);
    }

    protected function setupShowOperation()
    {
        return $this->setupListOperation();
    }
}

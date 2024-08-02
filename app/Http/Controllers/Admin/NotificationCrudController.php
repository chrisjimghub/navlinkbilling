<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Operations\NotificationMarkedAsReadOperation;
use App\Http\Controllers\Admin\Traits\CrudExtend;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

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

    use CrudExtend;
    use NotificationMarkedAsReadOperation;
    use FilterOperation;

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

    public function setupFilterOperation()
    {
        $this->crud->field([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select',
            'options' => [
                'read' => 'Read',
                'unread' => 'Unread',
            ],
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
        $this->crud->query->forAuthenticatedUser();
        $this->crud->orderBy('created_at', 'desc');

        $this->filterQueries(function ($query) {
            $status = request()->input('status');

            if ($status) {
                $query->{$status}();
            }
        });

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

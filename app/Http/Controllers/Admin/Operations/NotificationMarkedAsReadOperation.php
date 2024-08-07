<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Notification;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait NotificationMarkedAsReadOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupNotificationMarkedAsReadRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/notification-marked-as-read', [
            'as'        => $routeName.'.notificationMarkedAsRead',
            'uses'      => $controller.'@notificationMarkedAsRead',
            'operation' => 'notificationMarkedAsRead',
        ]);

        Route::get($segment.'/unreadCount', [
            'as'        => $routeName.'.unreadCount',
            'uses'      => $controller.'@unreadCount',
            'operation' => 'unreadCount',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupNotificationMarkedAsReadDefaults()
    {
        CRUD::allowAccess('markedAsRead');

        Widget::add()->type('script')->content('assets/js/admin/swal_helper.js');

        CRUD::operation('notificationMarkedAsRead', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('line', 'notification_marked_as_read', 'view', 'crud::buttons.notification_marked_as_read', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function notificationMarkedAsRead($id)
    {
        CRUD::hasAccessOrFail('markedAsRead');

        //Validate request data
        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'exists:notifications,id',
            ],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return notyValidatorError($validator);
        }

        Notification::find($id)->markAsRead();

        return notySuccess('The item is mark as read successfully.');
    }

    public function unreadCount()
    {
        $count = backpack_user()->unreadNotifications->count();

        $lastNotification = backpack_user()->unreadNotifications()->orderBy('created_at', 'desc')->first();

        return response()->json([
            'count' => $count,
            'last_notification' => $lastNotification ? $lastNotification->data : null,
        ]);
    }
}
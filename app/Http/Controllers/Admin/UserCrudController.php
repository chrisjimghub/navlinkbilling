<?php

namespace App\Http\Controllers\Admin;

use Winex01\BackpackPermissionManager\Http\Controllers\UserCrudController as Winex01UserCrudController;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends Winex01UserCrudController
{
    public function setup()
    {
        parent::setup();

        // NOTE:: do not put this in global scope because this is User model and if you add an
        // auth check in the global scope it will cause infinite loop,
        // AGAIN:: dont do what i did in Billing global scope OwnedByAuthenticatedCustomerScope.
        // because if you do, What will happen is! since this is User model, and if each model you do auth check
        // then it will blow the UNIVERSE!!!!, but since we are 100% sure that this /admin are all admin users then
        // we dont need to add auth check. now the users that will be display here are all those users that has null customer_id or a.k.a admins
    
        $this->crud->query->adminUsersOnly('131212592@gmail.com');
    } 

    public function setupListOperation()
    {
        parent::setupListOperation();
        
        $this->crud->setOperationSetting('showEntryCount', false);
    }

}

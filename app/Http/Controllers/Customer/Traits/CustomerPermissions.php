<?php

namespace App\Http\Controllers\Customer\Traits;

trait CustomerPermissions
{
    public function customerPermissions($permissions)
    {
        $this->crud->denyAllAccess();

        if (auth()->user()->isCustomer()) {
            $this->crud->allowAccess($permissions);
        }
    }
}

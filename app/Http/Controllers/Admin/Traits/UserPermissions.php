<?php 

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Support\Str;

/**
 * import in backpack crud controller
 * use in backpack crud controller
 */
trait UserPermissions
{
    /*
    |--------------------------------------------------------------------------
    | Check Auth User Permissions
    |--------------------------------------------------------------------------
    */ 
    private function userPermissions($role = null)
    {
        // check access for current role & admin
        $this->checkAccess($role);
        $this->checkAccess('admin');
        $this->checkAccess('menu_separator');
    }

    private function checkAccess($role)
    {
        $role = ($role == null) ? $this->crud->model->getTable() : $role;

        $allRolePermissions = config('permission.models.permission')::where('name', 'LIKE', "$role%")
                            ->pluck('name')->map(function ($item) use ($role) {
                                $value = str_replace($role.'_', '', $item);
                                $value = Str::camel($value);
                                return $value;
                            })->toArray();

        // dd($allRolePermissions);

        // deny all access first
        $this->crud->denyAccess($allRolePermissions);

        $permissions = auth()->user()->getAllPermissions()
            ->pluck('name')
            ->filter(function ($item) use ($role) {
                return false !== stristr($item, $role);
            })->map(function ($item) use ($role) {
                $value = str_replace($role.'_', '', $item);
                $value = Str::camel($value);
                return $value;
            })->toArray();

        // allow access if user have permission
        // debug($permissions);
        $this->crud->allowAccess($permissions);
    }
    
}
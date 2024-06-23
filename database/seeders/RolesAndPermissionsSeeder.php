<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * 
     */
    protected $rolesAndPermissions = [

        'admin' => [
            'admin_debugbar', 
            'admin_telescope', 
        ],

        // 'employees' => [
        //     'employees_list',
        //     'employees_create', 
        //     'employees_show', 
        //     'employees_update', 
        //     'employees_delete', 
        //     'employees_bulk_delete',
        //     'employees_export',
        //     'employees_force_delete',
        //     'employees_force_bulk_delete',
        //     'employees_revise',
        // ],

        'accounts' => [
            'accounts_list',
            'accounts_create', 
            'accounts_show', 
            'accounts_update', 
            'accounts_delete', 
            'accounts_revise',
        ],

       'customers' => [
            'customers_list',
            'customers_create', 
            'customers_show', 
            'customers_update', 
            'customers_delete', 
            'customers_revise',
        ],
        
        'planned_applications' => [
            'planned_applications_list',
            'planned_applications_create', 
            'planned_applications_show', 
            'planned_applications_update', 
            'planned_applications_delete', 
            'planned_applications_revise',
        ],

        'account_statuses' => [
            'account_statuses_list',
            'account_statuses_create', 
            'account_statuses_show', 
            'account_statuses_update', 
            'account_statuses_delete', 
            'account_statuses_revise',
        ],

        'locations' => [
            'locations_list',
            'locations_create', 
            'locations_show', 
            'locations_update', 
            'locations_delete', 
            'locations_revise',
        ],

        'subscriptions' => [
            'subscriptions_list',
            'subscriptions_create', 
            'subscriptions_show', 
            'subscriptions_update', 
            'subscriptions_delete', 
            'subscriptions_revise',
        ],

        // One-Time Charge
        'otcs' => [
            'otcs_list',
            'otcs_create', 
            'otcs_show', 
            'otcs_update', 
            'otcs_delete', 
            'otcs_revise',
        ],

        'contract_periods' => [
            'contract_periods_list',
            'contract_periods_create', 
            'contract_periods_show', 
            'contract_periods_update', 
            'contract_periods_delete', 
            'contract_periods_revise',
        ],

        'planned_application_types' => [
            'planned_application_types_list',
            'planned_application_types_create', 
            'planned_application_types_show', 
            'planned_application_types_update', 
            'planned_application_types_delete', 
            'planned_application_types_revise',
        ],

    ];

    /**
     * if backpack config is null 
     * then default is web
     */
    public $guardName;

    /**
     * 
     */
    public function __construct()
    {
        $this->guardName = config('backpack.base.guard') ?? 'web';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create specific permissions
        $this->createRolesAndPermissions();

        // assign all roles define in config/seeder to admin
        $this->assignAllRolesToAdmin();

    }

    private function assignAllRolesToAdmin()
    {
        // super admin ID = 1
        $admin = User::findOrFail(1);

        $roles = collect($this->rolesAndPermissions)->keys()->unique()->toArray();
        $admin->syncRoles($roles);
    }

    private function createRolesAndPermissions()
    {
        foreach ($this->rolesAndPermissions as $role => $permissions){
            // create role
            $roleInstance = config('permission.models.role')::firstOrCreate([
                'name' => $role,
                'guard_name' => $this->guardName,
            ]);

            foreach ($permissions as $rolePermission) {
               $permission = config('permission.models.permission')::firstOrCreate([
                    'name' => $rolePermission,
                    'guard_name' => $this->guardName,
                ]);
                
                // assign role_permission to role
               $permission->assignRole($role);
            }
        }

    }
}

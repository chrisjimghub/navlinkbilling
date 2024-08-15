<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * 
     */
    protected $rolesAndPermissions = [

        'admin' => [
            'admin_debugbar', // TODO:: add middleware
            'admin_telescope', // TODO:: add midleware
            'admin_file_manager',
            'admin_backups',
            'admin_logs',
            'admin_activity_logs',
        ],

        'billings' => [
            'billings_list',
            'billings_create', 
            'billings_show', 
            'billings_update', 
            'billings_delete', 
            'billings_button_pay', 
            'billings_pay', 
            'billings_pay_using_credit', 
            'billings_change_plan', 
            'billings_service_interrupt', 
            'billings_send_notification', 
            'billings_download_invoice', 
            'billings_filters', 
            'billings_export', 
            'billings_generate_by_group', 
        ],

        'menu_separator' => [
            'menu_separator_app_settings'
        ],

        'menus' => [
            'menus_list',
            'menus_create', 
            'menus_show', 
            'menus_update', 
            'menus_delete', 
            'menus_reorder', 
        ],

        'accounts' => [
            'accounts_list',
            'accounts_create', 
            'accounts_show', 
            'accounts_update', 
            'accounts_delete', 
            'accounts_filters', 
            'accounts_export', 
            'accounts_import', 
        ],

       'customers' => [
            'customers_list',
            'customers_create', 
            'customers_show', 
            'customers_update', 
            'customers_delete', 
            'customers_export', 
            'customers_import', 
            'customers_generate_portal_account', 
        ],
        
        'planned_applications' => [
            'planned_applications_list',
            'planned_applications_create', 
            'planned_applications_show', 
            'planned_applications_update', 
            'planned_applications_delete', 
        ],

        'account_statuses' => [
            'account_statuses_list',
            'account_statuses_create', 
            'account_statuses_show', 
            'account_statuses_update', 
            'account_statuses_delete', 
        ],

        'locations' => [
            'locations_list',
            'locations_create', 
            'locations_show', 
            'locations_update', 
            'locations_delete', 
        ],

        'subscriptions' => [
            'subscriptions_list',
            'subscriptions_create', 
            'subscriptions_show', 
            'subscriptions_update', 
        ],

        // One-Time Charge
        'otcs' => [
            'otcs_list',
            'otcs_create', 
            'otcs_show', 
            'otcs_update', 
            'otcs_delete', 
        ],

        'contract_periods' => [
            'contract_periods_list',
            'contract_periods_create', 
            'contract_periods_show', 
            'contract_periods_update', 
            'contract_periods_delete', 
        ],

        'planned_application_types' => [
            'planned_application_types_list',
            'planned_application_types_create', 
            'planned_application_types_show', 
            'planned_application_types_update', 
            'planned_application_types_delete', 
        ],
        
        'users' => [
            'users_list',
            'users_create', 
            'billings_show', 
            'users_update', 
            'users_delete', 
        ],

        'roles' => [
            'roles_list',
            'roles_create', 
            'roles_update', 
            'roles_delete', 
        ],

        'permissions' => [
            'permissions_list',
            'permissions_create', 
            'permissions_update', 
            'permissions_delete', 
        ],

        'account_service_interruptions' => [
            'account_service_interruptions_list',
            'account_service_interruptions_create', 
            'account_service_interruptions_update', 
            'account_service_interruptions_delete', 
        ],

        'account_credits' => [
            'account_credits_list',
            // 'account_credits_create', 
            // 'account_credits_update', 
            // 'account_credits_delete', 
        ],
        

        'settings' => [
            'settings_list',
            'settings_update', 
        ],

        'community_strings' => [
            'community_strings_list',
            'community_strings_create', 
            'community_strings_update', 
            'community_strings_delete', 
        ],

        'olts' => [
            'olts_list',
            'olts_create', 
            'olts_update', 
            'olts_delete', 
        ],

        'raisepon2s' => [
            'raisepon2s_list',
            'raisepon2s_create', 
            'raisepon2s_update', 
            'raisepon2s_delete', 
            'raisepon2s_api_config_settings', 
        ],

        'notifications' => [
            'notifications_list',
            'notifications_show',
            'notifications_delete',
            'notifications_cut_off', // permission to received noty
            'notifications_marked_as_read', // button operation
            'notifications_filters',
            'notifications_customer_payment',
        ],

        'billing_groupings' => [
            'billing_groupings_list',
            'billing_groupings_show',
            'billing_groupings_create', 
            'billing_groupings_update', 
            'billing_groupings_delete', 
        ],

        'piso_wifi_collectors' => [
            'piso_wifi_collectors_list',
            'piso_wifi_collectors_show',
            'piso_wifi_collectors_create', 
            'piso_wifi_collectors_update', 
            'piso_wifi_collectors_delete', 
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

        // collector
        $this->roleCollectorAndPermissions();

    }

    public function assignAllRolesToAdmin()
    {
        // super admin ID = 1
        $admin = User::findOrFail(1);

        $roles = collect($this->rolesAndPermissions)->keys()->unique()->toArray();
        $roles = array_diff($roles, $this->dontAssignRoles());
        $admin->syncRoles($roles);
    }

    public function createRolesAndPermissions()
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

    public function dontAssignRoles()
    {
        return [
            'community_strings',
            'olts',
            'raisepon2s',
            'subscriptions',
        ];
    }

    public function roleCollectorAndPermissions()
    {
        $role = config('permission.models.role')::firstOrCreate([
            'name' => 'collector',
            'guard_name' => $this->guardName,
        ]);

        $role->syncPermissions([
            'notifications_list',
            'notifications_show',
            'notifications_delete',
            'notifications_filters',
            'notifications_marked_as_read',
            'accounts_show',
        ]);
    }
}

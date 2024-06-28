<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accounts')->delete();
        
        \DB::table('accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'customer_id' => 12,
                'planned_application_id' => 170,
                'subscription_id' => 1,
                'installed_date' => '2014-11-04',
                'installed_address' => 'Quo dolorem aliquip',
                'google_map_coordinates' => NULL,
                'notes' => 'Minima sint id sint',
                'account_status_id' => 1,
                'created_at' => '2024-06-28 09:45:16',
                'updated_at' => '2024-06-28 09:45:16',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'customer_id' => 29,
                'planned_application_id' => 63,
                'subscription_id' => 1,
                'installed_date' => '2023-10-18',
                'installed_address' => 'Corporis ipsum ad au',
                'google_map_coordinates' => NULL,
                'notes' => 'Sint sunt nihil et f',
                'account_status_id' => 3,
                'created_at' => '2024-06-28 09:46:21',
                'updated_at' => '2024-06-28 09:46:21',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'customer_id' => 25,
                'planned_application_id' => 130,
                'subscription_id' => 1,
                'installed_date' => '1988-04-29',
                'installed_address' => 'Reiciendis molestiae',
                'google_map_coordinates' => NULL,
                'notes' => 'Nisi iure ullamco mo',
                'account_status_id' => 2,
                'created_at' => '2024-06-28 09:46:44',
                'updated_at' => '2024-06-28 09:46:44',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'customer_id' => 17,
                'planned_application_id' => 200,
                'subscription_id' => 2,
                'installed_date' => '2015-11-20',
                'installed_address' => 'Porro labore aliquip',
                'google_map_coordinates' => NULL,
                'notes' => 'Quaerat laborum Exc',
                'account_status_id' => 1,
                'created_at' => '2024-06-28 09:47:17',
                'updated_at' => '2024-06-28 09:47:17',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
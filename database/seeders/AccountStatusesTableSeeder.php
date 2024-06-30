<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('account_statuses')->delete();
        
        \DB::table('account_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Connected',
                'badge_css' => 'badge badge-success',
                'created_at' => '2024-06-20 13:36:42',
                'updated_at' => '2024-06-21 05:47:33',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Installing...',
                'badge_css' => 'badge badge-primary',
                'created_at' => '2024-06-20 13:36:49',
                'updated_at' => '2024-06-30 13:53:38',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Disconnected',
                'badge_css' => 'badge badge-danger',
                'created_at' => '2024-06-20 13:36:56',
                'updated_at' => '2024-06-21 05:47:40',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
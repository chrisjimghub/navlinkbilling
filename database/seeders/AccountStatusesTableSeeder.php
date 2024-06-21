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
                'created_at' => '2024-06-20 13:36:42',
                'updated_at' => '2024-06-21 05:47:33',
                'badge_css' => 'badge badge-success',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Processing...',
                'created_at' => '2024-06-20 13:36:49',
                'updated_at' => '2024-06-21 05:52:08',
                'badge_css' => 'badge badge-primary',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Disconnected',
                'created_at' => '2024-06-20 13:36:56',
                'updated_at' => '2024-06-21 05:47:40',
                'badge_css' => 'badge badge-danger',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Technical Issues',
                'created_at' => '2024-06-20 13:37:11',
                'updated_at' => '2024-06-21 05:47:57',
                'badge_css' => 'badge badge-warning',
            ),
        ));
        
        
    }
}
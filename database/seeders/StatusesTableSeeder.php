<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('statuses')->delete();
        
        \DB::table('statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Paid',
                'badge_css' => 'text-success',
                'created_at' => '2024-09-07 16:09:31',
                'updated_at' => '2024-09-07 16:09:31',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Unpaid',
                'badge_css' => 'text-danger',
                'created_at' => '2024-09-07 16:09:33',
                'updated_at' => '2024-09-07 16:09:33',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
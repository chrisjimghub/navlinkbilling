<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlannedApplicationTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('planned_application_types')->delete();
        
        \DB::table('planned_application_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'RESIDENTIAL / PERSONAL INTERNET CONNECTION',
                'created_at' => '2024-06-11 22:40:37',
                'updated_at' => '2024-06-11 22:40:37',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'FOR BUSINESS / COMMERCIAL INTERNET CONNECTION',
                'created_at' => '2024-06-11 22:41:05',
                'updated_at' => '2024-06-11 22:41:05',
            ),
        ));
        
        
    }
}
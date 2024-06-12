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
                'name' => 'Residential / Personal Internet Connection',
                'created_at' => '2024-06-11 22:40:37',
                'updated_at' => '2024-06-12 10:26:04',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'For Business / Commercial Internet Connection',
                'created_at' => '2024-06-11 22:41:05',
                'updated_at' => '2024-06-12 10:25:51',
            ),
        ));
        
        
    }
}
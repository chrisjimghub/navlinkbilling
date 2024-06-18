<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('locations')->delete();
        
        \DB::table('locations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Palompon to Jordan',
                'created_at' => '2024-06-18 12:51:33',
                'updated_at' => '2024-06-18 12:52:39',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'San Miguel',
                'created_at' => '2024-06-18 12:51:45',
                'updated_at' => '2024-06-18 12:51:45',
            ),
        ));
        
        
    }
}
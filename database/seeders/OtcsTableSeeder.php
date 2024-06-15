<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OtcsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('otcs')->delete();
        
        \DB::table('otcs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '2,500 OTC',
                'created_at' => '2024-06-15 13:12:34',
                'updated_at' => '2024-06-15 13:12:34',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Free Installation',
                'created_at' => '2024-06-15 13:12:42',
                'updated_at' => '2024-06-15 13:12:42',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Swap Antenna / Router Free Installation',
                'created_at' => '2024-06-15 13:13:01',
                'updated_at' => '2024-06-15 13:13:01',
            ),
        ));
        
        
    }
}
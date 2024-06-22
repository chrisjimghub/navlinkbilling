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
                'id' => 2,
                'name' => 'Free Installation',
                'amount' => 0.0,
                'created_at' => '2024-06-15 13:12:42',
                'updated_at' => '2024-06-15 13:12:42',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Swap Antenna / Router Free Installation',
                'amount' => 0.0,
                'created_at' => '2024-06-15 13:13:01',
                'updated_at' => '2024-06-15 13:13:01',
            ),
            2 => 
            array (
                'id' => 4,
            'name' => '4,500 OTC (P2P)',
                'amount' => 4500.0,
                'created_at' => '2024-06-22 00:51:09',
                'updated_at' => '2024-06-22 03:51:48',
            ),
            3 => 
            array (
                'id' => 5,
            'name' => '2,500 OTC (FIBER)',
                'amount' => 2500.0,
                'created_at' => '2024-06-22 00:51:26',
                'updated_at' => '2024-06-22 03:51:38',
            ),
        ));
        
        
    }
}
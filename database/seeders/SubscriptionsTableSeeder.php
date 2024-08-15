<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('subscriptions')->delete();
        
        \DB::table('subscriptions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'P2P',
                'created_at' => '2024-06-11 22:34:20',
                'updated_at' => '2024-06-11 22:34:40',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'FIBER',
                'created_at' => '2024-06-11 22:34:57',
                'updated_at' => '2024-06-11 22:34:57',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Piso Wifi',
                'created_at' => '2024-08-15 21:49:07',
                'updated_at' => '2024-08-15 21:49:07',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Hotspot Voucher',
                'created_at' => '2024-08-15 21:49:14',
                'updated_at' => '2024-08-15 21:49:14',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
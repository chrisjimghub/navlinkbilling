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
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'FIBER',
                'created_at' => '2024-06-11 22:34:57',
                'updated_at' => '2024-06-11 22:34:57',
            ),
        ));
        
        
    }
}
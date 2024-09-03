<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_methods')->delete();
        
        \DB::table('payment_methods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Cash',
                'created_at' => '2024-07-19 17:08:47',
                'updated_at' => '2024-07-19 17:08:47',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Credit',
                'created_at' => '2024-07-19 17:08:55',
                'updated_at' => '2024-07-19 17:08:55',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'GCash',
                'created_at' => '2024-07-19 17:09:15',
                'updated_at' => '2024-07-19 17:09:15',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Bank/Check',
                'created_at' => '2024-09-03 16:18:56',
                'updated_at' => '2024-09-03 16:18:56',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
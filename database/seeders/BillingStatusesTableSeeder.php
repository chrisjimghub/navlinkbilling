<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BillingStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('billing_statuses')->delete();
        
        \DB::table('billing_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Paid',
                'created_at' => '2024-07-03 09:04:41',
                'updated_at' => '2024-07-03 09:04:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Unpaid',
                'created_at' => '2024-07-03 09:04:48',
                'updated_at' => '2024-07-03 09:04:48',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Pending...',
                'created_at' => '2024-08-04 12:41:06',
                'updated_at' => '2024-08-04 12:41:06',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
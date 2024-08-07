<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BillingPeriodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('billing_periods')->delete();
        
        \DB::table('billing_periods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'previous_month_current_month',
                'created_at' => '2024-08-07 19:59:46',
                'updated_at' => '2024-08-07 19:59:46',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'current_month_current_month',
                'created_at' => '2024-08-07 19:59:57',
                'updated_at' => '2024-08-07 19:59:57',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'current_month_next_month',
                'created_at' => '2024-08-07 20:00:06',
                'updated_at' => '2024-08-07 20:00:06',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
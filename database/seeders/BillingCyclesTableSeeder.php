<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BillingCyclesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('billing_cycles')->delete();
        
        \DB::table('billing_cycles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Previous Month - Current Month',
                'created_at' => '2024-08-07 19:59:46',
                'updated_at' => '2024-08-07 19:59:46',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Current Month - Current Month',
                'created_at' => '2024-08-07 19:59:57',
                'updated_at' => '2024-08-07 19:59:57',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Current Month - Next Month',
                'created_at' => '2024-08-07 20:00:06',
                'updated_at' => '2024-08-07 20:00:06',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
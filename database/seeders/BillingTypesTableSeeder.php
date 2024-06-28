<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BillingTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('billing_types')->delete();
        
        \DB::table('billing_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Installation Fee',
                'created_at' => '2024-06-28 17:11:34',
                'updated_at' => '2024-06-28 17:11:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Monthly Fee',
                'created_at' => '2024-06-28 17:11:40',
                'updated_at' => '2024-06-28 17:11:40',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
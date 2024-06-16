<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ContractPeriodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('contract_periods')->delete();
        
        \DB::table('contract_periods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'With 12 months Lock-in',
                'created_at' => '2024-06-16 10:27:17',
                'updated_at' => '2024-06-16 10:27:17',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Advance 1-month monthly payment',
                'created_at' => '2024-06-16 10:27:33',
                'updated_at' => '2024-06-16 10:27:33',
            ),
        ));
        
        
    }
}
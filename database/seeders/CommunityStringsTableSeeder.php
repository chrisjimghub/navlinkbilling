<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommunityStringsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('community_strings')->delete();
        
        \DB::table('community_strings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'public',
                'created_at' => '2024-07-26 19:13:52',
                'updated_at' => '2024-07-26 19:13:52',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'private',
                'created_at' => '2024-07-26 19:13:57',
                'updated_at' => '2024-07-26 19:13:57',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$Lbl0iPguCudwWjeRpDBfi.HyT3CcIMjnE1Vv97p22CcvHqOR96Neq',
                'remember_token' => NULL,
                'created_at' => '2024-06-10 06:00:00',
                'updated_at' => '2024-06-10 06:00:00',
            ),
        ));
        
        
    }
}
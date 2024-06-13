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
                'remember_token' => 'm7XHgYUQeZzLlBjG2do1E1LVrfdPMRaRvUAwqVVKMeWlm1jrAFbkZEfYI9gX',
                'created_at' => '2024-06-10 06:00:00',
                'updated_at' => '2024-06-10 06:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'test',
                'email' => 'test@test.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$1NccbqfQJ6TaIMbdy5QL1eQhBsWx11lMMx8UhGTPyv5Q/1tdqeVGG',
                'remember_token' => NULL,
                'created_at' => '2024-06-13 05:19:49',
                'updated_at' => '2024-06-13 05:19:49',
            ),
        ));
        
        
    }
}
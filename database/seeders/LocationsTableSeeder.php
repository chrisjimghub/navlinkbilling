<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('locations')->delete();
        
        \DB::table('locations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Palompon',
                'created_at' => '2024-06-18 12:51:33',
                'updated_at' => '2024-06-20 15:04:16',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'San Miguel',
                'created_at' => '2024-06-18 12:51:45',
                'updated_at' => '2024-06-18 12:51:45',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Tinubdan',
                'created_at' => '2024-06-20 01:32:00',
                'updated_at' => '2024-06-20 01:32:00',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Tinago',
                'created_at' => '2024-06-20 01:32:16',
                'updated_at' => '2024-06-20 01:32:16',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'San Vicente',
                'created_at' => '2024-06-20 01:32:41',
                'updated_at' => '2024-06-20 01:32:41',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'Fatima',
                'created_at' => '2024-06-20 01:33:05',
                'updated_at' => '2024-06-20 01:33:05',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 8,
                'name' => 'Binun-an',
                'created_at' => '2024-06-20 01:33:12',
                'updated_at' => '2024-06-20 01:33:12',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 9,
                'name' => 'Balite',
                'created_at' => '2024-06-20 01:33:16',
                'updated_at' => '2024-06-20 01:33:16',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 10,
                'name' => 'Rizal',
                'created_at' => '2024-06-20 01:54:24',
                'updated_at' => '2024-06-20 01:54:24',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 11,
                'name' => 'Cambinoy',
                'created_at' => '2024-06-20 01:54:32',
                'updated_at' => '2024-06-20 01:54:32',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 12,
                'name' => 'San Pedro',
                'created_at' => '2024-06-20 01:54:41',
                'updated_at' => '2024-06-20 01:54:41',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 13,
                'name' => 'Tambis',
                'created_at' => '2024-06-20 01:54:47',
                'updated_at' => '2024-06-20 01:54:47',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 14,
                'name' => 'Badiang',
                'created_at' => '2024-06-20 01:54:55',
                'updated_at' => '2024-06-20 01:54:55',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 15,
                'name' => 'Tagbubunga',
                'created_at' => '2024-06-20 01:55:13',
                'updated_at' => '2024-06-20 01:55:13',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 16,
                'name' => 'Abijao',
                'created_at' => '2024-06-20 01:55:19',
                'updated_at' => '2024-06-20 01:55:19',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 17,
                'name' => 'Jordan',
                'created_at' => '2024-06-20 01:55:25',
                'updated_at' => '2024-06-20 01:55:25',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 18,
                'name' => 'Bangkal',
                'created_at' => '2024-06-20 01:55:30',
                'updated_at' => '2024-06-20 01:55:30',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 19,
                'name' => 'Liberty',
                'created_at' => '2024-06-20 02:16:01',
                'updated_at' => '2024-06-20 02:16:01',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 20,
                'name' => 'Santiago',
                'created_at' => '2024-06-20 02:16:12',
                'updated_at' => '2024-06-20 02:16:12',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 21,
                'name' => 'Caduhaan',
                'created_at' => '2024-06-20 02:16:19',
                'updated_at' => '2024-06-20 02:16:19',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 22,
                'name' => 'Himarco',
                'created_at' => '2024-06-20 02:24:05',
                'updated_at' => '2024-06-20 02:24:05',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 24,
                'name' => 'Tabunok Sabang',
                'created_at' => '2024-06-20 02:24:13',
                'updated_at' => '2024-06-20 02:42:30',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 25,
                'name' => 'Cambinos',
                'created_at' => '2024-06-20 02:24:21',
                'updated_at' => '2024-06-20 02:24:21',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 26,
                'name' => 'Jalas',
                'created_at' => '2024-06-20 02:53:18',
                'updated_at' => '2024-06-20 02:53:18',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 27,
                'name' => 'Villaba Proper',
                'created_at' => '2024-06-20 02:53:23',
                'updated_at' => '2024-06-20 02:53:23',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 28,
                'name' => 'Tabango',
                'created_at' => '2024-06-20 02:53:27',
                'updated_at' => '2024-06-20 02:53:27',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 29,
                'name' => 'Asinan',
                'created_at' => '2024-08-05 16:23:09',
                'updated_at' => '2024-08-05 16:23:09',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 30,
                'name' => 'Lomonon',
                'created_at' => '2024-08-05 16:24:24',
                'updated_at' => '2024-08-05 16:24:24',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 31,
                'name' => 'Bugho',
                'created_at' => '2024-08-05 16:28:06',
                'updated_at' => '2024-08-05 16:28:06',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 32,
                'name' => 'Canduman',
                'created_at' => '2024-08-05 16:30:27',
                'updated_at' => '2024-08-05 16:30:36',
                'deleted_at' => '2024-08-05 16:30:36',
            ),
            30 => 
            array (
                'id' => 33,
                'name' => 'Cambacbac',
                'created_at' => '2024-08-05 16:35:48',
                'updated_at' => '2024-08-05 16:35:48',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 34,
                'name' => 'SAN JOAQUIN',
                'created_at' => '2024-08-05 16:48:29',
                'updated_at' => '2024-08-05 16:48:29',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 35,
                'name' => 'CANTANDOY',
                'created_at' => '2024-08-05 16:50:30',
                'updated_at' => '2024-08-05 16:50:30',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
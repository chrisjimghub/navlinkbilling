<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlannedApplicationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('planned_applications')->delete();
        
        \DB::table('planned_applications')->insert(array (
            0 => 
            array (
                'id' => 1,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '999.00',
                'created_at' => '2024-06-18 13:00:31',
                'updated_at' => '2024-06-18 13:05:18',
                'location_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1199.00',
                'created_at' => '2024-06-18 13:05:33',
                'updated_at' => '2024-06-18 13:05:33',
                'location_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1299.00',
                'created_at' => '2024-06-18 13:08:18',
                'updated_at' => '2024-06-18 13:08:18',
                'location_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1399.00',
                'created_at' => '2024-06-18 13:08:39',
                'updated_at' => '2024-06-18 13:08:39',
                'location_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1599.00',
                'created_at' => '2024-06-18 13:09:02',
                'updated_at' => '2024-06-18 13:09:02',
                'location_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1199.00',
                'created_at' => '2024-06-18 13:09:28',
                'updated_at' => '2024-06-18 13:09:28',
                'location_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1299.00',
                'created_at' => '2024-06-18 13:11:34',
                'updated_at' => '2024-06-18 13:11:34',
                'location_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1399.00',
                'created_at' => '2024-06-18 13:12:17',
                'updated_at' => '2024-06-18 13:12:17',
                'location_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1599.00',
                'created_at' => '2024-06-18 13:12:36',
                'updated_at' => '2024-06-18 13:12:36',
                'location_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1899.00',
                'created_at' => '2024-06-18 13:12:49',
                'updated_at' => '2024-06-18 13:12:49',
                'location_id' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '999.00',
                'created_at' => '2024-06-18 13:13:15',
                'updated_at' => '2024-06-18 13:13:15',
                'location_id' => 2,
            ),
            11 => 
            array (
                'id' => 12,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1200.00',
                'created_at' => '2024-06-18 13:13:33',
                'updated_at' => '2024-06-18 13:13:33',
                'location_id' => 2,
            ),
            12 => 
            array (
                'id' => 13,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '1600.00',
                'created_at' => '2024-06-18 13:13:48',
                'updated_at' => '2024-06-18 13:13:48',
                'location_id' => 2,
            ),
            13 => 
            array (
                'id' => 14,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '2000.00',
                'created_at' => '2024-06-18 13:14:03',
                'updated_at' => '2024-06-18 13:14:03',
                'location_id' => 2,
            ),
            14 => 
            array (
                'id' => 15,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1200.00',
                'created_at' => '2024-06-18 13:14:19',
                'updated_at' => '2024-06-18 13:14:19',
                'location_id' => 2,
            ),
            15 => 
            array (
                'id' => 16,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1500.00',
                'created_at' => '2024-06-18 13:14:47',
                'updated_at' => '2024-06-18 13:14:47',
                'location_id' => 2,
            ),
            16 => 
            array (
                'id' => 17,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1800.00',
                'created_at' => '2024-06-18 13:15:03',
                'updated_at' => '2024-06-18 13:15:03',
                'location_id' => 2,
            ),
            17 => 
            array (
                'id' => 18,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '2400.00',
                'created_at' => '2024-06-18 13:15:23',
                'updated_at' => '2024-06-18 13:15:23',
                'location_id' => 2,
            ),
        ));
        
        
    }
}
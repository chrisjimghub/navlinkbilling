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
            18 => 
            array (
                'id' => 19,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '899.00',
                'created_at' => '2024-06-20 01:34:13',
                'updated_at' => '2024-06-20 01:34:13',
                'location_id' => 3,
            ),
            19 => 
            array (
                'id' => 20,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '999.00',
                'created_at' => '2024-06-20 01:34:29',
                'updated_at' => '2024-06-20 01:34:29',
                'location_id' => 3,
            ),
            20 => 
            array (
                'id' => 21,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:35:20',
                'updated_at' => '2024-06-20 01:35:20',
                'location_id' => 3,
            ),
            21 => 
            array (
                'id' => 22,
                'planned_application_type_id' => 1,
                'mbps' => 35,
                'price' => '1299.00',
                'created_at' => '2024-06-20 01:35:49',
                'updated_at' => '2024-06-20 01:35:49',
                'location_id' => 3,
            ),
            22 => 
            array (
                'id' => 23,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:36:19',
                'updated_at' => '2024-06-20 01:36:19',
                'location_id' => 3,
            ),
            23 => 
            array (
                'id' => 24,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1399.00',
                'created_at' => '2024-06-20 01:36:39',
                'updated_at' => '2024-06-20 01:36:39',
                'location_id' => 3,
            ),
            24 => 
            array (
                'id' => 25,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1599.00',
                'created_at' => '2024-06-20 01:36:53',
                'updated_at' => '2024-06-20 01:36:53',
                'location_id' => 3,
            ),
            25 => 
            array (
                'id' => 26,
                'planned_application_type_id' => 2,
                'mbps' => 35,
                'price' => '1799.00',
                'created_at' => '2024-06-20 01:37:09',
                'updated_at' => '2024-06-20 01:37:09',
                'location_id' => 3,
            ),
            26 => 
            array (
                'id' => 27,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '899.00',
                'created_at' => '2024-06-20 01:40:39',
                'updated_at' => '2024-06-20 01:40:39',
                'location_id' => 4,
            ),
            27 => 
            array (
                'id' => 28,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '999.00',
                'created_at' => '2024-06-20 01:40:55',
                'updated_at' => '2024-06-20 01:40:55',
                'location_id' => 4,
            ),
            28 => 
            array (
                'id' => 29,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:41:11',
                'updated_at' => '2024-06-20 01:41:11',
                'location_id' => 4,
            ),
            29 => 
            array (
                'id' => 30,
                'planned_application_type_id' => 1,
                'mbps' => 35,
                'price' => '1299.00',
                'created_at' => '2024-06-20 01:41:25',
                'updated_at' => '2024-06-20 01:41:25',
                'location_id' => 4,
            ),
            30 => 
            array (
                'id' => 31,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:41:39',
                'updated_at' => '2024-06-20 01:41:39',
                'location_id' => 4,
            ),
            31 => 
            array (
                'id' => 32,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1399.00',
                'created_at' => '2024-06-20 01:42:33',
                'updated_at' => '2024-06-20 01:42:33',
                'location_id' => 4,
            ),
            32 => 
            array (
                'id' => 33,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1599.00',
                'created_at' => '2024-06-20 01:42:56',
                'updated_at' => '2024-06-20 01:42:56',
                'location_id' => 4,
            ),
            33 => 
            array (
                'id' => 34,
                'planned_application_type_id' => 2,
                'mbps' => 35,
                'price' => '1799.00',
                'created_at' => '2024-06-20 01:43:11',
                'updated_at' => '2024-06-20 01:43:11',
                'location_id' => 4,
            ),
            34 => 
            array (
                'id' => 35,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '899.00',
                'created_at' => '2024-06-20 01:45:21',
                'updated_at' => '2024-06-20 01:45:21',
                'location_id' => 9,
            ),
            35 => 
            array (
                'id' => 36,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '999.00',
                'created_at' => '2024-06-20 01:45:32',
                'updated_at' => '2024-06-20 01:45:32',
                'location_id' => 9,
            ),
            36 => 
            array (
                'id' => 37,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:45:46',
                'updated_at' => '2024-06-20 01:45:46',
                'location_id' => 9,
            ),
            37 => 
            array (
                'id' => 38,
                'planned_application_type_id' => 1,
                'mbps' => 35,
                'price' => '1299.00',
                'created_at' => '2024-06-20 01:46:02',
                'updated_at' => '2024-06-20 01:46:02',
                'location_id' => 9,
            ),
            38 => 
            array (
                'id' => 39,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:46:15',
                'updated_at' => '2024-06-20 01:46:15',
                'location_id' => 9,
            ),
            39 => 
            array (
                'id' => 40,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1399.00',
                'created_at' => '2024-06-20 01:46:32',
                'updated_at' => '2024-06-20 01:46:32',
                'location_id' => 9,
            ),
            40 => 
            array (
                'id' => 41,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1599.00',
                'created_at' => '2024-06-20 01:46:45',
                'updated_at' => '2024-06-20 01:46:45',
                'location_id' => 9,
            ),
            41 => 
            array (
                'id' => 42,
                'planned_application_type_id' => 2,
                'mbps' => 35,
                'price' => '1799.00',
                'created_at' => '2024-06-20 01:46:57',
                'updated_at' => '2024-06-20 01:46:57',
                'location_id' => 9,
            ),
            42 => 
            array (
                'id' => 43,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '899.00',
                'created_at' => '2024-06-20 01:47:13',
                'updated_at' => '2024-06-20 01:47:13',
                'location_id' => 6,
            ),
            43 => 
            array (
                'id' => 44,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '999.00',
                'created_at' => '2024-06-20 01:47:29',
                'updated_at' => '2024-06-20 01:47:29',
                'location_id' => 6,
            ),
            44 => 
            array (
                'id' => 45,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:48:00',
                'updated_at' => '2024-06-20 01:48:00',
                'location_id' => 6,
            ),
            45 => 
            array (
                'id' => 46,
                'planned_application_type_id' => 1,
                'mbps' => 35,
                'price' => '1299.00',
                'created_at' => '2024-06-20 01:48:11',
                'updated_at' => '2024-06-20 01:48:11',
                'location_id' => 6,
            ),
            46 => 
            array (
                'id' => 47,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:48:21',
                'updated_at' => '2024-06-20 01:48:21',
                'location_id' => 6,
            ),
            47 => 
            array (
                'id' => 48,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1399.00',
                'created_at' => '2024-06-20 01:48:47',
                'updated_at' => '2024-06-20 01:48:47',
                'location_id' => 6,
            ),
            48 => 
            array (
                'id' => 49,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1599.00',
                'created_at' => '2024-06-20 01:49:12',
                'updated_at' => '2024-06-20 01:49:12',
                'location_id' => 6,
            ),
            49 => 
            array (
                'id' => 50,
                'planned_application_type_id' => 2,
                'mbps' => 35,
                'price' => '1799.00',
                'created_at' => '2024-06-20 01:49:35',
                'updated_at' => '2024-06-20 01:49:35',
                'location_id' => 6,
            ),
            50 => 
            array (
                'id' => 51,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '899.00',
                'created_at' => '2024-06-20 01:50:27',
                'updated_at' => '2024-06-20 01:50:27',
                'location_id' => 7,
            ),
            51 => 
            array (
                'id' => 52,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '999.00',
                'created_at' => '2024-06-20 01:50:36',
                'updated_at' => '2024-06-20 01:50:36',
                'location_id' => 7,
            ),
            52 => 
            array (
                'id' => 53,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:50:47',
                'updated_at' => '2024-06-20 01:50:47',
                'location_id' => 7,
            ),
            53 => 
            array (
                'id' => 54,
                'planned_application_type_id' => 1,
                'mbps' => 35,
                'price' => '1299.00',
                'created_at' => '2024-06-20 01:50:59',
                'updated_at' => '2024-06-20 01:50:59',
                'location_id' => 7,
            ),
            54 => 
            array (
                'id' => 55,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:51:11',
                'updated_at' => '2024-06-20 01:51:11',
                'location_id' => 7,
            ),
            55 => 
            array (
                'id' => 56,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1399.00',
                'created_at' => '2024-06-20 01:51:24',
                'updated_at' => '2024-06-20 01:51:24',
                'location_id' => 7,
            ),
            56 => 
            array (
                'id' => 57,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1599.00',
                'created_at' => '2024-06-20 01:51:39',
                'updated_at' => '2024-06-20 01:51:39',
                'location_id' => 7,
            ),
            57 => 
            array (
                'id' => 58,
                'planned_application_type_id' => 2,
                'mbps' => 35,
                'price' => '1799.00',
                'created_at' => '2024-06-20 01:51:54',
                'updated_at' => '2024-06-20 01:51:54',
                'location_id' => 7,
            ),
            58 => 
            array (
                'id' => 59,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '899.00',
                'created_at' => '2024-06-20 01:52:13',
                'updated_at' => '2024-06-20 01:52:13',
                'location_id' => 8,
            ),
            59 => 
            array (
                'id' => 60,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '999.00',
                'created_at' => '2024-06-20 01:52:23',
                'updated_at' => '2024-06-20 01:52:23',
                'location_id' => 8,
            ),
            60 => 
            array (
                'id' => 61,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:52:34',
                'updated_at' => '2024-06-20 01:52:34',
                'location_id' => 8,
            ),
            61 => 
            array (
                'id' => 62,
                'planned_application_type_id' => 1,
                'mbps' => 35,
                'price' => '1299.00',
                'created_at' => '2024-06-20 01:52:43',
                'updated_at' => '2024-06-20 01:52:43',
                'location_id' => 8,
            ),
            62 => 
            array (
                'id' => 63,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1199.00',
                'created_at' => '2024-06-20 01:53:06',
                'updated_at' => '2024-06-20 01:53:06',
                'location_id' => 8,
            ),
            63 => 
            array (
                'id' => 64,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1399.00',
                'created_at' => '2024-06-20 01:53:15',
                'updated_at' => '2024-06-20 01:53:15',
                'location_id' => 8,
            ),
            64 => 
            array (
                'id' => 65,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1599.00',
                'created_at' => '2024-06-20 01:53:26',
                'updated_at' => '2024-06-20 01:53:26',
                'location_id' => 8,
            ),
            65 => 
            array (
                'id' => 66,
                'planned_application_type_id' => 2,
                'mbps' => 35,
                'price' => '1799.00',
                'created_at' => '2024-06-20 01:53:36',
                'updated_at' => '2024-06-20 01:53:36',
                'location_id' => 8,
            ),
            66 => 
            array (
                'id' => 67,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 01:56:02',
                'updated_at' => '2024-06-20 01:56:02',
                'location_id' => 10,
            ),
            67 => 
            array (
                'id' => 68,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 01:56:23',
                'updated_at' => '2024-06-20 01:56:23',
                'location_id' => 10,
            ),
            68 => 
            array (
                'id' => 69,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 01:56:42',
                'updated_at' => '2024-06-20 01:56:42',
                'location_id' => 10,
            ),
            69 => 
            array (
                'id' => 70,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 01:57:34',
                'updated_at' => '2024-06-20 01:57:34',
                'location_id' => 10,
            ),
            70 => 
            array (
                'id' => 71,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 01:57:48',
                'updated_at' => '2024-06-20 01:58:17',
                'location_id' => 10,
            ),
            71 => 
            array (
                'id' => 72,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 01:58:36',
                'updated_at' => '2024-06-20 01:58:36',
                'location_id' => 10,
            ),
            72 => 
            array (
                'id' => 73,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 01:58:49',
                'updated_at' => '2024-06-20 01:58:49',
                'location_id' => 10,
            ),
            73 => 
            array (
                'id' => 74,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 01:58:59',
                'updated_at' => '2024-06-20 01:58:59',
                'location_id' => 10,
            ),
            74 => 
            array (
                'id' => 75,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 01:59:19',
                'updated_at' => '2024-06-20 01:59:19',
                'location_id' => 11,
            ),
            75 => 
            array (
                'id' => 76,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 01:59:30',
                'updated_at' => '2024-06-20 01:59:30',
                'location_id' => 11,
            ),
            76 => 
            array (
                'id' => 77,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 01:59:58',
                'updated_at' => '2024-06-20 01:59:58',
                'location_id' => 11,
            ),
            77 => 
            array (
                'id' => 78,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:00:11',
                'updated_at' => '2024-06-20 02:00:11',
                'location_id' => 11,
            ),
            78 => 
            array (
                'id' => 79,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:00:20',
                'updated_at' => '2024-06-20 02:00:20',
                'location_id' => 11,
            ),
            79 => 
            array (
                'id' => 80,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:00:27',
                'updated_at' => '2024-06-20 02:00:27',
                'location_id' => 11,
            ),
            80 => 
            array (
                'id' => 81,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:00:41',
                'updated_at' => '2024-06-20 02:00:41',
                'location_id' => 11,
            ),
            81 => 
            array (
                'id' => 82,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:01:04',
                'updated_at' => '2024-06-20 02:01:04',
                'location_id' => 11,
            ),
            82 => 
            array (
                'id' => 83,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:01:45',
                'updated_at' => '2024-06-20 02:01:45',
                'location_id' => 12,
            ),
            83 => 
            array (
                'id' => 84,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:02:13',
                'updated_at' => '2024-06-20 02:02:13',
                'location_id' => 12,
            ),
            84 => 
            array (
                'id' => 85,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:02:22',
                'updated_at' => '2024-06-20 02:02:22',
                'location_id' => 12,
            ),
            85 => 
            array (
                'id' => 86,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:02:40',
                'updated_at' => '2024-06-20 02:02:40',
                'location_id' => 12,
            ),
            86 => 
            array (
                'id' => 87,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:03:01',
                'updated_at' => '2024-06-20 02:03:01',
                'location_id' => 12,
            ),
            87 => 
            array (
                'id' => 88,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:03:09',
                'updated_at' => '2024-06-20 02:03:09',
                'location_id' => 12,
            ),
            88 => 
            array (
                'id' => 89,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:03:18',
                'updated_at' => '2024-06-20 02:03:18',
                'location_id' => 12,
            ),
            89 => 
            array (
                'id' => 90,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:03:39',
                'updated_at' => '2024-06-20 02:03:39',
                'location_id' => 12,
            ),
            90 => 
            array (
                'id' => 91,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:04:02',
                'updated_at' => '2024-06-20 02:04:02',
                'location_id' => 13,
            ),
            91 => 
            array (
                'id' => 92,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:04:12',
                'updated_at' => '2024-06-20 02:04:12',
                'location_id' => 13,
            ),
            92 => 
            array (
                'id' => 93,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:04:23',
                'updated_at' => '2024-06-20 02:04:23',
                'location_id' => 13,
            ),
            93 => 
            array (
                'id' => 94,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:04:38',
                'updated_at' => '2024-06-20 02:04:38',
                'location_id' => 13,
            ),
            94 => 
            array (
                'id' => 95,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:04:49',
                'updated_at' => '2024-06-20 02:04:49',
                'location_id' => 13,
            ),
            95 => 
            array (
                'id' => 96,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:04:58',
                'updated_at' => '2024-06-20 02:04:58',
                'location_id' => 13,
            ),
            96 => 
            array (
                'id' => 97,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:05:14',
                'updated_at' => '2024-06-20 02:05:14',
                'location_id' => 13,
            ),
            97 => 
            array (
                'id' => 98,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:05:23',
                'updated_at' => '2024-06-20 02:05:23',
                'location_id' => 13,
            ),
            98 => 
            array (
                'id' => 99,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:05:41',
                'updated_at' => '2024-06-20 02:05:41',
                'location_id' => 14,
            ),
            99 => 
            array (
                'id' => 100,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:05:48',
                'updated_at' => '2024-06-20 02:05:48',
                'location_id' => 14,
            ),
            100 => 
            array (
                'id' => 101,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:06:01',
                'updated_at' => '2024-06-20 02:06:01',
                'location_id' => 14,
            ),
            101 => 
            array (
                'id' => 102,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:06:14',
                'updated_at' => '2024-06-20 02:06:14',
                'location_id' => 14,
            ),
            102 => 
            array (
                'id' => 103,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:06:22',
                'updated_at' => '2024-06-20 02:06:22',
                'location_id' => 14,
            ),
            103 => 
            array (
                'id' => 104,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:06:32',
                'updated_at' => '2024-06-20 02:06:32',
                'location_id' => 14,
            ),
            104 => 
            array (
                'id' => 105,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:06:43',
                'updated_at' => '2024-06-20 02:06:43',
                'location_id' => 14,
            ),
            105 => 
            array (
                'id' => 106,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:06:55',
                'updated_at' => '2024-06-20 02:06:55',
                'location_id' => 14,
            ),
            106 => 
            array (
                'id' => 107,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:07:21',
                'updated_at' => '2024-06-20 02:07:21',
                'location_id' => 15,
            ),
            107 => 
            array (
                'id' => 108,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:07:31',
                'updated_at' => '2024-06-20 02:07:31',
                'location_id' => 15,
            ),
            108 => 
            array (
                'id' => 109,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:07:41',
                'updated_at' => '2024-06-20 02:07:41',
                'location_id' => 15,
            ),
            109 => 
            array (
                'id' => 110,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:07:54',
                'updated_at' => '2024-06-20 02:07:54',
                'location_id' => 15,
            ),
            110 => 
            array (
                'id' => 111,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:08:03',
                'updated_at' => '2024-06-20 02:08:03',
                'location_id' => 15,
            ),
            111 => 
            array (
                'id' => 112,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:08:22',
                'updated_at' => '2024-06-20 02:08:22',
                'location_id' => 15,
            ),
            112 => 
            array (
                'id' => 113,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:09:19',
                'updated_at' => '2024-06-20 02:09:19',
                'location_id' => 15,
            ),
            113 => 
            array (
                'id' => 114,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:09:32',
                'updated_at' => '2024-06-20 02:09:32',
                'location_id' => 15,
            ),
            114 => 
            array (
                'id' => 115,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:10:03',
                'updated_at' => '2024-06-20 02:10:03',
                'location_id' => 16,
            ),
            115 => 
            array (
                'id' => 116,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:10:13',
                'updated_at' => '2024-06-20 02:10:13',
                'location_id' => 16,
            ),
            116 => 
            array (
                'id' => 117,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:10:24',
                'updated_at' => '2024-06-20 02:10:24',
                'location_id' => 16,
            ),
            117 => 
            array (
                'id' => 118,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:10:37',
                'updated_at' => '2024-06-20 02:10:37',
                'location_id' => 16,
            ),
            118 => 
            array (
                'id' => 119,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:10:48',
                'updated_at' => '2024-06-20 02:10:48',
                'location_id' => 16,
            ),
            119 => 
            array (
                'id' => 120,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:10:57',
                'updated_at' => '2024-06-20 02:10:57',
                'location_id' => 16,
            ),
            120 => 
            array (
                'id' => 121,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:11:05',
                'updated_at' => '2024-06-20 02:11:05',
                'location_id' => 16,
            ),
            121 => 
            array (
                'id' => 122,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:11:12',
                'updated_at' => '2024-06-20 02:11:12',
                'location_id' => 16,
            ),
            122 => 
            array (
                'id' => 123,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:11:26',
                'updated_at' => '2024-06-20 02:11:26',
                'location_id' => 17,
            ),
            123 => 
            array (
                'id' => 124,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:11:38',
                'updated_at' => '2024-06-20 02:11:38',
                'location_id' => 17,
            ),
            124 => 
            array (
                'id' => 125,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:11:58',
                'updated_at' => '2024-06-20 02:11:58',
                'location_id' => 17,
            ),
            125 => 
            array (
                'id' => 126,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:12:10',
                'updated_at' => '2024-06-20 02:12:10',
                'location_id' => 17,
            ),
            126 => 
            array (
                'id' => 127,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:12:18',
                'updated_at' => '2024-06-20 02:12:18',
                'location_id' => 17,
            ),
            127 => 
            array (
                'id' => 128,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:12:26',
                'updated_at' => '2024-06-20 02:12:26',
                'location_id' => 17,
            ),
            128 => 
            array (
                'id' => 129,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:12:35',
                'updated_at' => '2024-06-20 02:12:35',
                'location_id' => 17,
            ),
            129 => 
            array (
                'id' => 130,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:12:45',
                'updated_at' => '2024-06-20 02:12:45',
                'location_id' => 17,
            ),
            130 => 
            array (
                'id' => 131,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:13:12',
                'updated_at' => '2024-06-20 02:13:12',
                'location_id' => 18,
            ),
            131 => 
            array (
                'id' => 132,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:13:29',
                'updated_at' => '2024-06-20 02:13:29',
                'location_id' => 18,
            ),
            132 => 
            array (
                'id' => 133,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:14:17',
                'updated_at' => '2024-06-20 02:14:17',
                'location_id' => 18,
            ),
            133 => 
            array (
                'id' => 134,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:14:30',
                'updated_at' => '2024-06-20 02:14:30',
                'location_id' => 18,
            ),
            134 => 
            array (
                'id' => 135,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:14:55',
                'updated_at' => '2024-06-20 02:14:55',
                'location_id' => 18,
            ),
            135 => 
            array (
                'id' => 136,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:15:08',
                'updated_at' => '2024-06-20 02:15:08',
                'location_id' => 18,
            ),
            136 => 
            array (
                'id' => 137,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:15:18',
                'updated_at' => '2024-06-20 02:15:18',
                'location_id' => 18,
            ),
            137 => 
            array (
                'id' => 138,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:15:34',
                'updated_at' => '2024-06-20 02:15:34',
                'location_id' => 18,
            ),
            138 => 
            array (
                'id' => 139,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '899.00',
                'created_at' => '2024-06-20 02:16:46',
                'updated_at' => '2024-06-20 02:16:46',
                'location_id' => 19,
            ),
            139 => 
            array (
                'id' => 140,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:18:16',
                'updated_at' => '2024-06-20 02:18:16',
                'location_id' => 19,
            ),
            140 => 
            array (
                'id' => 141,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:18:30',
                'updated_at' => '2024-06-20 02:18:30',
                'location_id' => 19,
            ),
            141 => 
            array (
                'id' => 142,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1600.00',
                'created_at' => '2024-06-20 02:18:50',
                'updated_at' => '2024-06-20 02:18:50',
                'location_id' => 19,
            ),
            142 => 
            array (
                'id' => 143,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:19:10',
                'updated_at' => '2024-06-20 02:19:10',
                'location_id' => 19,
            ),
            143 => 
            array (
                'id' => 144,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:19:22',
                'updated_at' => '2024-06-20 02:19:22',
                'location_id' => 19,
            ),
            144 => 
            array (
                'id' => 145,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1600.00',
                'created_at' => '2024-06-20 02:19:42',
                'updated_at' => '2024-06-20 02:19:42',
                'location_id' => 19,
            ),
            145 => 
            array (
                'id' => 146,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2000.00',
                'created_at' => '2024-06-20 02:19:51',
                'updated_at' => '2024-06-20 02:19:51',
                'location_id' => 19,
            ),
            146 => 
            array (
                'id' => 147,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '899.00',
                'created_at' => '2024-06-20 02:20:20',
                'updated_at' => '2024-06-20 02:20:20',
                'location_id' => 20,
            ),
            147 => 
            array (
                'id' => 148,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:20:28',
                'updated_at' => '2024-06-20 02:20:28',
                'location_id' => 20,
            ),
            148 => 
            array (
                'id' => 149,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:20:39',
                'updated_at' => '2024-06-20 02:20:39',
                'location_id' => 20,
            ),
            149 => 
            array (
                'id' => 150,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1600.00',
                'created_at' => '2024-06-20 02:20:58',
                'updated_at' => '2024-06-20 02:20:58',
                'location_id' => 20,
            ),
            150 => 
            array (
                'id' => 151,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:21:17',
                'updated_at' => '2024-06-20 02:21:17',
                'location_id' => 20,
            ),
            151 => 
            array (
                'id' => 152,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:21:27',
                'updated_at' => '2024-06-20 02:21:27',
                'location_id' => 20,
            ),
            152 => 
            array (
                'id' => 153,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1600.00',
                'created_at' => '2024-06-20 02:21:40',
                'updated_at' => '2024-06-20 02:21:40',
                'location_id' => 20,
            ),
            153 => 
            array (
                'id' => 154,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2000.00',
                'created_at' => '2024-06-20 02:21:49',
                'updated_at' => '2024-06-20 02:21:49',
                'location_id' => 20,
            ),
            154 => 
            array (
                'id' => 155,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '899.00',
                'created_at' => '2024-06-20 02:22:14',
                'updated_at' => '2024-06-20 02:22:14',
                'location_id' => 21,
            ),
            155 => 
            array (
                'id' => 156,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:22:23',
                'updated_at' => '2024-06-20 02:22:23',
                'location_id' => 21,
            ),
            156 => 
            array (
                'id' => 157,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:22:33',
                'updated_at' => '2024-06-20 02:22:33',
                'location_id' => 21,
            ),
            157 => 
            array (
                'id' => 158,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1600.00',
                'created_at' => '2024-06-20 02:22:44',
                'updated_at' => '2024-06-20 02:22:44',
                'location_id' => 21,
            ),
            158 => 
            array (
                'id' => 159,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:22:53',
                'updated_at' => '2024-06-20 02:22:53',
                'location_id' => 21,
            ),
            159 => 
            array (
                'id' => 160,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:23:01',
                'updated_at' => '2024-06-20 02:23:01',
                'location_id' => 21,
            ),
            160 => 
            array (
                'id' => 161,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1600.00',
                'created_at' => '2024-06-20 02:23:10',
                'updated_at' => '2024-06-20 02:23:10',
                'location_id' => 21,
            ),
            161 => 
            array (
                'id' => 162,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2000.00',
                'created_at' => '2024-06-20 02:23:17',
                'updated_at' => '2024-06-20 02:23:17',
                'location_id' => 21,
            ),
            162 => 
            array (
                'id' => 163,
                'planned_application_type_id' => 1,
                'mbps' => 5,
                'price' => '499.00',
                'created_at' => '2024-06-20 02:26:40',
                'updated_at' => '2024-06-20 02:26:40',
                'location_id' => 22,
            ),
            163 => 
            array (
                'id' => 164,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '899.00',
                'created_at' => '2024-06-20 02:26:58',
                'updated_at' => '2024-06-20 02:26:58',
                'location_id' => 22,
            ),
            164 => 
            array (
                'id' => 165,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:27:08',
                'updated_at' => '2024-06-20 02:27:08',
                'location_id' => 22,
            ),
            165 => 
            array (
                'id' => 166,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:27:16',
                'updated_at' => '2024-06-20 02:27:16',
                'location_id' => 22,
            ),
            166 => 
            array (
                'id' => 167,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:27:29',
                'updated_at' => '2024-06-20 02:27:29',
                'location_id' => 22,
            ),
            167 => 
            array (
                'id' => 168,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1650.00',
                'created_at' => '2024-06-20 02:27:40',
                'updated_at' => '2024-06-20 02:27:40',
                'location_id' => 22,
            ),
            168 => 
            array (
                'id' => 169,
                'planned_application_type_id' => 2,
                'mbps' => 5,
                'price' => '600.00',
                'created_at' => '2024-06-20 02:27:51',
                'updated_at' => '2024-06-20 02:27:51',
                'location_id' => 22,
            ),
            169 => 
            array (
                'id' => 170,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:28:00',
                'updated_at' => '2024-06-20 02:28:00',
                'location_id' => 22,
            ),
            170 => 
            array (
                'id' => 171,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1100.00',
                'created_at' => '2024-06-20 02:28:16',
                'updated_at' => '2024-06-20 02:28:16',
                'location_id' => 22,
            ),
            171 => 
            array (
                'id' => 172,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:28:36',
                'updated_at' => '2024-06-20 02:28:36',
                'location_id' => 22,
            ),
            172 => 
            array (
                'id' => 173,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1650.00',
                'created_at' => '2024-06-20 02:28:50',
                'updated_at' => '2024-06-20 02:28:50',
                'location_id' => 22,
            ),
            173 => 
            array (
                'id' => 174,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1950.00',
                'created_at' => '2024-06-20 02:29:02',
                'updated_at' => '2024-06-20 02:29:02',
                'location_id' => 22,
            ),
            174 => 
            array (
                'id' => 187,
                'planned_application_type_id' => 1,
                'mbps' => 5,
                'price' => '499.00',
                'created_at' => '2024-06-20 02:32:46',
                'updated_at' => '2024-06-20 02:32:46',
                'location_id' => 24,
            ),
            175 => 
            array (
                'id' => 188,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '899.00',
                'created_at' => '2024-06-20 02:32:58',
                'updated_at' => '2024-06-20 02:32:58',
                'location_id' => 24,
            ),
            176 => 
            array (
                'id' => 189,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:33:09',
                'updated_at' => '2024-06-20 02:33:09',
                'location_id' => 24,
            ),
            177 => 
            array (
                'id' => 190,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:33:23',
                'updated_at' => '2024-06-20 02:33:23',
                'location_id' => 24,
            ),
            178 => 
            array (
                'id' => 191,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:33:33',
                'updated_at' => '2024-06-20 02:33:33',
                'location_id' => 24,
            ),
            179 => 
            array (
                'id' => 192,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1650.00',
                'created_at' => '2024-06-20 02:33:43',
                'updated_at' => '2024-06-20 02:33:43',
                'location_id' => 24,
            ),
            180 => 
            array (
                'id' => 193,
                'planned_application_type_id' => 2,
                'mbps' => 5,
                'price' => '600.00',
                'created_at' => '2024-06-20 02:33:54',
                'updated_at' => '2024-06-20 02:33:54',
                'location_id' => 24,
            ),
            181 => 
            array (
                'id' => 194,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:34:02',
                'updated_at' => '2024-06-20 02:34:02',
                'location_id' => 24,
            ),
            182 => 
            array (
                'id' => 195,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1100.00',
                'created_at' => '2024-06-20 02:34:11',
                'updated_at' => '2024-06-20 02:34:11',
                'location_id' => 24,
            ),
            183 => 
            array (
                'id' => 196,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:34:21',
                'updated_at' => '2024-06-20 02:34:21',
                'location_id' => 24,
            ),
            184 => 
            array (
                'id' => 197,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1650.00',
                'created_at' => '2024-06-20 02:34:35',
                'updated_at' => '2024-06-20 02:34:35',
                'location_id' => 24,
            ),
            185 => 
            array (
                'id' => 198,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1950.00',
                'created_at' => '2024-06-20 02:34:47',
                'updated_at' => '2024-06-20 02:34:47',
                'location_id' => 24,
            ),
            186 => 
            array (
                'id' => 199,
                'planned_application_type_id' => 1,
                'mbps' => 5,
                'price' => '499.00',
                'created_at' => '2024-06-20 02:35:16',
                'updated_at' => '2024-06-20 02:35:16',
                'location_id' => 25,
            ),
            187 => 
            array (
                'id' => 200,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '899.00',
                'created_at' => '2024-06-20 02:35:26',
                'updated_at' => '2024-06-20 02:35:26',
                'location_id' => 25,
            ),
            188 => 
            array (
                'id' => 201,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1000.00',
                'created_at' => '2024-06-20 02:35:36',
                'updated_at' => '2024-06-20 02:35:36',
                'location_id' => 25,
            ),
            189 => 
            array (
                'id' => 202,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:35:46',
                'updated_at' => '2024-06-20 02:35:46',
                'location_id' => 25,
            ),
            190 => 
            array (
                'id' => 203,
                'planned_application_type_id' => 1,
                'mbps' => 25,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:36:21',
                'updated_at' => '2024-06-20 02:36:21',
                'location_id' => 25,
            ),
            191 => 
            array (
                'id' => 204,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '1650.00',
                'created_at' => '2024-06-20 02:36:29',
                'updated_at' => '2024-06-20 02:36:29',
                'location_id' => 25,
            ),
            192 => 
            array (
                'id' => 205,
                'planned_application_type_id' => 2,
                'mbps' => 5,
                'price' => '600.00',
                'created_at' => '2024-06-20 02:36:37',
                'updated_at' => '2024-06-20 02:36:37',
                'location_id' => 25,
            ),
            193 => 
            array (
                'id' => 206,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '999.00',
                'created_at' => '2024-06-20 02:36:44',
                'updated_at' => '2024-06-20 02:36:44',
                'location_id' => 25,
            ),
            194 => 
            array (
                'id' => 207,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '1100.00',
                'created_at' => '2024-06-20 02:36:55',
                'updated_at' => '2024-06-20 02:36:55',
                'location_id' => 25,
            ),
            195 => 
            array (
                'id' => 208,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '1400.00',
                'created_at' => '2024-06-20 02:37:07',
                'updated_at' => '2024-06-20 02:37:07',
                'location_id' => 25,
            ),
            196 => 
            array (
                'id' => 209,
                'planned_application_type_id' => 2,
                'mbps' => 25,
                'price' => '1650.00',
                'created_at' => '2024-06-20 02:37:21',
                'updated_at' => '2024-06-20 02:37:21',
                'location_id' => 25,
            ),
            197 => 
            array (
                'id' => 210,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '1950.00',
                'created_at' => '2024-06-20 02:37:29',
                'updated_at' => '2024-06-20 02:37:29',
                'location_id' => 25,
            ),
            198 => 
            array (
                'id' => 211,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:53:49',
                'updated_at' => '2024-06-20 02:53:49',
                'location_id' => 26,
            ),
            199 => 
            array (
                'id' => 212,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1500.00',
                'created_at' => '2024-06-20 02:54:01',
                'updated_at' => '2024-06-20 02:54:01',
                'location_id' => 26,
            ),
            200 => 
            array (
                'id' => 213,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:54:11',
                'updated_at' => '2024-06-20 02:54:11',
                'location_id' => 26,
            ),
            201 => 
            array (
                'id' => 214,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 02:54:24',
                'updated_at' => '2024-06-20 02:54:24',
                'location_id' => 26,
            ),
            202 => 
            array (
                'id' => 215,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '2800.00',
                'created_at' => '2024-06-20 02:54:38',
                'updated_at' => '2024-06-20 02:54:38',
                'location_id' => 26,
            ),
            203 => 
            array (
                'id' => 216,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1500.00',
                'created_at' => '2024-06-20 02:54:58',
                'updated_at' => '2024-06-20 02:54:58',
                'location_id' => 26,
            ),
            204 => 
            array (
                'id' => 217,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:57:43',
                'updated_at' => '2024-06-20 02:57:43',
                'location_id' => 26,
            ),
            205 => 
            array (
                'id' => 218,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 02:57:56',
                'updated_at' => '2024-06-20 02:57:56',
                'location_id' => 26,
            ),
            206 => 
            array (
                'id' => 219,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 02:58:12',
                'updated_at' => '2024-06-20 02:58:12',
                'location_id' => 26,
            ),
            207 => 
            array (
                'id' => 220,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '3100.00',
                'created_at' => '2024-06-20 02:58:29',
                'updated_at' => '2024-06-20 02:58:29',
                'location_id' => 26,
            ),
            208 => 
            array (
                'id' => 221,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 02:59:02',
                'updated_at' => '2024-06-20 02:59:02',
                'location_id' => 27,
            ),
            209 => 
            array (
                'id' => 222,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1500.00',
                'created_at' => '2024-06-20 02:59:12',
                'updated_at' => '2024-06-20 02:59:12',
                'location_id' => 27,
            ),
            210 => 
            array (
                'id' => 223,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 02:59:30',
                'updated_at' => '2024-06-20 03:00:01',
                'location_id' => 27,
            ),
            211 => 
            array (
                'id' => 224,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 03:00:18',
                'updated_at' => '2024-06-20 03:00:18',
                'location_id' => 27,
            ),
            212 => 
            array (
                'id' => 225,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '2800.00',
                'created_at' => '2024-06-20 03:00:56',
                'updated_at' => '2024-06-20 03:00:56',
                'location_id' => 27,
            ),
            213 => 
            array (
                'id' => 226,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1500.00',
                'created_at' => '2024-06-20 03:17:46',
                'updated_at' => '2024-06-20 03:17:46',
                'location_id' => 27,
            ),
            214 => 
            array (
                'id' => 227,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1800.00',
                'created_at' => '2024-06-20 03:17:55',
                'updated_at' => '2024-06-20 03:17:55',
                'location_id' => 27,
            ),
            215 => 
            array (
                'id' => 228,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 03:18:06',
                'updated_at' => '2024-06-20 03:18:06',
                'location_id' => 27,
            ),
            216 => 
            array (
                'id' => 229,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 03:18:17',
                'updated_at' => '2024-06-20 03:18:17',
                'location_id' => 27,
            ),
            217 => 
            array (
                'id' => 230,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '3100.00',
                'created_at' => '2024-06-20 03:18:31',
                'updated_at' => '2024-06-20 03:18:31',
                'location_id' => 27,
            ),
            218 => 
            array (
                'id' => 231,
                'planned_application_type_id' => 1,
                'mbps' => 10,
                'price' => '1200.00',
                'created_at' => '2024-06-20 03:19:00',
                'updated_at' => '2024-06-20 03:19:00',
                'location_id' => 28,
            ),
            219 => 
            array (
                'id' => 232,
                'planned_application_type_id' => 1,
                'mbps' => 12,
                'price' => '1500.00',
                'created_at' => '2024-06-20 03:19:15',
                'updated_at' => '2024-06-20 03:19:15',
                'location_id' => 28,
            ),
            220 => 
            array (
                'id' => 233,
                'planned_application_type_id' => 1,
                'mbps' => 15,
                'price' => '1800.00',
                'created_at' => '2024-06-20 03:19:27',
                'updated_at' => '2024-06-20 03:19:27',
                'location_id' => 28,
            ),
            221 => 
            array (
                'id' => 234,
                'planned_application_type_id' => 1,
                'mbps' => 20,
                'price' => '2400.00',
                'created_at' => '2024-06-20 03:19:41',
                'updated_at' => '2024-06-20 03:19:41',
                'location_id' => 28,
            ),
            222 => 
            array (
                'id' => 235,
                'planned_application_type_id' => 1,
                'mbps' => 30,
                'price' => '2800.00',
                'created_at' => '2024-06-20 03:19:59',
                'updated_at' => '2024-06-20 03:19:59',
                'location_id' => 28,
            ),
            223 => 
            array (
                'id' => 236,
                'planned_application_type_id' => 2,
                'mbps' => 10,
                'price' => '1500.00',
                'created_at' => '2024-06-20 03:20:11',
                'updated_at' => '2024-06-20 03:20:11',
                'location_id' => 28,
            ),
            224 => 
            array (
                'id' => 237,
                'planned_application_type_id' => 2,
                'mbps' => 12,
                'price' => '1800.00',
                'created_at' => '2024-06-20 03:20:21',
                'updated_at' => '2024-06-20 03:20:21',
                'location_id' => 28,
            ),
            225 => 
            array (
                'id' => 238,
                'planned_application_type_id' => 2,
                'mbps' => 15,
                'price' => '2200.00',
                'created_at' => '2024-06-20 03:20:32',
                'updated_at' => '2024-06-20 03:20:32',
                'location_id' => 28,
            ),
            226 => 
            array (
                'id' => 239,
                'planned_application_type_id' => 2,
                'mbps' => 20,
                'price' => '2600.00',
                'created_at' => '2024-06-20 03:21:10',
                'updated_at' => '2024-06-20 03:21:10',
                'location_id' => 28,
            ),
            227 => 
            array (
                'id' => 240,
                'planned_application_type_id' => 2,
                'mbps' => 30,
                'price' => '3100.00',
                'created_at' => '2024-06-20 03:21:19',
                'updated_at' => '2024-06-20 03:21:19',
                'location_id' => 28,
            ),
        ));
        
        
    }
}
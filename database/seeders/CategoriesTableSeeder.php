<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'GASOLINE',
                'created_at' => '2024-08-26 11:24:04',
                'updated_at' => '2024-08-26 11:24:04',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'HOUSEHOLD',
                'created_at' => '2024-08-26 11:24:13',
                'updated_at' => '2024-08-26 11:24:13',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'PERSONAL',
                'created_at' => '2024-08-26 11:24:19',
                'updated_at' => '2024-08-26 11:24:19',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'TOWER  RENTAL/KURENTE',
                'created_at' => '2024-08-26 11:24:22',
                'updated_at' => '2024-08-26 11:24:22',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'SUPPLIES',
                'created_at' => '2024-08-26 11:24:26',
                'updated_at' => '2024-08-26 11:24:26',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'NET SALARY',
                'created_at' => '2024-08-26 11:24:31',
                'updated_at' => '2024-08-26 11:24:31',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
            'name' => 'CASH ADVANCE (SALARY)',
                'created_at' => '2024-08-26 11:24:36',
                'updated_at' => '2024-08-26 11:24:36',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'GLOBE PLAN BILL',
                'created_at' => '2024-08-26 11:24:38',
                'updated_at' => '2024-08-26 11:24:38',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'ALLOWANCE/SCHOOL TUITION/BHOUSE',
                'created_at' => '2024-08-26 08:25:22',
                'updated_at' => '2024-08-26 08:25:22',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'MOTOR/SERVICE MAINTENANCE',
                'created_at' => '2024-08-26 08:25:25',
                'updated_at' => '2024-08-26 08:25:25',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'DEPOSIT TO NAVLINK BANK ACCOUNT',
                'created_at' => '2024-08-26 08:25:28',
                'updated_at' => '2024-08-26 08:25:28',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'INSURANCE/LOAN/CREDIT/HOUSING LOAN',
                'created_at' => '2024-08-26 08:25:30',
                'updated_at' => '2024-08-26 08:25:30',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'REMIT KUYA',
                'created_at' => '2024-08-26 08:25:34',
                'updated_at' => '2024-08-26 08:25:34',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'REMIT ATE',
                'created_at' => '2024-08-26 08:25:38',
                'updated_at' => '2024-08-26 08:25:38',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'GAS STATION EXPENSES',
                'created_at' => '2024-08-26 08:25:41',
                'updated_at' => '2024-08-26 08:25:41',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'PAYAG TINABILAN EXPENSES',
                'created_at' => '2024-08-26 08:25:44',
                'updated_at' => '2024-08-26 08:25:44',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'PAYAG TAGBUBUNGA EXPENSES',
                'created_at' => '2024-08-26 08:25:46',
                'updated_at' => '2024-08-26 08:25:46',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'REFUND',
                'created_at' => '2024-08-26 08:25:56',
                'updated_at' => '2024-08-26 08:25:56',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'ELECTRICITY BILL',
                'created_at' => '2024-08-26 08:25:58',
                'updated_at' => '2024-08-26 08:25:58',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'WATER BILL',
                'created_at' => '2024-08-26 08:26:08',
                'updated_at' => '2024-08-26 08:26:08',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'HOUSE/OFFICE RENTAL',
                'created_at' => '2024-08-26 08:26:10',
                'updated_at' => '2024-08-26 08:26:10',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'BAJAJ PAYMENT',
                'created_at' => '2024-08-26 08:26:28',
                'updated_at' => '2024-08-26 08:26:28',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'EMPLOYEE INCENTIVES',
                'created_at' => '2024-08-26 08:26:32',
                'updated_at' => '2024-08-26 08:26:32',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'LABOR',
                'created_at' => '2024-08-26 08:26:34',
                'updated_at' => '2024-08-26 08:26:34',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
            'name' => 'PROFESSIONAL FEE (TRISTAN)',
                'created_at' => '2024-08-26 08:26:37',
                'updated_at' => '2024-08-26 08:26:37',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
            'name' => 'PROFESSIONAL FEE (TAOKI)',
                'created_at' => '2024-08-26 08:26:44',
                'updated_at' => '2024-08-26 08:26:44',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
            'name' => 'EMPLOYEE BENEFITS (SSS/INSURANCE)',
                'created_at' => '2024-08-26 08:26:46',
                'updated_at' => '2024-08-26 08:26:46',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'PERSONAL LOAN',
                'created_at' => '2024-08-26 08:26:48',
                'updated_at' => '2024-08-26 08:26:48',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'SALARY - NARVYL',
                'created_at' => '2024-08-26 08:26:51',
                'updated_at' => '2024-08-26 08:26:51',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'CONSULTANCY FEE - ALVIN',
                'created_at' => '2024-08-26 08:26:53',
                'updated_at' => '2024-08-26 08:26:53',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'COMMISSION',
                'created_at' => '2024-08-26 08:27:01',
                'updated_at' => '2024-08-26 08:27:01',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'BIR',
                'created_at' => '2024-08-26 08:27:11',
                'updated_at' => '2024-08-26 08:27:11',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
            'name' => 'REFUND (HYPE-PRO)',
                'created_at' => '2024-08-26 08:27:16',
                'updated_at' => '2024-08-26 08:27:16',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'CHRISTMAS PARTY EXPENSES',
                'created_at' => '2024-08-26 08:27:21',
                'updated_at' => '2024-08-26 08:27:21',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'BONUS',
                'created_at' => '2024-08-26 08:27:24',
                'updated_at' => '2024-08-26 08:27:24',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'OTHERS',
                'created_at' => '2024-08-26 08:27:27',
                'updated_at' => '2024-08-26 08:27:27',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
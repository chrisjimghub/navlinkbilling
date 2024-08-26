<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExpenseCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('expense_categories')->delete();
        
        \DB::table('expense_categories')->insert(array (
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
                'created_at' => '2024-08-26 11:24:40',
                'updated_at' => '2024-08-26 11:24:40',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'MOTOR/SERVICE MAINTENANCE',
                'created_at' => '2024-08-26 11:24:43',
                'updated_at' => '2024-08-26 11:24:43',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'DEPOSIT TO NAVLINK BANK ACCOUNT',
                'created_at' => '2024-08-26 11:24:44',
                'updated_at' => '2024-08-26 11:24:44',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'INSURANCE/LOAN/CREDIT/HOUSING LOAN',
                'created_at' => '2024-08-26 11:24:48',
                'updated_at' => '2024-08-26 11:24:48',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'REMIT KUYA',
                'created_at' => '2024-08-26 11:24:50',
                'updated_at' => '2024-08-26 11:24:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'REMIT ATE',
                'created_at' => '2024-08-26 11:24:52',
                'updated_at' => '2024-08-26 11:24:52',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'GAS STATION EXPENSES',
                'created_at' => '2024-08-26 11:24:56',
                'updated_at' => '2024-08-26 11:24:56',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'PAYAG TINABILAN EXPENSES',
                'created_at' => '2024-08-26 11:24:58',
                'updated_at' => '2024-08-26 11:24:58',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'PAYAG TAGBUBUNGA EXPENSES',
                'created_at' => '2024-08-26 11:25:01',
                'updated_at' => '2024-08-26 11:25:01',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'REFUND',
                'created_at' => '2024-08-26 11:25:07',
                'updated_at' => '2024-08-26 11:25:07',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'ELECTRICITY BILL',
                'created_at' => '2024-08-26 11:25:10',
                'updated_at' => '2024-08-26 11:25:10',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'WATER BILL',
                'created_at' => '2024-08-26 11:25:16',
                'updated_at' => '2024-08-26 11:25:16',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'HOUSE/OFFICE RENTAL',
                'created_at' => '2024-08-26 11:25:21',
                'updated_at' => '2024-08-26 11:25:21',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'OFFICE FURNITURES/APPLIANCES',
                'created_at' => '2024-08-26 11:25:24',
                'updated_at' => '2024-08-26 11:25:24',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'BAJAJ PAYMENT',
                'created_at' => '2024-08-26 11:25:30',
                'updated_at' => '2024-08-26 11:25:30',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'EMPLOYEE INCENTIVES',
                'created_at' => '2024-08-26 11:25:33',
                'updated_at' => '2024-08-26 11:25:33',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'LABOR',
                'created_at' => '2024-08-26 11:25:36',
                'updated_at' => '2024-08-26 11:25:36',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
            'name' => 'PROFESSIONAL FEE (TRISTAN)',
                'created_at' => '2024-08-26 11:25:38',
                'updated_at' => '2024-08-26 11:25:38',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
            'name' => 'PROFESSIONAL FEE (TAOKI)',
                'created_at' => '2024-08-26 11:25:41',
                'updated_at' => '2024-08-26 11:25:41',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
            'name' => 'EMPLOYEE BENEFITS (SSS/INSURANCE)',
                'created_at' => '2024-08-26 11:25:44',
                'updated_at' => '2024-08-26 11:25:44',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'PERSONAL LOAN',
                'created_at' => '2024-08-26 11:25:47',
                'updated_at' => '2024-08-26 11:25:47',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'SALARY - NARVYL',
                'created_at' => '2024-08-26 11:25:48',
                'updated_at' => '2024-08-26 11:25:48',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'CONSULTANCY FEE - ALVIN',
                'created_at' => '2024-08-26 11:25:51',
                'updated_at' => '2024-08-26 11:25:51',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'COMMISSION',
                'created_at' => '2024-08-26 11:25:54',
                'updated_at' => '2024-08-26 11:25:54',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'BIR',
                'created_at' => '2024-08-26 11:25:55',
                'updated_at' => '2024-08-26 11:25:55',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
            'name' => 'REFUND (HYPE-PRO)',
                'created_at' => '2024-08-26 11:26:05',
                'updated_at' => '2024-08-26 11:26:05',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'CHRISTMAS PARTY EXPENSES',
                'created_at' => '2024-08-26 11:26:08',
                'updated_at' => '2024-08-26 11:26:08',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'BONUS',
                'created_at' => '2024-08-26 11:26:10',
                'updated_at' => '2024-08-26 11:26:10',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'OTHERS',
                'created_at' => '2024-08-26 11:26:13',
                'updated_at' => '2024-08-26 11:26:13',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
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
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:04',
                'updated_at' => '2024-08-26 11:24:04',
=======
                'created_at' => '2024-08-26 08:24:30',
                'updated_at' => '2024-08-26 08:24:30',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'HOUSEHOLD',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:13',
                'updated_at' => '2024-08-26 11:24:13',
=======
                'created_at' => '2024-08-26 08:24:42',
                'updated_at' => '2024-08-26 08:24:42',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'PERSONAL',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:19',
                'updated_at' => '2024-08-26 11:24:19',
=======
                'created_at' => '2024-08-26 08:24:52',
                'updated_at' => '2024-08-26 08:24:52',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'TOWER  RENTAL/KURENTE',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:22',
                'updated_at' => '2024-08-26 11:24:22',
=======
                'created_at' => '2024-08-26 08:24:57',
                'updated_at' => '2024-08-26 08:24:57',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'SUPPLIES',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:26',
                'updated_at' => '2024-08-26 11:24:26',
=======
                'created_at' => '2024-08-26 08:25:05',
                'updated_at' => '2024-08-26 08:25:05',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'NET SALARY',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:31',
                'updated_at' => '2024-08-26 11:24:31',
=======
                'created_at' => '2024-08-26 08:25:10',
                'updated_at' => '2024-08-26 08:25:10',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
            'name' => 'CASH ADVANCE (SALARY)',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:36',
                'updated_at' => '2024-08-26 11:24:36',
=======
                'created_at' => '2024-08-26 08:25:15',
                'updated_at' => '2024-08-26 08:25:15',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'GLOBE PLAN BILL',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:38',
                'updated_at' => '2024-08-26 11:24:38',
=======
                'created_at' => '2024-08-26 08:25:19',
                'updated_at' => '2024-08-26 08:25:19',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'ALLOWANCE/SCHOOL TUITION/BHOUSE',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:40',
                'updated_at' => '2024-08-26 11:24:40',
=======
                'created_at' => '2024-08-26 08:25:22',
                'updated_at' => '2024-08-26 08:25:22',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'MOTOR/SERVICE MAINTENANCE',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:43',
                'updated_at' => '2024-08-26 11:24:43',
=======
                'created_at' => '2024-08-26 08:25:25',
                'updated_at' => '2024-08-26 08:25:25',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'DEPOSIT TO NAVLINK BANK ACCOUNT',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:44',
                'updated_at' => '2024-08-26 11:24:44',
=======
                'created_at' => '2024-08-26 08:25:28',
                'updated_at' => '2024-08-26 08:25:28',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'INSURANCE/LOAN/CREDIT/HOUSING LOAN',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:48',
                'updated_at' => '2024-08-26 11:24:48',
=======
                'created_at' => '2024-08-26 08:25:30',
                'updated_at' => '2024-08-26 08:25:30',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'REMIT KUYA',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:50',
                'updated_at' => '2024-08-26 11:24:50',
=======
                'created_at' => '2024-08-26 08:25:34',
                'updated_at' => '2024-08-26 08:25:34',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'REMIT ATE',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:52',
                'updated_at' => '2024-08-26 11:24:52',
=======
                'created_at' => '2024-08-26 08:25:38',
                'updated_at' => '2024-08-26 08:25:38',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'GAS STATION EXPENSES',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:56',
                'updated_at' => '2024-08-26 11:24:56',
=======
                'created_at' => '2024-08-26 08:25:41',
                'updated_at' => '2024-08-26 08:25:41',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'PAYAG TINABILAN EXPENSES',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:24:58',
                'updated_at' => '2024-08-26 11:24:58',
=======
                'created_at' => '2024-08-26 08:25:44',
                'updated_at' => '2024-08-26 08:25:44',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'PAYAG TAGBUBUNGA EXPENSES',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:25:01',
                'updated_at' => '2024-08-26 11:25:01',
=======
                'created_at' => '2024-08-26 08:25:46',
                'updated_at' => '2024-08-26 08:25:46',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'REFUND',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:25:07',
                'updated_at' => '2024-08-26 11:25:07',
=======
                'created_at' => '2024-08-26 08:25:56',
                'updated_at' => '2024-08-26 08:25:56',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'ELECTRICITY BILL',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:25:10',
                'updated_at' => '2024-08-26 11:25:10',
=======
                'created_at' => '2024-08-26 08:25:58',
                'updated_at' => '2024-08-26 08:25:58',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'WATER BILL',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:25:16',
                'updated_at' => '2024-08-26 11:25:16',
=======
                'created_at' => '2024-08-26 08:26:08',
                'updated_at' => '2024-08-26 08:26:08',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'HOUSE/OFFICE RENTAL',
<<<<<<< HEAD
                'created_at' => '2024-08-26 11:25:21',
                'updated_at' => '2024-08-26 11:25:21',
=======
                'created_at' => '2024-08-26 08:26:10',
                'updated_at' => '2024-08-26 08:26:10',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
<<<<<<< HEAD
                'name' => 'OFFICE FURNITURES/APPLIANCES',
                'created_at' => '2024-08-26 11:25:24',
                'updated_at' => '2024-08-26 11:25:24',
=======
                'name' => 'BAJAJ PAYMENT',
                'created_at' => '2024-08-26 08:26:28',
                'updated_at' => '2024-08-26 08:26:28',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
<<<<<<< HEAD
                'name' => 'BAJAJ PAYMENT',
                'created_at' => '2024-08-26 11:25:30',
                'updated_at' => '2024-08-26 11:25:30',
=======
                'name' => 'EMPLOYEE INCENTIVES',
                'created_at' => '2024-08-26 08:26:32',
                'updated_at' => '2024-08-26 08:26:32',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
<<<<<<< HEAD
                'name' => 'EMPLOYEE INCENTIVES',
                'created_at' => '2024-08-26 11:25:33',
                'updated_at' => '2024-08-26 11:25:33',
=======
                'name' => 'LABOR',
                'created_at' => '2024-08-26 08:26:34',
                'updated_at' => '2024-08-26 08:26:34',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
<<<<<<< HEAD
                'name' => 'LABOR',
                'created_at' => '2024-08-26 11:25:36',
                'updated_at' => '2024-08-26 11:25:36',
=======
            'name' => 'PROFESSIONAL FEE (TRISTAN)',
                'created_at' => '2024-08-26 08:26:37',
                'updated_at' => '2024-08-26 08:26:37',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
<<<<<<< HEAD
            'name' => 'PROFESSIONAL FEE (TRISTAN)',
                'created_at' => '2024-08-26 11:25:38',
                'updated_at' => '2024-08-26 11:25:38',
=======
            'name' => 'PROFESSIONAL FEE (TAOKI)',
                'created_at' => '2024-08-26 08:26:44',
                'updated_at' => '2024-08-26 08:26:44',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
<<<<<<< HEAD
            'name' => 'PROFESSIONAL FEE (TAOKI)',
                'created_at' => '2024-08-26 11:25:41',
                'updated_at' => '2024-08-26 11:25:41',
=======
            'name' => 'EMPLOYEE BENEFITS (SSS/INSURANCE)',
                'created_at' => '2024-08-26 08:26:46',
                'updated_at' => '2024-08-26 08:26:46',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
<<<<<<< HEAD
            'name' => 'EMPLOYEE BENEFITS (SSS/INSURANCE)',
                'created_at' => '2024-08-26 11:25:44',
                'updated_at' => '2024-08-26 11:25:44',
=======
                'name' => 'PERSONAL LOAN',
                'created_at' => '2024-08-26 08:26:48',
                'updated_at' => '2024-08-26 08:26:48',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
<<<<<<< HEAD
                'name' => 'PERSONAL LOAN',
                'created_at' => '2024-08-26 11:25:47',
                'updated_at' => '2024-08-26 11:25:47',
=======
                'name' => 'SALARY - NARVYL',
                'created_at' => '2024-08-26 08:26:51',
                'updated_at' => '2024-08-26 08:26:51',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
<<<<<<< HEAD
                'name' => 'SALARY - NARVYL',
                'created_at' => '2024-08-26 11:25:48',
                'updated_at' => '2024-08-26 11:25:48',
=======
                'name' => 'CONSULTANCY FEE - ALVIN',
                'created_at' => '2024-08-26 08:26:53',
                'updated_at' => '2024-08-26 08:26:53',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
<<<<<<< HEAD
                'name' => 'CONSULTANCY FEE - ALVIN',
                'created_at' => '2024-08-26 11:25:51',
                'updated_at' => '2024-08-26 11:25:51',
=======
                'name' => 'COMMISSION',
                'created_at' => '2024-08-26 08:27:01',
                'updated_at' => '2024-08-26 08:27:01',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
<<<<<<< HEAD
                'name' => 'COMMISSION',
                'created_at' => '2024-08-26 11:25:54',
                'updated_at' => '2024-08-26 11:25:54',
=======
                'name' => 'BIR',
                'created_at' => '2024-08-26 08:27:11',
                'updated_at' => '2024-08-26 08:27:11',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
<<<<<<< HEAD
                'name' => 'BIR',
                'created_at' => '2024-08-26 11:25:55',
                'updated_at' => '2024-08-26 11:25:55',
=======
            'name' => 'REFUND (HYPE-PRO)',
                'created_at' => '2024-08-26 08:27:16',
                'updated_at' => '2024-08-26 08:27:16',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
<<<<<<< HEAD
            'name' => 'REFUND (HYPE-PRO)',
                'created_at' => '2024-08-26 11:26:05',
                'updated_at' => '2024-08-26 11:26:05',
=======
                'name' => 'CHRISTMAS PARTY EXPENSES',
                'created_at' => '2024-08-26 08:27:21',
                'updated_at' => '2024-08-26 08:27:21',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
<<<<<<< HEAD
                'name' => 'CHRISTMAS PARTY EXPENSES',
                'created_at' => '2024-08-26 11:26:08',
                'updated_at' => '2024-08-26 11:26:08',
=======
                'name' => 'BONUS',
                'created_at' => '2024-08-26 08:27:24',
                'updated_at' => '2024-08-26 08:27:24',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
<<<<<<< HEAD
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
=======
                'name' => 'OTHERS',
                'created_at' => '2024-08-26 08:27:27',
                'updated_at' => '2024-08-26 08:27:27',
>>>>>>> 3b425be7443f4e44b40723a809ff7b093f92e0b6
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
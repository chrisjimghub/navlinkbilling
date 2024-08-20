<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BillingGroupingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('billing_groupings')->delete();
        
        \DB::table('billing_groupings')->insert(array (
            0 => 
            array (
                'id' => 1,
            'name' => 'FIBER (EVERY 30TH OF THE MONTH)',
                'day_start' => 30,
                'day_end' => 30,
                'day_cut_off' => 5,
                'bill_generate_days_before_end_of_billing_period' => 5,
                'bill_notification_days_after_the_bill_created' => 5,
                'bill_cut_off_notification_days_before_cut_off_date' => 0,
                'billing_cycle_id' => 1,
                'auto_generate_bill' => 1,
                'auto_send_bill_notification' => 1,
                'auto_send_cut_off_notification' => 1,
                'created_at' => '2024-08-08 19:37:13',
                'updated_at' => '2024-08-20 15:18:55',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
            'name' => 'P2P (EVERY 20TH OF THE MONTH)',
                'day_start' => 20,
                'day_end' => 20,
                'day_cut_off' => 5,
                'bill_generate_days_before_end_of_billing_period' => 5,
                'bill_notification_days_after_the_bill_created' => 5,
                'bill_cut_off_notification_days_before_cut_off_date' => 0,
                'billing_cycle_id' => 1,
                'auto_generate_bill' => 1,
                'auto_send_bill_notification' => 1,
                'auto_send_cut_off_notification' => 1,
                'created_at' => '2024-08-08 19:38:51',
                'updated_at' => '2024-08-20 15:18:44',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
            'name' => 'FIBER (EVERY 15TH OF THE MONTH)',
                'day_start' => 15,
                'day_end' => 15,
                'day_cut_off' => 5,
                'bill_generate_days_before_end_of_billing_period' => 5,
                'bill_notification_days_after_the_bill_created' => 5,
                'bill_cut_off_notification_days_before_cut_off_date' => 0,
                'billing_cycle_id' => 1,
                'auto_generate_bill' => 1,
                'auto_send_bill_notification' => 1,
                'auto_send_cut_off_notification' => 1,
                'created_at' => '2024-08-20 15:19:18',
                'updated_at' => '2024-08-20 15:19:38',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
            'name' => 'P2P (EVERY 15TH OF THE MONTH)',
                'day_start' => 15,
                'day_end' => 15,
                'day_cut_off' => 5,
                'bill_generate_days_before_end_of_billing_period' => 5,
                'bill_notification_days_after_the_bill_created' => 5,
                'bill_cut_off_notification_days_before_cut_off_date' => 0,
                'billing_cycle_id' => 1,
                'auto_generate_bill' => 1,
                'auto_send_bill_notification' => 1,
                'auto_send_cut_off_notification' => 1,
                'created_at' => '2024-08-20 15:20:08',
                'updated_at' => '2024-08-20 15:20:08',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
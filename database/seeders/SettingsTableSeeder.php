<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'enable_auto_bill',
                'name' => 'Auto Generate Bill',
                'description' => 'If enabled this will generate bill automatically depending on the day supplied.',
                'value' => '1',
                'field' => '{"name":"value","label":"Enable","type":"checkbox"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-16 10:06:29',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'fiber_day_start',
                'name' => 'Fiber monthly day start.',
            'description' => ' For P2P and Fiber Day Start/End, if you select the 31st day and the billing month does not have a 31st day, or if it\'s February (28 days) or February in a leap year (29 days), it will automatically use the last day of the month.',
                'value' => '31',
                'field' => '{
"name": "value",
"label": "Day",
"type": "number",
"attributes": {
"step": "1",
"required": "required",
"min": "1",
"max": "31"
}
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-16 09:19:12',
            ),
            2 => 
            array (
                'id' => 5,
                'key' => 'fiber_day_end',
                'name' => 'Fiber monthly day end.',
            'description' => ' For P2P and Fiber Day Start/End, if you select the 31st day and the billing month does not have a 31st day, or if it\'s February (28 days) or February in a leap year (29 days), it will automatically use the last day of the month.',
                'value' => '31',
                'field' => '{
"name": "value",
"label": "Day",
"type": "number",
"attributes": {
"step": "1",
"min": "1",
"max": "31"
},
"hint": "If you leave it blank; it defaults to the end of the month."
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-15 12:33:45',
            ),
            3 => 
            array (
                'id' => 6,
                'key' => 'p2p_day_start',
                'name' => 'P2P monthly day start.',
            'description' => ' For P2P and Fiber Day Start/End, if you select the 31st day and the billing month does not have a 31st day, or if it\'s February (28 days) or February in a leap year (29 days), it will automatically use the last day of the month.',
                'value' => '20',
                'field' => '{
"name": "value",
"label": "Day",
"type": "number",
"attributes": {
"step": "1",
"required": "required",
"min": "1",
"max": "31"
}
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-15 19:21:34',
            ),
            4 => 
            array (
                'id' => 7,
                'key' => 'p2p_day_end',
                'name' => 'P2P monthly day end.',
            'description' => ' For P2P and Fiber Day Start/End, if you select the 31st day and the billing month does not have a 31st day, or if it\'s February (28 days) or February in a leap year (29 days), it will automatically use the last day of the month.',
                'value' => '20',
                'field' => '{
"name": "value",
"label": "Day",
"type": "number",
"attributes": {
"step": "1",
"required": "required",
"min": "1",
"max": "31"
}
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-15 19:21:34',
            ),
            5 => 
            array (
                'id' => 10,
                'key' => 'days_before_generate_bill',
                'name' => 'Generate bill days before the date end.',
                'description' => 'How many days before the end of the billing period should the bill be generated? *',
                'value' => '5',
                'field' => '{
"name": "value",
"label": "When should the bill be auto-generated?",
"type": "select_from_array",
"options": {
"0": "Immediately at the end of the billing period.",
"5": "5 days before the end of billing period."
},
"allows_null": false
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-15 12:34:49',
            ),
            6 => 
            array (
                'id' => 17,
                'key' => 'fiber_billing_period',
                'name' => 'Fiber billing period',
                'description' => '',
                'value' => 'previous_month_current_month',
                'field' => '{
"name": "value",
"label": "Billing Period",
"type": "select_from_array",
"options": {
"previous_month_current_month": "Previous Month - Current Month",
"current_month_current_month": "Current Month - Current Month",
"current_month_next_month": "Current Month - Next Month"
},
"allows_null": false
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-16 09:18:02',
            ),
            7 => 
            array (
                'id' => 18,
                'key' => 'p2p_billing_period',
                'name' => 'P2P billing period',
                'description' => '',
                'value' => 'previous_month_current_month',
                'field' => '{
"name": "value",
"label": "Billing Period",
"type": "select_from_array",
"options": {
"previous_month_current_month": "Previous Month - Current Month",
"current_month_current_month": "Current Month - Current Month",
"current_month_next_month": "Current Month - Next Month"
},
"allows_null": false
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-15 19:13:29',
            ),
            8 => 
            array (
                'id' => 19,
                'key' => 'days_before_send_bill_notification',
                'name' => 'Days before send bill notification.',
                'description' => 'How many days after the bill is created should we send notifications to customers?',
                'value' => '5',
                'field' => '{
"name": "value",
"label": "How many days after the bill is created should we send notifications to customers?",
"type": "select_from_array",
"options": {
"0": "Immediately after the bill is created.",
"5": "5 days after the bill is created."
},
"allows_null": false
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-16 10:09:56',
            ),
            9 => 
            array (
                'id' => 20,
                'key' => 'days_before_send_cut_off_notification',
                'name' => 'Days before send cut-off notification.',
                'description' => 'How many days before the cut-off date should we send cut-off notifications to customers?',
                'value' => '0',
                'field' => '{
"name": "value",
"label": "How many days before the cut-off date should we send cut-off notifications to customers?",
"type": "select_from_array",
"options": {
"0": "Immediately on the cut-off date.",
"1": "1 day before the cut-off date."
},
"allows_null": false
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-15 12:34:49',
            ),
            10 => 
            array (
                'id' => 21,
                'key' => 'fiber_day_cut_off',
                'name' => 'Fiber monthly cut off day.',
                'description' => 'How many days after the end of the billing period is the cut-off date?






',
                'value' => '5',
                'field' => '{
"name": "value",
"label": "How many days after the end of the billing period is the cut-off date?",
"type": "select_from_array",
"options": {
"0": "Immediately at the end of the billing period.",
"5": "5 days after the end of billing period."
},
"allows_null": false
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-16 09:18:02',
            ),
            11 => 
            array (
                'id' => 22,
                'key' => 'p2p_day_cut_off',
                'name' => 'P2P monthly cut off day.',
                'description' => 'How many days after the end of the billing period is the cut-off date?






',
                'value' => '5',
                'field' => '{
"name": "value",
"label": "How many days after the end of the billing period is the cut-off date?",
"type": "select_from_array",
"options": {
"0": "Immediately at the end of the billing period.",
"5": "5 days after the end of billing period."
},
"allows_null": false
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-16 09:07:11',
            ),
            12 => 
            array (
                'id' => 23,
                'key' => 'enable_auto_notification',
                'name' => 'Auto Send Notifications',
                'description' => 'If enabled this will send bill notifications to customers.',
                'value' => '1',
                'field' => '{"name":"value","label":"Enable","type":"checkbox"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-16 10:09:56',
            ),
        ));
        
        
    }
}
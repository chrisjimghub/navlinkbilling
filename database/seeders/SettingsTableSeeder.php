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
                'key' => 'auto_generate_bill',
                'name' => 'Auto generate bill',
                'description' => 'If enabled this will generate bill automatically depending on the dates supplied.',
                'value' => '1',
                'field' => '{"name":"value","label":"Enable","type":"checkbox"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-14 14:25:36',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'fiber_date_start',
                'name' => 'Fiber monthly date start.',
                'description' => 'Day of the month.',
                'value' => '1',
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
                'updated_at' => '2024-07-14 13:57:55',
            ),
            2 => 
            array (
                'id' => 5,
                'key' => 'fiber_date_end',
                'name' => 'Fiber monthly date end.',
                'description' => 'Day of the month.',
                'value' => NULL,
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
                'updated_at' => '2024-07-14 14:04:52',
            ),
            3 => 
            array (
                'id' => 6,
                'key' => 'p2p_date_start',
                'name' => 'P2P monthly date start.',
                'description' => 'Day of the prev month.',
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
                'updated_at' => '2024-07-14 14:12:52',
            ),
            4 => 
            array (
                'id' => 7,
                'key' => 'p2p_date_end',
                'name' => 'P2P monthly date end.',
                'description' => 'Day of the month.',
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
                'updated_at' => '2024-07-14 14:12:52',
            ),
            5 => 
            array (
                'id' => 8,
                'key' => 'days_cut_off',
                'name' => 'Cut off days after date end.',
                'description' => 'Number of days after the end of billing date to cut off',
                'value' => '5',
                'field' => '{
"name": "value",
"label": "Day",
"type": "number",
"attributes": {
"step": "1",
"required": "required",
"min": "1"
}
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-14 14:12:52',
            ),
            6 => 
            array (
                'id' => 10,
                'key' => 'days_generate_bill',
                'name' => 'Generate bill days before the start date.',
                'description' => 'Number of days before bill is auto generated before date start.',
                'value' => '5',
                'field' => '{
"name": "value",
"label": "Day",
"type": "number",
"attributes": {
"step": "1",
"required": "required",
"min": "1"
}
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-14 14:12:52',
            ),
        ));
        
        
    }
}
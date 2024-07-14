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
                'key' => 'generate_bill',
                'name' => 'Generate Bill Settings',
                'description' => 'Automated background process generation of bill once date is arrived.',
            'value' => '[{"description":"Enable auto-generate bill (0 to disable)","value":"1"},{"description":"FIBER billing period start date and end date (x = last day of the month)","value":"1-x"},{"description":"P2P billing period date start and date end","value":"20-20"},{"description":"This field represents the cutoff days, accepting an integer value that will be added to the billing\'s end date.","value":"5"}]',
                'field' => '{
"name": "value",
"label": "Settings",
"type": "repeat",
"init_rows": 4,
"max_rows": 4,
"min_rows": 4,
"fields": [
{
"name": "description",
"type": "text",
"label": "Description",
"wrapper": {
"class": "form-group col-sm-12"
},
"attributes": {
"readonly": "readonly"
}
},
{
"name": "value",
"type": "text",
"label": "Value",
"wrapper": {
"class": "form-group col-sm-2"
},
"attributes": {
"required": "required"
}
}
]
}
',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-14 10:10:05',
            ),
        ));
        
        
    }
}
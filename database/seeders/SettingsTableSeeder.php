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
                'key' => 'invoices.seller.attributes.custom_fields.company',
                'name' => 'Invoice Company',
                'description' => '',
                'value' => 'NavLink Technology',
                'field' => '{"name":"value","label":"Invoice Company","type":"text"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-22 09:22:07',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'invoices.seller.attributes.custom_fields.address',
                'name' => 'Invoice Address',
                'description' => '',
                'value' => 'Brgy. San Isidro Palompon Leyte',
                'field' => '{"name":"value","label":"Invoice Company Address","type":"text"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-22 09:22:07',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'invoices.seller.attributes.custom_fields.phone',
                'name' => 'Invoice Company Phone',
                'description' => '',
                'value' => '09958476256 / 09093639756',
                'field' => '{"name":"value","label":"Invoice Company Phone","type":"text"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-22 09:22:07',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'raisepon_username',
                'name' => 'raisepon_username',
                'description' => '',
                'value' => 'admin',
                'field' => '{"name":"value","label":"Raisepon Username","type":"text"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-27 17:06:52',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'raisepon_password',
                'name' => 'raisepon_password',
                'description' => '',
                'value' => 'admin123',
                'field' => '{"name":"value","label":"Raisepon Password","type":"text"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-27 17:06:52',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'raisepon_url',
                'name' => 'raisepon_url',
                'description' => '',
                'value' => 'http://raisepon2.test/api/',
                'field' => '{"name":"value","label":"Raisepon Url","type":"text"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2024-07-27 17:06:52',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'paymongo_service_charge',
                'name' => 'Paymongo Service Charge',
                'description' => '',
                'value' => '2.5',
                'field' => '{
"name": "value",
"label": "Value must be in %",
"type": "number",
"attributes": {
"step": "any",
"required": "required"
}
}',
                'active' => 1,
                'created_at' => '2024-08-05 10:00:32',
                'updated_at' => '2024-08-05 10:12:06',
            ),
        ));
        
        
    }
}
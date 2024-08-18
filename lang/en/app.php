<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Application Language Lines
    |--------------------------------------------------------------------------
    */

 


    // Accounts
    'account'                        => 'Account',
    'account_field_validation'       => 'The account field is required.',
    'account_name'                   => 'Account Name (Customer)',
    'account_names'                  => 'Account Names (Customers)',
    'account_google_map_coordinates' => 'Coordinates',
    'account_installed_date'         => 'Installed Date',
    'account_installed_address'      => 'Installed Address',
    'account_notes'                  => 'Notes',
    'account_coordinates'            => 'Coordinates',

    // Account credits
    'account_credit_latest_udpated' => 'Last Updated',

    // Account Status
    'account_status' => 'Account Status',
    'account_statuses' => 'Account Statuses',
    
    // Billing 
    'billing_date_start'     => 'Date Start',
    'billing_date_end'       => 'Date End',
    'billing_date_cut_off'   => 'Date Cut Off',
    'billing_particulars'    => 'Particulars',
    'billing_description'    => 'Description',
    'billing_period'         => 'Billing Period',
    'billing_total'          => 'Total Balance',
    'billing_payment_method' => 'Payment Method',
    'billing_date_change'    => 'Date Change',
    'billing_deduction'      => 'Deduction',
    'billing_amount'         => 'Amount',

    // Billing Validation
    'billing_unique_account_billing_type_installation' => 'The selected account already has a billing Installation Fee.',
    'billing_unique_account_billing_type_monthly'      => 'The selected account already has unpaid billing Monthly Fee.',
    'billing_account_must_have_installed_date'         => 'The selected account must have installed date in his account record to proceed.',
    'billing_particulars_description_required'         => 'The particulars description is required.',
    'billing_particulars_amount_required'              => 'The particulars amount is required.',

    // Billing Type
    'billing_type' => 'Billing Type',
    'billing_type_id_hint' => '<span class="text-success">It does not require particulars; it will automatically populate based on account records, and you can edit it if you want to add more details.</span>',

    // Contract Period
    'contract_period' => 'Contract Period',
    'contract_periods' => 'Contract Periods',
    
    // Customers
    'customer'                   => 'Customer',
    'customer_name'              => 'Customer Name',
    'customer_signature'         => 'Please sign here',
    'customer_date_birth'        => 'Date of Birth',
    'customer_contact'           => 'Contact Number',
    'customer_street'            => 'Block Street',
    'customer_barangay'          => 'Barangay',
    'customer_city_municipality' => 'City or Municipality',
    'customer_social'            => 'Social Media',

    // Customer Validation
    'customer_select_field' => 'The customer field is required.',

    'installation_fee' => 'Installation Fee',

    // Menus
    'menu_icon_hint' => '<a href="https://icons8.com/line-awesome" target="_blank">https://icons8.com/line-awesome</a>',

    // OTC 
    'otc' => 'One-Time Charge',
    'otcs' => 'One-Time Charges',
    'otc_name' => 'Name',
    'otc_amount' => 'Amount',
    
    // Planned Application
    'planned_application' => 'Planned Application',
    'planned_applications' => 'Planned Applications',
    'planned_application_mbps' => 'Mbps',
    'planned_application_price' => 'Price',
    'planned_application_details' => 'Planned Application',
    
    // Planned Application Type
    'planned_application_type' => 'Planned Applicaton Type',
    'planned_applications_types' => 'Planned Applicaton Types',
    

    // Subscription
    'subscription' => 'Subscription',
    'subscriptions' => 'Subscriptions',

    // Location
    'location' => 'Location',

    // Misc.
    'download_excel' => 'Download Excel',
    'row_num'        => '#',
    'status'         => 'Status',
    'type'           => 'Type',
    'created'        => 'Created At',
    'email'          => 'Email',

    // Dashboard
    'dashboard' => [
        'priority_num'   => 'Priority #',
        'account_name'   => 'Account Name',
        'planned_app'    => 'Planned Application',
        'sub'            => 'Subscription',
        'coordinates'    => 'Coordinates',
        'date_cut_off'   => 'Cut Off Date',
        'date_installed' => 'Installed Date',
        'date_created'   => 'Created Date',
        'address'        => 'Address',

        'account_connected'      => 'Accounts Connected.',
        'disconnected'           => ' disconnected, ',
        'installing'             => ' installing, ',
        'installment'            => ' installment, ',
        'monthly_unpaid'         => ' monthly unpaid.',
        'no_billing'             => ' no billing.',
        'paid_billing'           => 'Paid Billings.',
        'registered_customer'    => 'Registered Customers.',
        'sum_of_advanced'        => 'Sum of all customers advanced.',
        'total_accounts'         => ' total accounts.',
        'total_advanced_payment' => 'Total Advanced Payment.',

        // Near Cut Off 
        'near_cut_off' => 'Near Cut Off Accounts',
        'install_account' => 'Install Accounts',
    ],
    
    // OLT
    'olt_base_oid_hint' => 'Interface based object identifier',
    'olt_community_read' => 'Community read',
    'olt_community_write' => 'Community write',

    // Customer Portal
    'gcash_button_pending' => 'Reprocess...',
    'gcash_button_pay' => 'Gcash pay',

    'customer_portal' => [
        'next_bill' => 'Next Bill'
    ],

    'billing_grouping' => 'Billing Grouping',

    'piso_wifi' => [
        'schedule_hint' => 'Harvest date.',
        'harvestor' => 'Harvestor / Collector',
        'harvestor_required' => 'The harvestor / collector field is required.',
    ],

    'wifi_harvest' => [
        'total' => 'Net Income',
        'status' => 'Harvest Status',
        'gross_income' => 'Gross Income',
        'internet_fee' => 'Internet Fee',
        'electric_bill' => 'Electric Bill',
        'lessor' => 'Lessor',  
        'date' => 'Date',
        'particulars_hint' => 'Enter a negative value for deductions and a positive value for non-deductions in the particulars amount. You can also leave it blank, and it will automatically populate with the basic particulars. You can also edit it later for each amount value.',  
        'particulars_hint_edit' => 'Enter a negative value for deductions and a positive value for non-deductions in the particulars amount.',
    ]
];

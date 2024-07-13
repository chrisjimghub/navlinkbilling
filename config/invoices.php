<?php

return [
    'serial_number' => [
        'sequence' => 1,

        /*
         * Sequence will be padded accordingly, for ex. 00001
         */
        'sequence_padding' => 7,


        // 'format' => '{SERIES}{DELIMITER}{SEQUENCE}',
        'format' => '{SEQUENCE}',
    ],

    'currency' => [
        'code' => env('CURRENCY_CODE', 'php'),

        /*
         * Usually cents
         * Used when spelling out the amount and if your currency has decimals.
         *
         * Example: Amount in words: Eight hundred fifty thousand sixty-eight EUR and fifteen ct.
         */
        'fraction' => env('CURRENCY_FRACTION', 'ct.'),
        'symbol'   => env('CURRENCY_PREFIX', '₱'),

        /*
         * Example: 19.00
         */
        'decimals' => env('CURRENCY_DECIMAL_PRECISION', '2'),

        /*
         * Example: 1.99
         */
        'decimal_point' => env('CURRENCY_DECIMAL_POINT', '.'),

        /*
         * By default empty.
         * Example: 1,999.00
         */
        'thousands_separator' => env('CURRENCY_THOUSAND_SEPARATOR', ','),

        /*
         * Supported tags {VALUE}, {SYMBOL}, {CODE}
         * Example: 1.99 €
         */
        'format' => '{SYMBOL} {VALUE}',
    ],

    'paper' => [
        // A4 = 210 mm x 297 mm = 595 pt x 842 pt
        'size'        => 'a4',
        'orientation' => 'portrait',
    ],

    'disk' => 'local',

    'seller' => [
        /*
         * Class used in templates via $invoice->seller
         *
         * Must implement LaravelDaily\Invoices\Contracts\PartyContract
         *      or extend LaravelDaily\Invoices\Classes\Party
         */
        'class' => \LaravelDaily\Invoices\Classes\Seller::class,

        /*
         * Default attributes for Seller::class
         */
        'attributes' => [
            'custom_fields' => [
                /*
                 * Custom attributes for Seller::class
                 *
                 * Used to display additional info on Seller section in invoice
                 * attribute => value
                 */
                'company' => 'NavLink Technology FBR-X',
                'address' => 'Brgy. San Isidro Palompon Leyte',
                'phone' => '09958476256 / 09093639756',
            ],
        ],
    ],

    'dompdf_options' => [
        'enable_php' => true,
        /**
         * Do not write log.html or make it optional
         *  @see https://github.com/dompdf/dompdf/issues/2810
         */
        'logOutputFile' => '/dev/null',
    ],

    'project_logo' => env('PROJECT_LOGO', '/images/NAVLINK_LOGO.png'),
];

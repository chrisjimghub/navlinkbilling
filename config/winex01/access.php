<?php

return [
    'connections' => [
        'access' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE_ACCESS', __DIR__.'/../database/access.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],
    ],


    'key' => env('W01_ACCESS_KEY', 'base64:67FHmS0kc8GrJsgPFGeP89epbEuqDuQPJ3BR2oNygeA='),

    'check_key_every' => 50// in minutes
];
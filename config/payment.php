<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'tap'),

    'gateways' => [
        'paymob' => [
            'api_key' => env('PAYMOB_API_KEY'),
            'integration_id' => env('PAYMOB_INTEGRATION_ID'),
            'iframe_id' => env('PAYMOB_IFRAME_ID'),
            'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
        ],
        'tap' => [
            'api_key' => env('TAP_API_KEY'),
            'secret_key' => env('TAP_SECRET_KEY'),
            'publishable_key' => env('TAP_PUBLISHABLE_KEY'),
            'currency' => env('TAP_CURRENCY', 'SAR'),
        ],
    ],

    'currency' => env('PAYMENT_CURRENCY', 'SAR'),
    'currency_decimal_points' => 2,
]; 
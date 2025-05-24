<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tap Payment Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for Tap Payment Gateway.
    |
    */

    'base_url' => env('TAP_BASE_URL', 'https://api.tap.company/v2/'),
    'secret_key' => env('TAP_SECRET_KEY'),
    'encryption_key' => env('TAP_ENCRYPTION_KEY'),
    'publishable_key' => env('TAP_PUBLISHABLE_KEY'),
    'currency' => env('TAP_CURRENCY', 'SAR'),
    'redirect_url' => env('TAP_REDIRECT_URL', '/Payment/Callback?invoiceReferenceId='),
    'site_base_url' => env('TAP_SITE_BASE_URL', 'https://register.itqan-quran.com/'),
]; 
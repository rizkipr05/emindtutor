<?php

return [

    'fonnte' => [
        'token' => env('FONNTE_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'toyyibpay' => [
        'user_secret_key' => env('TOYYIBPAY_USER_SECRET_KEY'),
        'category_code' => env('TOYYIBPAY_CATEGORY_CODE'),
        'base_url' => env('TOYYIBPAY_BASE_URL', 'https://toyyibpay.com'),
        'api_url' => env('TOYYIBPAY_API_URL', 'https://toyyibpay.com'),
        'payment_channel' => env('TOYYIBPAY_PAYMENT_CHANNEL', 0),
        'expiry_days' => env('TOYYIBPAY_EXPIRY_DAYS', 1),
    ],

];

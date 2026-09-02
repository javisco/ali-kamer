<?php

return [

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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'campay' => [
        'permanent_token' => env('CAMPAY_PERMANENT_TOKEN'),
        'username'        => env('CAMPAY_APP_USERNAME'),
        'password'        => env('CAMPAY_APP_PASSWORD'),
        'base_url'        => env('CAMPAY_BASE_URL', 'https://demo.campay.net/api'),
    ],
    'vonage' => [
        'key'          => env('VONAGE_KEY'),
        'secret'       => env('VONAGE_SECRET'),
        'sms_from'     => env('VONAGE_SMS_FROM', 'AliKamer'), // nom expéditeur (11 car. max, alphanumérique)
    ],

    'whatsapp' => [
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'access_token'     => env('WHATSAPP_ACCESS_TOKEN'),
        'api_version'      => env('WHATSAPP_API_VERSION', 'v21.0'),
    ],
    'elgiopay' => [
        'base_url'       => env('ELGIOPAY_BASE_URL', 'https://sandbox-api.elgiopay.com'),
        'secret_token'   => env('ELGIOPAY_SECRET_TOKEN'),
        'webhook_secret' => env('ELGIOPAY_WEBHOOK_SECRET'), // whsec_...
    ],


];

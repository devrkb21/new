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

    'pathao' => [
        'url' => env('PATHAO_API_URL'),
        'credentials' => array_map(
            function ($id, $secret, $user, $pass) {
                return [
                    'client_id' => $id,
                    'client_secret' => $secret,
                    'username' => $user,
                    'password' => $pass,
                ];
            },
            explode(',', env('PATHAO_CLIENT_IDS')),
            explode(',', env('PATHAO_CLIENT_SECRETS')),
            explode(',', env('PATHAO_USERNAMES')),
            explode(',', env('PATHAO_PASSWORDS'))
        ),
    ],

    'steadfast' => [
        'url' => env('STEADFAST_API_URL'),
    ],

    'redx' => [
        'url' => env('REDX_API_URL'),
        'tokens' => explode(',', env('REDX_API_TOKENS')),
    ],
    'sms' => [
        'api_key' => env('SMS_API_KEY'),
        'senderid' => env('SMS_SENDER_ID'),
        'url' => env('SMS_API_URL'),
    ],
];

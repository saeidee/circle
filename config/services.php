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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'password_client' => [
        'id' => env('PASSWORD_CLIENT_ID'),
        'secret' => env('PASSWORD_CLIENT_SECRET'),
    ],

    'sendgrid' => [
        'endpoints' => [
            'send_email' => 'https://webhook.site/008a3381-b4bf-483c-9afa-a573d165611d',
        ],
        'secret' => env('SENDGRID_SECRET'),
    ],

    'mailjet' => [
        'endpoints' => [
            'send_email' => 'https://webhook.site/008a3381-b4bf-483c-9afa-a573d165611d',
        ],
        'secret' => env('MAILJET_SECRET'),
    ],

    'slack' => [
        'endpoint' => env('SLACK_HOST'),
    ],
];

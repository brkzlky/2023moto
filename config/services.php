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
        'domain' => env('MAILGUN_DOMAIN', 'mg.motovago.com'),
        'secret' => env('MAILGUN_SECRET', 'ec5152eb73b8cb6e25ebf556e2abdcd9-48c092ba-32ad33e8'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.eu.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', '989785507691-9ica61g8lkoakdrht6o7kkonj5nqqaiv.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET','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'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'https://motovago.com/member/google-login')
    ],
    'tiktok' => [
        'client_id' => env('TIKTOK_CLIENT_ID', 'aw6k0kzxjicy2p0s'),
        'client_secret' => env('TIKTOK_CLIENT_SECRET', '552e8f1e1e36321947d71d29f21e5100'),
        'redirect' => env('TIKTOK_REDIRECT_URI', 'https://motovago.com/member/tiktok-login')
    ],
    'instagram' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID', '461078638704313'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET', '4cdfba923a8703cf43a95d8b2c29bc07'),
        'redirect' => env('INSTAGRAM_REDIRECT_URI', 'https://motovago.com/member/instagram-login')
    ],

];

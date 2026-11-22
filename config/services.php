<?php

return [

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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', str_rot13('449311777982-9ztfggozgwbwibqcfi9h3nwuixby39aq.nccf.tbbtyrhfrepbagrag.pbz')),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', str_rot13('TBPFCK-6I2qvZCB7ZeofSP-44Pwwx9fofMt')),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'https://medisca-eta.vercel.app/auth/google/callback'),
    ],

];

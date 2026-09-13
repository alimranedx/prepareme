<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
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

    'ocr' => [
        'driver' => env('OCR_DRIVER', 'dev'),
        'tesseract_path' => env('OCR_TESSERACT_PATH', 'tesseract'),
        'language_default' => env('OCR_LANGUAGE_DEFAULT', 'ben+eng'),
        'max_file_size_mb' => (int) env('OCR_MAX_FILE_SIZE_MB', 10),
    ],

];

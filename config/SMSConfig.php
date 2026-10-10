<?php
return [

    'default' => env('SMS_METHOD', 'manual'),

    'SMS' => [
        'twilio' => [
            'twilio_account_sid' => ['value' => env('TWILIO_ACCOUNT_SID'), 'is_protected' => true],
            'twilio_auth_token' => ['value' => env('TWILIO_AUTH_TOKEN'), 'is_protected' => true],
            'twilio_phone_number' => ['value' => env('TWILIO_PHONE_NUMBER'), 'is_protected' => false],
        ],
        'infobip' => [
            'infobip_api_key' => ['value' => env('INFOBIP_API_KEY'), 'is_protected' =>true],
            'infobip_url_base_path' => ['value' => env('INFOBIP_URL_BASE_PATH'), 'is_protected' => false],
        ],
        'plivo' => [
            'plivo_id' => ['value' => env('PLIVO_ID'), 'is_protected' => false],
            'plivo_auth_id' => ['value' => env('PLIVO_AUTH_ID'), 'is_protected' => true],
            'plivo_auth_token' => ['value' => env('PLIVO_AUTH_TOKEN'), 'is_protected' => true],
        ],
        'vonage' => [
            'vonage_api_key' => ['value' => env('VONAGE_API_KEY'), 'is_protected' => true],
            'vonage_api_secret' => ['value' => env('VONAGE_API_SECRET'), 'is_protected' => true],
        ],
        'manual' => [
        ]
    ]

];

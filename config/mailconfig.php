<?php
return [
    'mailchimp' => [
        'mailchimp_api_key' => ['value' => env('MAILCHIMP_API_KEY'), 'is_protected' => true],
    ],
    'mailersend' => [
        'mailersend_api_key' => ['value' => env('MAILERSEND_API_KEY'), 'is_protected' => true],
    ],
    'mailgun' => [
        'mailgun_domain' => ['value' => env('MAILGUN_DOMAIN'), 'is_protected' => true],
        'mailgun_secret' => ['value' => env('MAILGUN_SECRET'), 'is_protected' => true],
    ],
    'postmark' => [
        'postmark_token' => ['value' => env('POSTMARK_TOKEN'), 'is_protected' => true],
    ],
    'sendgrid' => [
        'sendgrid_api_key' => ['value' => env('SENDGRID_API_KEY'), 'is_protected' => true],
    ],
    'sendinblue' => [
        'sendinblue_api_key' => ['value' => env('SENDINBLUE_API_KEY'), 'is_protected' => true],
    ],
    'SES' => [
        'aws_access_key_id' => ['value' => env('AWS_ACCESS_KEY_ID'), 'is_protected' => true],
        'aws_default_region' => ['value' => env('AWS_DEFAULT_REGION'), 'is_protected' => true],
        'aws_secret_access_key' => ['value' => env('AWS_SECRET_ACCESS_KEY'), 'is_protected' => true],
        'aws_session_token' => ['value' => env('AWS_SESSION_TOKEN'), 'is_protected' => true],
    ],
    'SMTP' => [
        'mail_host' => ['value' => env('MAIL_HOST'), 'is_protected' => false],
        'mail_port' => ['value' => env('MAIL_PORT'), 'is_protected' => false],
        'mail_username' => ['value' => env('MAIL_USERNAME'), 'is_protected' => false],
        'mail_password' => ['value' => env('MAIL_PASSWORD'), 'is_protected' => true],
        'mail_encryption' => ['value' => env('MAIL_ENCRYPTION'), 'is_protected' => false],
    ]
];

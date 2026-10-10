<?php
return [
    'settings' => [
        'basic-control' => [
            'route' => 'admin.basic.control',
            'icon' => 'bi bi-gear',
            'short_description' => 'Basic such as, site title, timezone, currency, notifications, verifications and so on.',
        ],
        'logo' => [
            'route' => 'admin.logo.settings',
            'icon' => 'fa-light fa-image',
            'short_description' => 'Logo settings such as, logo, footer logo, admin logo, favicon, breadcrumb.',
        ],
        'push-notification' => [
            'route' => 'admin.settings',
            'route_segment' => ['push-notification'],
            'icon' => 'fa-light fa-bullhorn',
            'short_description' => 'Push notification settings such as, firebase configuration and push notification templates.',
        ],
        'in-app-notification' => [
            'route' => 'admin.settings',
            'route_segment' => ['in-app-notification'],
            'icon' => 'bi-bell nav-icon',
            'short_description' => 'In app notification settings such as, pusher configuration and in app notification templates.',
        ],
        'email' => [
            'route' => 'admin.settings',
            'route_segment' => ['email'],
            'icon' => 'fa-light fa-envelope',
            'short_description' => 'Email settings such as, email configuration and email templates.',
        ],
        'SMS' => [
            'route' => 'admin.settings',
            'route_segment' => ['sms'],
            'icon' => 'fa-light fa-message',
            'short_description' => 'SMS settings such as, SMS configuration and SMS templates.',
        ],
        'language' => [
            'route' => 'admin.language.index',
            'icon' => 'fa-light fa-language',
            'short_description' => 'Language settings such as, create new language, add keywords and so on.',
        ],
        'Storage' => [
            'route' => 'admin.storage.index',
            'icon' => 'fa-light fa-database',
            'short_description' => 'Storage settings such as, store images.',
        ],
        'exchange_API' => [
            'route' => 'admin.currency.exchange.api.config',
            'icon' => 'bi bi-arrow-down-up',
            'short_description' => 'Currency Layer Access Key, Coin Market Cap App Key, Select update time and so on.',
        ],
        'translate_api' => [
            'route' => 'admin.translate.api.setting',
            'icon' => 'fa-sharp fa-light fa-language',
            'short_description' => 'Translate API service for google sheet, drive and others.',
        ],
        'plugin' => [
            'route' => 'admin.plugin.config',
            'icon' => 'fa-thin fa-toolbox',
            'short_description' => 'Message your customers, reCAPTCHA protects, google analytics your website and so on.',
        ],
        'maintenance_mode' => [
            'route' => 'admin.maintenance.index',
            'icon' => 'fa-thin fa-screwdriver-wrench',
            'short_description' => "Maintenance mode is a feature that allows you to temporarily disable access to your online store's frontend while you perform updates.",
        ],
        'Socialite' => [
            'route' => 'admin.socialite.index',
            'icon' => 'fas fa-share-alt',
            'short_description' => 'Socialite settings such as, advantage for user login there dashboard without register process.',
        ],
    ],
    'plugin' => [
        'tawk' => [
            'route' => 'admin.tawk.configuration',
            'icon' => 'fa-thin fa-crow nav-icon',
            'short_description' => 'Message your customers,they\'ll love you for it',
        ],
        'fb-messenger' => [
            'route' => 'admin.fb.messenger.configuration',
            'icon' => 'bi bi-chat-right-dots nav-icon',
            'short_description' => 'Message your customers,they\'ll love you for it',
        ],
        'google-recaptcha' => [
            'route' => 'admin.google.recaptcha.configuration',
            'icon' => 'bi-google nav-icon',
            'short_description' => 'reCAPTCHA protects your website from fraud and abuse.',
        ],
        'manual-recaptcha' => [
            'route' => 'admin.manual.recaptcha',
            'icon' => 'bi bi-file-lock nav-icon',
            'short_description' => 'reCAPTCHA protects your website from fraud and abuse.',
        ],
        'google-analytics' => [
            'route' => 'admin.google.analytics.configuration',
            'icon' => 'bi bi-graph-down nav-icon',
            'short_description' => 'Google Analytics is a web analytics service offered by Google.',
        ],
    ],
    'in-app-notification' => [
        'in-app-notification-configuration' => [
            'route' => 'admin.pusher.config',
            'icon' => 'bi-bell nav-icon',
            'short_description' => 'Set up the configuration for Pusher to enable in-app notifications.',
        ],
        'notification-templates' => [
            'route' => 'admin.in.app.notification.templates',
            'icon' => 'fa-light fa-scroll',
            'short_description' => 'Configure the templates for in-app notifications.',
        ]
    ],
    'push-notification' => [
        'push-notification-configuration' => [
            'route' => 'admin.firebase.config',
            'icon' => 'bi-bell nav-icon',
            'short_description' => 'Set up Firebase configuration for push notifications.',
        ],
        'notification-templates' => [
            'route' => 'admin.push.notification.templates',
            'icon' => 'fa-light fa-scroll',
            'short_description' => 'Set up push notification templates.',
        ]
    ],
    'email' => [
        'email-configuration' => [
            'route' => 'admin.email.control',
            'icon' => 'fa-light fa-envelope',
            'short_description' => 'Email Config such as, sender email, email methods and etc.',
        ],
        'default-templates' => [
            'route' => 'admin.email.template.default',
            'icon' => 'fa-light fa-shield-cross',
            'short_description' => 'Setup email templates for default email notifications.',
        ],
        'email-templates' => [
            'route' => 'admin.email.templates',
            'icon' => 'fas fa-laptop-code',
            'short_description' => 'Setup email templates for different email notifications.',
        ]

    ],
    'sms' => [
        'SMS-configuration' => [
            'route' => 'admin.sms.controls',
            'icon' => 'bi bi-chat-square-dots',
            'short_description' => 'Setup SMS api configuration for sending sms notifications.',
        ],
        'SMS-templates' => [
            'route' => 'admin.sms.templates',
            'icon' => 'bi bi-laptop',
            'short_description' => 'Setup sms templates for different email notifications.',
        ]
    ],
];


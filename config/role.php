<?php

$arr = [
    'dashboard' => [
        'label' => "Dashboard",
        'access' => [
            'view' => ['admin.dashboard'],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],
    'manage_role' =>[
        'label' => "Role Permission",
        'access' => [
            'view' => ['admin.staff'],
            'add' => ['admin.storeStaff'],
            'edit' => ['admin.updateStaff'],
            'delete' => [],
        ],
    ],
    'identify_form' =>[
        'label' => "KYC / Identity Form",
        'access' => [
            'view' => ['admin.identify-form'],
            'add' => ['admin.identify-form.store'],
            'edit' => [
                'admin.identify-form.store',
                'admin.identify-form.action'
            ],
            'delete' => [],
        ],
    ],
    'manage_user' => [
        'label' => "Manage User",
        'access' => [
            'view' => [
                'admin.users',
                'admin.user-multiple-active',
                'admin.user-multiple-inactive',
                'admin.user-edit',
                'admin.kyc.users.pending',
                'admin.kyc.users',
                'admin.email-send',
            ],
            'add' => ['admin.email-send.store'],
            'edit' => [
                'admin.user-update',
                'admin.userPasswordUpdate',
                'admin.user-balance-update',
                'admin.userKycHistory',
                'admin.send-email',
                'admin.login-as-user',
                'admin.users.Kyc.action',
            ],
            'delete' => [],
        ],
    ],
    'manage_property' => [
        'label' => "Manage Property",
        'access' => [
            'view' => [
                'admin.scheduleManage',
                'admin.amenities',
                'admin.addressList',
                'admin.propertyList',
            ],
            'add' => [
                'admin.store.schedule',
                'admin.amenitiesCreate',
                'admin.addressCreate',
                'admin.propertyCreate',
            ],
            'edit' => [
                'admin.update.schedule',
                'admin.amenitiesEdit',
                'admin.addressEdit',
                'admin.propertyEdit',
                'admin.property-active',
                'admin.property-inactive',

            ],
            'delete' => [
                'admin.propertyDelete',
            ],
        ],
    ],

    'investments' => [
        'label' => "Investments",
        'access' => [
            'view' => [
                'admin.investments',
                'admin.investmentDetails',
            ],
            'add' => [],
            'edit' => [
                'admin.investActive',
                'admin.investDeactive',
                'admin.multiple.invest.active',
                'admin.multiple.invest.deactive',

            ],
            'delete' => [],
        ],
    ],

    'manage_badge' => [
        'label' => "Manage Badge",
        'access' => [
            'view' => [
                'admin.badgeSettings',
                'admin.rankingsUser',
            ],
            'add' => [
                'admin.rankCreate',
            ],
            'edit' => [
                'admin.badge.settings.action',
                'admin.sort.badges',
                'admin.rankEdit',
            ],
            'delete' => [],
        ],
    ],

    'commission_setting' => [
        'label' => "Commission Setting",
        'access' => [
            'view' => [
                'admin.referral-commission',
            ],
            'add' => [
                'admin.referral-commission.store',
            ],
            'edit' => [
                'admin.referral-commission.action'
            ],
            'delete' => [],
        ],
    ],

    'payment_settings' => [
        'label' => "Payment Settings",
        'access' => [
            'view' => [
                'admin.payment.methods',
                'admin.deposit.manual.index',
                'admin.payment.pending',
                'admin.payment.log',
            ],
            'add' => [
                'admin.deposit.manual.create',
            ],
            'edit' => [
                'admin.edit.payment.methods',
                'admin.deposit.manual.edit',
                'admin.payment.action',
                'admin.payment.methods.deactivate',
            ],
            'delete' => [],
        ],
    ],

    'payout_settings' => [
        'label' => "Payout Settings",
        'access' => [
            'view' => [
                'admin.payout-method',
                'admin.payout.settings',
                'admin.payout-request',
                'admin.payout-log',
                'admin.payout-log.search',
            ],
            'add' => [
                'admin.payout-method.create',
            ],
            'edit' => [
                'admin.payout-method.edit',
                'admin.payout-action',
                'admin.payout.settings.action'
            ],
            'delete' => [],
        ],
    ],

    'all_transaction' => [
        'label' => "All Transaction",
        'access' => [
            'view' => [
                'admin.transaction',
                'admin.transaction.search',
                'admin.commissions',
                'admin.commissions.search'
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],

    'support_ticket' => [
        'label' => "Support Ticket",
        'access' => [
            'view' => [
                'admin.ticket',
                'admin.ticket.view',
            ],
            'add' => [
                'admin.ticket.reply'
            ],
            'edit' => [],
            'delete' => [
                'admin.ticket.delete',
            ],
        ],
    ],

    'subscriber' => [
        'label' => "Subscriber",
        'access' => [
            'view' => [
                'admin.subscriber.index',
                'admin.subscriber.sendEmail'

            ],
            'add' => ['admin.subscriber.mail'],
            'edit' => [],
            'delete' => [
                'admin.subscriber.remove'
            ],
        ],
    ],

    'website_controls' => [
        'label' => "Website Controls",
        'access' => [
            'view' => [
                'admin.basic-controls',
                'admin.plugin.config',
                'admin.tawk.control',
                'admin.fb.messenger.control',
                'admin.google.recaptcha.control',
                'admin.google.analytics.control',
                'admin.email-controls',
                'admin.email-controls',
                'admin.email-template.show',
                'admin.sms.config',
                'admin.sms-template',
                'admin.notify-config',
                'admin.notify-template.show',
                'admin.notify-template.edit',
            ],
            'add' => [],
            'edit' => [
                'admin.basic-controls.update',
                'admin.plugin.config',
                'admin.tawk.control',
                'admin.fb.messenger.control',
                'admin.google.recaptcha.control',
                'admin.google.analytics.control',
                'admin.email-controls.update',
                'admin.email-controls.action',
                'admin.email-template.edit',
                'admin.sms.config',
                'admin.sms-controls.action',
                'admin.sms-template.edit',
                'admin.notify-config.update',
                'admin.notify-template.edit',
                'admin.notify-template.update',
            ],
            'delete' => [],
        ],
    ],

    'language_settings' => [
        'label' => "Language Settings",
        'access' => [
            'view' => [
                'admin.language.index',
            ],
            'add' => [
                'admin.language.create',
            ],
            'edit' => [
                'admin.language.edit',
                'admin.language.keywordEdit',
            ],
            'delete' => [
                'admin.language.delete'
            ],
        ],
    ],

    'theme_settings' =>  [
        'label' => "Theme Settings",
        'access' => [
            'view' => [
                'admin.manage.theme',
                'admin.logo-seo',
                'admin.breadcrumb',
                'admin.template.show',
                'admin.content.index',
            ],
            'add' => [
                'admin.content.create'
            ],
            'edit' => [
                'admin.template.update',
                'admin.logoUpdate',
                'admin.seoUpdate',
                'admin.breadcrumbUpdate',
                'admin.content.show',
            ],
            'delete' => [
                'admin.content.delete'
            ],
        ],
    ],

    'manage_blogs' =>  [
        'label' => "Manage Blogs",
        'access' => [
            'view' => [
                'admin.blogCategory',
                'admin.blogList',
            ],
            'add' => [
                'admin.blogCategoryCreate',
                'admin.blogCreate',
            ],
            'edit' => [
                'admin.blogCategoryEdit',
                'admin.blogEdit',
            ],
            'delete' => [
                'admin.blogCategoryDelete',
                'admin.blogDelete',
            ],
        ],
    ],
];

return $arr;




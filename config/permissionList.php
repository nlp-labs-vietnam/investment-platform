<?php

$arr = [
    "Dashboard" => [
        "Dashboard" => [
            'permission' => [
                'view' => ['admin.dashboard'],
                'add' => [],
                'edit' => [],
                'delete' => [],
            ],
        ],
    ],

    "Manage_Property" => [
        'Profit_Schedule' => [
            'permission' => [
                'view' => ['admin.profit.schedule'],
                'add' => ['admin.profit.schedule.store'],
                'edit' => ['admin.profit.schedule.update'],
                'delete' => ['admin.profit.schedule.delete'],
            ],
        ],
        "Amenities" => [
            'permission' => [
                'view' => ['admin.amenities'],
                'add' => ['admin.amenities.create', 'admin.amenities.store'],
                'edit' => ['admin.amenities.edit', 'admin.amenities.update'],
                'delete' => ['admin.amenities.delete'],
            ],
        ],
        "Address" => [
            'permission' => [
                'view' => ['admin.addresses'],
                'add' => ['admin.address.create', 'admin.address.store'],
                'edit' => ['admin.address.edit', 'admin.address.update'],
                'delete' => ['admin.address.delete'],
            ],
        ],
        "Properties" => [
            'permission' => [
                'view' => ['admin.properties', 'admin.property.details'],
                'add' => ['admin.property.create', 'admin.property.store'],
                'edit' => ['admin.property.edit', 'admin.property.update'],
                'delete' => ['admin.property.delete'],
            ],
        ],
        "Wishlist" => [
            'permission' => [
                'view' => ['admin.property.wishlist', 'admin.internationallyViewShipment', 'admin.internationallyShipmentInvoice'],
                'add' => [],
                'edit' => [],
                'delete' => ['admin.property.wishlist.delete'],
            ],
        ],
    ],

    "Manage_Investment" => [
        'All_Investment' => [
            'permission' => [
                'view' => ['admin.investments', 'admin.investment.details', 'admin.operatorCountryShipmentInvoice'],
                'add' => [],
                'edit' => ['admin.investment.active', 'admin.investment.deactive'],
                'delete' => [],
            ],
        ],
        "Running_Investment" => [
            'permission' => [
                'view' => ['admin.running.investments', 'admin.running.investment.details'],
                'add' => [],
                'edit' => [],
                'delete' => [],
            ],
        ],
        "Due_Investment" => [
            'permission' => [
                'view' => ['admin.due.investments', 'admin.due.investment.details'],
                'add' => [],
                'edit' => [],
                'delete' => [],
            ],
        ],
        "Expired_Investment" => [
            'permission' => [
                'view' => ['admin.expired.investments', 'admin.expired.investment.details'],
                'add' => [],
                'edit' => [],
                'delete' => [],
            ],
        ],
        "Completed_Investment" => [
            'permission' => [
                'view' => ['admin.completed.investments', 'admin.completed.investment.details'],
                'add' => [],
                'edit' => [],
                'delete' => [],
            ],
        ],
    ],

    "Manage_Rank" => [
        'All_Ranks' => [
            'permission' => [
                'view' => ['admin.ranks'],
                'add' => ['admin.rank.create', 'admin.rank.store'],
                'edit' => ['admin.rank.edit', 'admin.rank.update'],
                'delete' => ['admin.rank.delete'],
            ],
        ],
        "Rank_Bonus" => [
            'permission' => [
                'view' => ['admin.rank.bonus'],
                'add' => [],
                'edit' => ['admin.rank.bonus.update'],
                'delete' => [],
            ],
        ],
    ],

    "Manage_Commission" => [
        'Referral' => [
            'permission' => [
                'view' => ['admin.referral.commission'],
                'add' => [],
                'edit' => ['admin.referral.commission.store', 'admin.referral.commission.action'],
                'delete' => [],
            ],
        ],
    ],

    'User_Panel' => [
        "User_Management" => [
            'permission' => [
                'view' => ['admin.users', 'inactive.user.list', 'admin.user.view.profile'],
                'add' => ['admin.users.add', 'admin.user.store'],
                'edit' => ['admin.user.edit', 'admin.user.update', 'admin.user.update.balance'],
                'delete' => ['admin.user.delete.multiple'],
                'send_mail' => ['admin.send.email', 'admin.mail.all.user'],
                'login_as' => ['admin.login.as.user'],
            ],
        ],
    ],

    "Transactions" => [
        'Transaction' => [
            'permission' => [
                'view' => ['admin.transaction', 'admin.transaction.search'],
                'add' => [],
                'edit' => [],
                'delete' => [],
            ],
        ],
        'Commission' => [
            'permission' => [
                'view' => ['admin.commission', 'admin.commission.search'],
                'add' => [],
                'edit' => [],
                'delete' => [],
            ],
        ],
    ],

    "Payment_Transactions" => [
        'Payment_Log' => [
            'permission' => [
                'view' => ['admin.payment.log', 'admin.payment.search'],
                'add' => [],
                'edit' => ['admin.payment.action'],
                'delete' => [],
            ],
        ],
        'Payment_Request' => [
            'permission' => [
                'view' => ['admin.payment.pending', 'admin.payment.request'],
                'add' => [],
                'edit' => ['admin.payment.action'],
                'delete' => [],
            ],
        ],
    ],

    "Withdraw_Transactions" => [
        'Withdraw_Log' => [
            'permission' => [
                'view' => ['admin.payout.log', 'admin.payout.search'],
                'add' => [],
                'edit' => ['admin.payout.action'],
                'delete' => [],
            ],
        ],
        'Withdraw_Request' => [
            'permission' => [
                'view' => ['admin.payout.pending', 'admin.payout.request'],
                'add' => ['admin.operatorCountryCreateShipment', 'admin.operatorCountryShipmentStore'],
                'edit' => ['admin.payout.action'],
                'delete' => [],
            ],
        ],
    ],

    'Kyc_Management' => [
        "Kyc_Settings" => [
            'permission' => [
                'view' => ['admin.kyc.form.list'],
                'add' => ['admin.kyc.create', 'admin.kyc.store'],
                'edit' => ['admin.kyc.edit', 'admin.kyc.update'],
                'delete' => [],
            ],
        ],
        "Kyc_Request" => [
            'permission' => [
                'view' => ['admin.kyc.list', 'kyc.view'],
                'add' => [],
                'edit' => ['admin.kyc.action'],
                'delete' => [],
            ],
        ],
    ],

    'Ticket_Panel' => [
        "Support_Tickets" => [
            'permission' => [
                'view' => ['admin.ticket', 'admin.ticket.view'],
                'add' => [],
                'edit' => ['admin.ticket.reply', 'admin.ticket.download'],
                'delete' => ['admin.ticket.delete'],
            ],
        ],
    ],

    "Subscribers" => [
        'Subscriber_List' => [
            'permission' => [
                'view' => ['admin.subscribers'],
                'add' => [],
                'edit' => [],
                'delete' => ['admin.subscriber.delete'],
            ],
        ],
    ],

    'Roles_And_Permission' => [
        "Available_Roles" => [
            'permission' => [
                'view' => ['admin.role'],
                'add' => ['admin.createRole', 'admin.roleStore'],
                'edit' => ['admin.editRole', 'admin.roleUpdate'],
                'delete' => ['admin.deleteRole'],
                'login_as' => [],
            ],
        ],
        "Manage_Staff" => [
            'permission' => [
                'view' => ['admin.role.staff'],
                'add' => ['admin.role.staffCreate', 'admin.role.staffStore'],
                'edit' => ['admin.role.staffEdit', 'admin.role.updateStaff'],
                'delete' => ['admin.staffDelete'],
                'login_as' => ['admin.role.staffLogin'],
            ],
        ],
    ],

    'Settings_Panel' => [
        "Control_Panel" => [
            'permission' => [
                'view' => ['admin.settings', 'admin.basic.control', 'admin.currency.exchange.api.config', 'admin.storage.index', 'admin.maintenance.index', 'admin.logo.settings', 'admin.firebase.config', 'pusher.config', 'admin.email.control', 'admin.email.template.default', 'admin.email.templates', 'admin.sms.templates', 'admin.in.app.notification.templates', 'admin.push.notification.templates', 'admin.sms.controls', 'admin.plugin.config', 'admin.tawk.configuration', 'admin.fb.messenger.configuration', 'admin.google.recaptcha.configuration', 'admin.google.analytics.configuration', 'admin.manual.recaptcha', 'admin.translate.api.setting', 'admin.language.index', 'admin.language.keywords'],
                'add' => ['admin.language.create', 'admin.language.store', 'admin.add.language.keyword'],
                'edit' => ['admin.basic.control.update', 'admin.basic.control.activity.update', 'admin.currency.exchange.api.config.update', 'admin.maintenance.mode.update', 'admin.logo.update', 'admin.firebase.config.update', 'admin.pusher.config.update', 'admin.email.config.edit', 'admin.email.config.update', 'admin.email.set.default', 'admin.test.email', 'admin.email.template.edit', 'admin.email.template.update', 'admin.sms.template.edit', 'admin.sms.template.update', 'admin.in.app.notification.template.edit', 'admin.in.app.notification.template.update', 'admin.push.notification.template.edit', 'admin.push.notification.template.update', 'admin.sms.config.edit', 'admin.sms.config.update', 'admin.manual.sms.method.update', 'admin.sms.set.default', 'admin.tawk.configuration.update', 'admin.fb.messenger.configuration.update', 'admin.google.recaptcha.Configuration.update', 'admin.google.analytics.configuration.update', 'admin.manual.recaptcha.update', 'admin.active.recaptcha', 'admin.translate.api.config.edit', 'admin.translate.api.setting.update', 'admin.translate.set.default', 'admin.language.edit', 'admin.language.update', 'admin.change.language.status', 'admin.update.language.keyword', 'admin.language.update.key'],
                'delete' => ['admin.language.delete', 'language.delete.key', 'admin.delete.language.keyword'],
            ],
        ],
        "Payment_Setting" => [
            'permission' => [
                'view' => ['admin.settings', 'admin.basic.control', 'admin.currency.exchange.api.config', 'admin.storage.index', 'admin.maintenance.index', 'admin.logo.settings', 'admin.firebase.config', 'pusher.config', 'admin.email.control', 'admin.email.template.default', 'admin.email.templates', 'admin.sms.templates', 'admin.in.app.notification.templates', 'admin.push.notification.templates', 'admin.sms.controls', 'admin.plugin.config', 'admin.tawk.configuration', 'admin.fb.messenger.configuration', 'admin.google.recaptcha.configuration', 'admin.google.analytics.configuration', 'admin.manual.recaptcha', 'admin.translate.api.setting', 'admin.language.index', 'admin.language.keywords'],
                'add' => ['admin.language.create', 'admin.language.store', 'admin.add.language.keyword'],
                'edit' => ['admin.basic.control.update', 'admin.basic.control.activity.update', 'admin.currency.exchange.api.config.update', 'admin.maintenance.mode.update', 'admin.logo.update', 'admin.firebase.config.update', 'admin.pusher.config.update', 'admin.email.config.edit', 'admin.email.config.update', 'admin.email.set.default', 'admin.test.email', 'admin.email.template.edit', 'admin.email.template.update', 'admin.sms.template.edit', 'admin.sms.template.update', 'admin.in.app.notification.template.edit', 'admin.in.app.notification.template.update', 'admin.push.notification.template.edit', 'admin.push.notification.template.update', 'admin.sms.config.edit', 'admin.sms.config.update', 'admin.manual.sms.method.update', 'admin.sms.set.default', 'admin.tawk.configuration.update', 'admin.fb.messenger.configuration.update', 'admin.google.recaptcha.Configuration.update', 'admin.google.analytics.configuration.update', 'admin.manual.recaptcha.update', 'admin.active.recaptcha', 'admin.translate.api.config.edit', 'admin.translate.api.setting.update', 'admin.translate.set.default', 'admin.language.edit', 'admin.language.update', 'admin.change.language.status', 'admin.update.language.keyword', 'admin.language.update.key'],
                'delete' => ['admin.language.delete', 'language.delete.key', 'admin.delete.language.keyword'],
            ],
        ],
        "Withdraw_Setting" => [
            'permission' => [
                'view' => ['admin.settings', 'admin.basic.control', 'admin.currency.exchange.api.config', 'admin.storage.index', 'admin.maintenance.index', 'admin.logo.settings', 'admin.firebase.config', 'pusher.config', 'admin.email.control', 'admin.email.template.default', 'admin.email.templates', 'admin.sms.templates', 'admin.in.app.notification.templates', 'admin.push.notification.templates', 'admin.sms.controls', 'admin.plugin.config', 'admin.tawk.configuration', 'admin.fb.messenger.configuration', 'admin.google.recaptcha.configuration', 'admin.google.analytics.configuration', 'admin.manual.recaptcha', 'admin.translate.api.setting', 'admin.language.index', 'admin.language.keywords'],
                'add' => ['admin.language.create', 'admin.language.store', 'admin.add.language.keyword'],
                'edit' => ['admin.basic.control.update', 'admin.basic.control.activity.update', 'admin.currency.exchange.api.config.update', 'admin.maintenance.mode.update', 'admin.logo.update', 'admin.firebase.config.update', 'admin.pusher.config.update', 'admin.email.config.edit', 'admin.email.config.update', 'admin.email.set.default', 'admin.test.email', 'admin.email.template.edit', 'admin.email.template.update', 'admin.sms.template.edit', 'admin.sms.template.update', 'admin.in.app.notification.template.edit', 'admin.in.app.notification.template.update', 'admin.push.notification.template.edit', 'admin.push.notification.template.update', 'admin.sms.config.edit', 'admin.sms.config.update', 'admin.manual.sms.method.update', 'admin.sms.set.default', 'admin.tawk.configuration.update', 'admin.fb.messenger.configuration.update', 'admin.google.recaptcha.Configuration.update', 'admin.google.analytics.configuration.update', 'admin.manual.recaptcha.update', 'admin.active.recaptcha', 'admin.translate.api.config.edit', 'admin.translate.api.setting.update', 'admin.translate.set.default', 'admin.language.edit', 'admin.language.update', 'admin.change.language.status', 'admin.update.language.keyword', 'admin.language.update.key'],
                'delete' => ['admin.language.delete', 'language.delete.key', 'admin.delete.language.keyword'],
            ],
        ],
    ],

    'Theme_Settings' => [
        "Pages" => [
            'permission' => [
                'view' => ['admin.page.index', 'admin.page.seo'],
                'add' => ['admin.create.page', 'admin.create.page.store'],
                'edit' => ['admin.edit.page', 'admin.update.page', 'admin.update.slug', 'admin.page.seo.update'],
                'delete' => ['admin.page.delete'],
            ],
        ],
        "Manage_Menu" => [
            'permission' => [
                'view' => ['admin.manage.menu', 'admin.get.custom.link'],
                'add' => ['admin.header.menu.item.store', 'admin.footer.menu.item.store', 'admin.add.custom.link'],
                'edit' => ['admin.edit.custom.link', 'admin.update.custom.link'],
                'delete' => ['admin.delete.custom.link'],
            ],
        ],
        "Manage_Theme" => [
            'permission' => [
                'view' => ['admin.manage.theme'],
                'add' => [],
                'edit' => [],
                'delete' => ['admin.delete.custom.link'],
            ],
        ],
        "Manage_Content" => [
            'permission' => [
                'view' => ['admin.manage.content', 'admin.manage.content.multiple'],
                'add' => ['admin.content.store', 'admin.content.multiple.store'],
                'edit' => ['admin.content.item.edit', 'admin.multiple.content.item.update'],
                'delete' => ['admin.content.item.delete'],
            ],
        ],
    ],

    'Manage_Blog' => [
        "Category" => [
            'permission' => [
                'view' => ['admin.blog-category.index'],
                'add' => ['admin.blog-category.create', 'admin.blog-category.store'],
                'edit' => ['admin.blog-category.edit', 'admin.blog-category.update'],
                'delete' => ['admin.blog-category.destroy'],
            ],
        ],
        "Blog" => [
            'permission' => [
                'view' => ['admin.blogs.index'],
                'add' => ['admin.blogs.create', 'admin.blogs.store', 'admin.blog.seo'],
                'edit' => ['admin.blog.edit', 'admin.blog.update', 'admin.slug.update', 'admin.blog.seo.update'],
                'delete' => ['admin.blogs.destroy'],
            ],
        ],
    ],
];

return $arr;

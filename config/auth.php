<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

    /*
    * Socialite
    */

    'socialite' => [
        'drivers' => [
            'google',
            'facebook',
        ],
    ],

    'permissions' => [
        'app.usermgmt.view' => [
            'label' => 'permissions.view_usermgmt',
            'sensitive' => true,
        ],
        'app.usermgmt.users.manage' => [
            'label' => 'permissions.usermgmt_manage_users',
            'sensitive' => true,
        ],
        'app.usermgmt.roles.manage' => [
            'label' => 'permissions.usermgmt_manage_roles',
            'sensitive' => false,
        ],

        'app.settings.common.configure' => [
            'label' => 'permissions.configure_common_settings',
            'sensitive' => false,
        ],

        'app.changelogs.view' => [
            'label' => 'permissions.view_changelogs',
            'sensitive' => false,
        ],

        'badges.create' => [
            'label' => 'permissions.create_badges',
            'sensitive' => false,
        ],

        'fundraising.donors_donations.view' => [
            'label' => 'permissions.view_fundraising_donors_donations',
            'sensitive' => true,
        ],
        'fundraising.donors_donations.manage' => [
            'label' => 'permissions.manage_fundraising_donors_donations',
            'sensitive' => true,
        ],
        'fundraising.reports.view' => [
            'label' => 'permissions.view_fundraising_reports',
            'sensitive' => true,
        ],
        'fundraising.donations.accept_webhooks' => [
            'label' => 'permissions.accept_fundraising_donations_webhooks',
            'sensitive' => false,
        ],

        'accounting.transactions.view' => [
            'label' => 'permissions.view_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.create' => [
            'label' => 'permissions.create_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.update' => [
            'label' => 'permissions.update_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.delete' => [
            'label' => 'permissions.delete_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.book_externally' => [
            'label' => 'permissions.book_externally',
            'sensitive' => true,
        ],
        'accounting.summary.view' => [
            'label' => 'permissions.view_summary',
            'sensitive' => false,
        ],
        'accounting.configure' => [
            'label' => 'permissions.configure_accounting',
            'sensitive' => false,
        ],

        'wiki.view' => [
            'label' => 'permissions.view_wiki',
            'sensitive' => false,
        ],
        'wiki.edit' => [
            'label' => 'permissions.edit_wiki',
            'sensitive' => false,
        ],
        'wiki.delete' => [
            'label' => 'permissions.delete_wiki',
            'sensitive' => false,
        ],

        'people.list' => [
            'label' => 'permissions.list_people',
            'sensitive' => true,
        ],
        'people.view' => [
            'label' => 'permissions.view_people',
            'sensitive' => true,
        ],
        'people.manage' => [
            'label' => 'permissions.manage_people',
            'sensitive' => true,
        ],
        'people.export' => [
            'label' => 'permissions.export_people',
            'sensitive' => true,
        ],
        'people.reports.view' => [
            'label' => 'permissions.view_people_reports',
            'sensitive' => false,
        ],

        'bank.withdrawals.do' => [
            'label' => 'permissions.do_bank_withdrawals',
            'sensitive' => true,
        ],
        'bank.statistics.view' => [
            'label' => 'permissions.view_bank_statistics',
            'sensitive' => false,
        ],
        'bank.configure' => [
            'label' => 'permissions.configure_bank',
            'sensitive' => false,
        ],

        'cmtyvol.view' => [
            'label' => 'permissions.view_community_volunteers',
            'sensitive' => true,
        ],
        'cmtyvol.manage' => [
            'label' => 'permissions.manage_community_volunteers',
            'sensitive' => true,
        ],

        'library.operate' => [
            'label' => 'permissions.operate_library',
            'sensitive' => true,
        ],
        'library.configure' => [
            'label' => 'permissions.configure_library',
            'sensitive' => true,
        ],

        'shop.coupons.validate' => [
            'label' => 'permissions.validate_shop_coupons',
            'sensitive' => true,
        ],
        'shop.configure' => [
            'label' => 'permissions.configure_shop',
            'sensitive' => false,
        ],

        'visitors.register' => [
            'label' => 'permissions.register_visitors',
            'sensitive' => true,
        ],
    ],
];

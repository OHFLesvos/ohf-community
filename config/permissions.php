<?php

return [
    'keys' => [
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
        'accounting.suppliers.manage' => [
            'label' => 'permissions.manage_suppliers',
            'sensitive' => true,
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
        'visitors.export' => [
            'label' => 'permissions.export_visitors',
            'sensitive' => true,
        ],
    ],
    'gate_mapping' => [
        'view-reports' => [  // TODO
            'people.reports.view',
            'bank.statistics.view',
            'app.usermgmt.view'
        ],
        'view-usermgmt-reports' => 'app.usermgmt.view',
        'configure-common-settings' => 'app.settings.commonbr.configure',
        'create-badges'=> 'badges.create',
        'view-fundraising' => [
            'fundraising.donors_donations.view',
            'fundraising.reports.view',
        ],
        'view-fundraising-entities' => 'fundraising.donors_donations.view',
        'manage-fundraising-entities' => 'fundraising.donors_donations.manage',
        'view-fundraising-reports'=> 'fundraising.reports.view',
        'accept-fundraising-webhooks' => 'fundraising.donations.accept_webhooks',
        'view-accounting-summary' => 'accounting.summary.view',
        'book-accounting-transactions-externally' => 'accounting.transactions.book_externally',
        'manage-suppliers' => 'accounting.suppliers.manage',
        'configure-accounting' => 'accounting.configure',
        'manage-people' => 'people.manage',
        'view-people-reports' => 'people.reports.view',
        'view-bank-index' => [
            'bank.withdrawals.do',
            'bank.configure',
        ],
        'do-bank-withdrawals' => 'bank.withdrawals.do',
        'view-bank-reports' => 'bank.statistics.view',
        'configure-bank' => 'bank.configure',
        'manage-community-volunteers' => 'cmtyvol.manage',
        'operate-library' => 'library.operate',
        'configure-library' => 'library.configure',
        'validate-shop-coupons' => 'shop.coupons.validate',
        'configure-shop' => 'shop.configure',
        'register-visitors' => 'visitors.register',
        'export-visitors' => 'visitors.export',
    ],
];

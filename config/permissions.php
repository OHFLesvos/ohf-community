<?php

return [
    'keys' => [
        'app.usermgmt.view' => [
            'label' => 'auth.permissions.view_usermgmt',
        ],
        'app.usermgmt.users.manage' => [
            'label' => 'auth.permissions.usermgmt_manage_users',
        ],
        'app.usermgmt.roles.manage' => [
            'label' => 'auth.permissions.usermgmt_manage_roles',
        ],
        'app.settings.common.configure' => [
            'label' => 'auth.permissions.configure_common_settings',
        ],
        'badges.create' => [
            'label' => 'auth.permissions.create_badges',
        ],
        'fundraising.donors_donations.view' => [
            'label' => 'auth.permissions.view_fundraising_donors_donations',
        ],
        'fundraising.donors_donations.manage' => [
            'label' => 'auth.permissions.manage_fundraising_donors_donations',
        ],
        'fundraising.reports.view' => [
            'label' => 'auth.permissions.view_fundraising_reports',
        ],
        'fundraising.donations.accept_webhooks' => [
            'label' => 'auth.permissions.accept_fundraising_donations_webhooks',
        ],
        'accounting.transactions.view' => [
            'label' => 'auth.permissions.view_transactions',
        ],
        'accounting.transactions.create' => [
            'label' => 'auth.permissions.create_transactions',
        ],
        'accounting.transactions.update' => [
            'label' => 'auth.permissions.update_transactions',
        ],
        'accounting.transactions.update_metadata' => [
            'label' => 'auth.permissions.update_transaction_metadata',
        ],
        'accounting.transactions.delete' => [
            'label' => 'auth.permissions.delete_transactions',
        ],
        'accounting.transactions.book_externally' => [
            'label' => 'auth.permissions.book_externally',
        ],
        'accounting.summary.view' => [
            'label' => 'auth.permissions.view_summary',
        ],
        'accounting.suppliers.manage' => [
            'label' => 'auth.permissions.manage_suppliers',
        ],
        'accounting.budgets.view' => [
            'label' => 'auth.permissions.view_budgets',
        ],
        'accounting.budgets.manage' => [
            'label' => 'auth.permissions.manage_budgets',
        ],
        'accounting.configure' => [
            'label' => 'auth.permissions.configure_accounting',
        ],
        'cmtyvol.view' => [
            'label' => 'auth.permissions.view_community_volunteers',
        ],
        'cmtyvol.manage' => [
            'label' => 'auth.permissions.manage_community_volunteers',
        ],
        'visitors.register' => [
            'label' => 'auth.permissions.register_visitors',
        ],
        'visitors.export' => [
            'label' => 'auth.permissions.export_visitors',
        ],
    ],
    'gate_mapping' => [
        'view-reports' => [  // TODO
            'app.usermgmt.view',
            'cmtyvol.manage',
            'visitors.register',
            'fundraising.reports.view',
        ],
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
        'manage-budgets' => 'accounting.budgets.manage',
        'configure-accounting' => 'accounting.configure',
        'manage-community-volunteers' => 'cmtyvol.manage',
        'view-community-volunteer-reports' => 'cmtyvol.manage',
        'register-visitors' => 'visitors.register',
        'export-visitors' => 'visitors.export',
    ],
];

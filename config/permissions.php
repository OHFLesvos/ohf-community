<?php

return [
    'keys' => [
        'app.usermgmt.view' => [
            'label' => 'permissions.view_usermgmt',
        ],
        'app.usermgmt.users.manage' => [
            'label' => 'permissions.usermgmt_manage_users',
        ],
        'app.usermgmt.roles.manage' => [
            'label' => 'permissions.usermgmt_manage_roles',
        ],
        'app.settings.common.configure' => [
            'label' => 'permissions.configure_common_settings',
        ],
        'badges.create' => [
            'label' => 'permissions.create_badges',
        ],
        'fundraising.donors_donations.view' => [
            'label' => 'permissions.view_fundraising_donors_donations',
        ],
        'fundraising.donors_donations.manage' => [
            'label' => 'permissions.manage_fundraising_donors_donations',
        ],
        'fundraising.reports.view' => [
            'label' => 'permissions.view_fundraising_reports',
        ],
        'fundraising.donations.accept_webhooks' => [
            'label' => 'permissions.accept_fundraising_donations_webhooks',
        ],
        'accounting.transactions.view' => [
            'label' => 'permissions.view_transactions',
        ],
        'accounting.transactions.create' => [
            'label' => 'permissions.create_transactions',
        ],
        'accounting.transactions.update' => [
            'label' => 'permissions.update_transactions',
        ],
        'accounting.transactions.delete' => [
            'label' => 'permissions.delete_transactions',
        ],
        'accounting.transactions.book_externally' => [
            'label' => 'permissions.book_externally',
        ],
        'accounting.summary.view' => [
            'label' => 'permissions.view_summary',
        ],
        'accounting.suppliers.manage' => [
            'label' => 'permissions.manage_suppliers',
        ],
        'accounting.configure' => [
            'label' => 'permissions.configure_accounting',
        ],
        'wiki.view' => [
            'label' => 'permissions.view_wiki',
        ],
        'wiki.edit' => [
            'label' => 'permissions.edit_wiki',
        ],
        'wiki.delete' => [
            'label' => 'permissions.delete_wiki',
        ],
        'cmtyvol.view' => [
            'label' => 'permissions.view_community_volunteers',
        ],
        'cmtyvol.manage' => [
            'label' => 'permissions.manage_community_volunteers',
        ],
        'visitors.register' => [
            'label' => 'permissions.register_visitors',
        ],
        'visitors.export' => [
            'label' => 'permissions.export_visitors',
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
        'configure-accounting' => 'accounting.configure',
        'manage-community-volunteers' => 'cmtyvol.manage',
        'view-community-volunteer-reports' => 'cmtyvol.manage',
        'register-visitors' => 'visitors.register',
        'export-visitors' => 'visitors.export',
    ],
];

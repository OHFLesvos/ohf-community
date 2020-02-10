<?php

namespace App\Providers;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\User::class                                  => \App\Policies\UserPolicy::class,
        \App\Role::class                                  => \App\Policies\RolePolicy::class,
        \App\Tag::class                                   => \App\Policies\TagPolicy::class,
        \App\Models\Fundraising\Donor::class              => \App\Policies\Fundraising\DonorPolicy::class,
        \App\Models\Fundraising\Donation::class           => \App\Policies\Fundraising\DonationPolicy::class,
        \App\Models\Accounting\MoneyTransaction::class    => \App\Policies\Accounting\MoneyTransactionPolicy::class,
        \App\Models\Collaboration\CalendarEvent::class    => \App\Policies\Collaboration\CalendarEventPolicy::class,
        \App\Models\Collaboration\CalendarResource::class => \App\Policies\Collaboration\ResourcePolicy::class,
        \App\Models\Collaboration\Task::class             => \App\Policies\Collaboration\TaskPolicy::class,
        \App\Models\Collaboration\WikiArticle::class      => \App\Policies\Collaboration\ArticlePolicy::class,
        \App\Models\People\Person::class                  => \App\Policies\People\PersonPolicy::class,
        \App\Models\Bank\CouponType::class                => \App\Policies\Bank\CouponTypePolicy::class,
        \App\Models\Helpers\Helper::class                 => \App\Policies\Helpers\HelperPolicy::class,
        \App\Models\Helpers\Responsibility::class         => \App\Policies\Helpers\ResponsibilityPolicy::class,
        \App\Models\Library\LibraryBook::class            => \App\Policies\Library\LibraryBookPolicy::class,
        \App\Models\Library\LibraryLending::class         => \App\Policies\Library\LibraryLendingPolicy::class,
    ];

    protected $permissions = [
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

        'app.changelogs.view' => [
            'label' => 'permissions.view_changelogs',
            'sensitive' => false,
        ],

        'app.logs.view' => [
            'label' => 'permissions.view_logs',
            'sensitive' => true,
        ],

        'badges.create' => [
            'label' => 'permissions.create_badges',
            'sensitive' => false,
        ],

        'fundraising.donors.view' => [
            'label' => 'permissions.view_fundraising_donors',
            'sensitive' => true,
        ],
        'fundraising.donors.manage' => [
            'label' => 'permissions.manage_fundraising_donors',
            'sensitive' => true,
        ],
        'fundraising.donations.view' => [
            'label' => 'permissions.view_fundraising_donations',
            'sensitive' => true,
        ],
        'fundraising.donations.register' => [
            'label' => 'permissions.register_fundraising_donations',
            'sensitive' => true,
        ],
        'fundraising.donations.edit' => [
            'label' => 'permissions.edit_fundraising_donations',
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
            'label' => 'permissions.configure',
            'sensitive' => false,
        ],

        'calendar.events.view' => [
            'label' => 'permissions.view_calendar_events',
            'sensitive' => false,
        ],
        'calendar.events.create' => [
            'label' => 'permissions.create_calendar_events',
            'sensitive' => false,
        ],
        'calendar.events.manage' => [
            'label' => 'permissions.manage_calendar_events',
            'sensitive' => false,
        ],
        'calendar.resources.manage' => [
            'label' => 'permissions.manage_calendar_resources',
            'sensitive' => false,
        ],
        'tasks.use' => [
            'label' => 'permissions.use_tasks',
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
        'bank.deposits.do' => [
            'label' => 'permissions.do_bank_deposits',
            'sensitive' => false,
        ],
        'bank.statistics.view' => [
            'label' => 'permissions.view_bank_statistics',
            'sensitive' => false,
        ],
        'bank.configure' => [
            'label' => 'permissions.configure_bank',
            'sensitive' => false,
        ],

        'people.helpers.view' => [
            'label' => 'permissions.view_helpers',
            'sensitive' => true,
        ],
        'people.helpers.manage' => [
            'label' => 'permissions.manage_helpers',
            'sensitive' => true,
        ],
        'people.helpers.casework.view' => [
            'label' => 'permissions.view_helpers_casework',
            'sensitive' => true,
        ],
        'people.helpers.casework.manage' => [
            'label' => 'permissions.manage_helpers_casework',
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
    ];

    protected $permission_gate_mappings = [
        'view-reports'                => ['people.reports.view', 'bank.statistics.view', 'app.usermgmt.view'], // TODO

        'view-usermgmt-reports'       => 'app.usermgmt.view',

        'view-changelogs'             => 'app.changelogs.view',

        'view-logs'                   => 'app.logs.view',

        'create-badges'               => 'badges.create',

        'accept-fundraising-webhooks' => 'fundraising.donations.accept_webhooks',

        'view-accounting-summary'     => 'accounting.summary.view',
        'book-accounting-transactions-externally'=> 'accounting.transactions.book_externally',
        'configure-accounting'        => 'accounting.configure',

        'view-calendar'               => 'calendar.events.view',

        'manage-people'               => 'people.manage',
        'view-people-reports'         => 'people.reports.view',

        'view-bank-index'             => ['bank.withdrawals.do', 'bank.deposits.do', 'bank.configure'],
        'do-bank-withdrawals'         => 'bank.withdrawals.do',
        'do-bank-deposits'            => 'bank.deposits.do',
        'view-bank-reports'           => 'bank.statistics.view',
        'configure-bank'              => 'bank.configure',

        'manage-helpers'              => 'people.helpers.manage',

        'operate-library'             => 'library.operate',
        'configure-library'           => 'library.configure',
    ];

    protected $permission_gate_mappings_no_super_admin = [
        'view-helpers-casework'       => 'people.helpers.casework.view',
        'manage-helpers-casework'     => 'people.helpers.casework.manage',
    ];
}

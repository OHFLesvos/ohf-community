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
        \App\User::class => \App\Policies\UserPolicy::class,
        \App\Role::class => \App\Policies\RolePolicy::class,
        \App\Person::class => \App\Policies\PersonPolicy::class,
        \App\CouponType::class => \App\Policies\People\Bank\CouponTypePolicy::class,
    ];

    protected $permissions = [
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
        'app.changelogs.view' => [
            'label' => 'permissions.view_changelogs',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'manage-people' => 'people.manage',
        'view-bank-index' => ['bank.withdrawals.do', 'bank.deposits.do', 'bank.configure'],
        'do-bank-withdrawals' => 'bank.withdrawals.do',
        'do-bank-deposits' => 'bank.deposits.do',
        'view-bank-reports' => 'bank.statistics.view',
        'view-people-reports' => 'people.reports.view',
        'view-reports' => ['people.reports.view', 'bank.statistics.view', 'app.usermgmt.view'],
        'configure-bank' => 'bank.configure',
        'view-changelogs' => 'app.changelogs.view',
    ];

}

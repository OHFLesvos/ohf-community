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
        \App\Task::class => \App\Policies\TaskPolicy::class,
        \App\CalendarEvent::class => \App\Policies\CalendarEventPolicy::class,
        \App\CalendarResource::class => \App\Policies\Calendar\ResourcePolicy::class,
        \App\Donor::class => \App\Policies\Fundraising\DonorPolicy::class,
        \App\Donation::class => \App\Policies\Fundraising\DonationPolicy::class,
        \App\CouponType::class => \App\Policies\People\Bank\CouponTypePolicy::class,
        \App\WikiArticle::class => \App\Policies\Wiki\ArticlePolicy::class,
        \App\InventoryItemTransaction::class => \App\Policies\Inventory\ItemTransactionPolicy::class,
        \App\InventoryStorage::class => \App\Policies\Inventory\StoragePolicy::class,
        \App\Helper::class => \App\Policies\People\HelperPolicy::class,
        \App\LibraryBook::class => \App\Policies\Library\LibraryBookPolicy::class,
        \App\LibraryLending::class => \App\Policies\Library\LibraryLendingPolicy::class,
    ];

    protected $permission_gate_mappings = [
        'manage-people' => 'people.manage',
        'manage-helpers' => 'people.helpers.manage',
        'create-badges' => 'badges.create',
        'view-bank-index' => ['bank.withdrawals.do', 'bank.deposits.do', 'bank.configure'],
        'do-bank-withdrawals' => 'bank.withdrawals.do',
        'do-bank-deposits' => 'bank.deposits.do',
        'validate-shop-coupons' => 'shop.coupons.validate',
        'configure-shop' => 'shop.configure',
        'view-barber-list' => 'shop.barber.list.view',
        'configure-barber-list' => 'shop.barber.list.configure',
        'operate-library' => 'library.operate',
        'configure-library' => 'library.configure',
        'view-bank-reports' => 'bank.statistics.view',
        'view-people-reports' => 'people.reports.view',
        'view-reports' => ['people.reports.view', 'bank.statistics.view', 'kitchen.reports.view', 'app.usermgmt.view'],
        'view-usermgmt-reports' => 'app.usermgmt.view',
        'configure-bank' => 'bank.configure',
        'use-logistics' => 'logistics.use',
        'accept-fundraising-webhooks' => 'fundraising.donations.accept_webhooks',
        'view-kitchen-reports' => 'kitchen.reports.view',
        'view-calendar' => 'calendar.events.view',
        'view-changelogs' => 'app.changelogs.view',
        'view-logs' => 'app.logs.view',
    ];
   
    protected $permission_gate_mappings_no_super_admin = [
        'view-helpers-casework' => 'people.helpers.casework.view',
        'manage-helpers-casework' => 'people.helpers.casework.manage',
    ];

}

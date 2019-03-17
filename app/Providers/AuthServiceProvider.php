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
        \App\CouponType::class => \App\Policies\People\Bank\CouponTypePolicy::class,
        \App\WikiArticle::class => \App\Policies\Wiki\ArticlePolicy::class,
        \App\InventoryItemTransaction::class => \App\Policies\Inventory\ItemTransactionPolicy::class,
        \App\InventoryStorage::class => \App\Policies\Inventory\StoragePolicy::class,
        \App\Helper::class => \App\Policies\People\HelperPolicy::class,
        \App\LibraryBook::class => \App\Policies\Library\LibraryBookPolicy::class,
        \App\LibraryLending::class => \App\Policies\Library\LibraryLendingPolicy::class,
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
        'shop.coupons.validate' => [
            'label' => 'permissions.validate_shop_coupons',
            'sensitive' => true,
        ],
        'shop.configure' => [
            'label' => 'permissions.configure_shop',
            'sensitive' => false,
        ],
        'shop.barber.list.view' => [
            'label' => 'permissions.view_barber_shop_list',
            'sensitive' => true,
        ],
        'shop.barber.list.configure' => [
            'label' => 'permissions.configure_barber_shop_list',
            'sensitive' => false,
        ],
        'library.operate' => [
            'label' => 'permissions.operate_library',
            'sensitive' => true,
        ],
        'library.configure' => [
            'label' => 'permissions.configure_library',
            'sensitive' => true,
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
        'badges.create' => [
            'label' => 'permissions.create_badges',
            'sensitive' => false,
        ],
        'logistics.use' => [
            'label' => 'permissions.use_logistics',
            'sensitive' => false,
        ],
        'tasks.use' => [
            'label' => 'permissions.use_tasks',
            'sensitive' => false,
        ],
        'kitchen.reports.view' => [
            'label' => 'permissions.view_kitchen_reports',
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
        'inventory.storage.view' => [
            'label' => 'permissions.view_inventory_storage',
            'sensitive' => false,
        ],
        'inventory.storage.manage' => [
            'label' => 'permissions.manage_inventory_storage',
            'sensitive' => false,
        ],
        'inventory.transactions.create' => [
            'label' => 'permissions.create_inventory_transactions',
            'sensitive' => false,
        ],
        'inventory.transactions.delete' => [
            'label' => 'permissions.delete_inventory_transactions',
            'sensitive' => false,
        ],
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

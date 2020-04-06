<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
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

    protected $permission_gate_mappings = [
        'view-reports'                => ['people.reports.view', 'bank.statistics.view', 'app.usermgmt.view'], // TODO

        'view-usermgmt-reports'       => 'app.usermgmt.view',

        'configure-common-settings'   => 'app.settings.common.configure',

        'view-changelogs'             => 'app.changelogs.view',

        'create-badges'               => 'badges.create',

        'accept-fundraising-webhooks' => 'fundraising.donations.accept_webhooks',

        'view-accounting-summary'     => 'accounting.summary.view',
        'book-accounting-transactions-externally' => 'accounting.transactions.book_externally',
        'configure-accounting'        => 'accounting.configure',

        'view-calendar'               => 'calendar.events.view',

        'manage-people'               => 'people.manage',
        'view-people-reports'         => 'people.reports.view',

        'view-bank-index'             => ['bank.withdrawals.do', 'bank.configure'],
        'do-bank-withdrawals'         => 'bank.withdrawals.do',
        'view-bank-reports'           => 'bank.statistics.view',
        'configure-bank'              => 'bank.configure',

        'manage-helpers'              => 'people.helpers.manage',

        'operate-library'             => 'library.operate',
        'configure-library'           => 'library.configure',

        'validate-shop-coupons'       => 'shop.coupons.validate',
        'configure-shop'              => 'shop.configure',
    ];

    protected $permission_gate_mappings_no_super_admin = [
        'view-helpers-casework'       => 'people.helpers.casework.view',
        'manage-helpers-casework'     => 'people.helpers.casework.manage',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissionGateMappings();
        $this->registerPErmissionGateMappingsNoSuperAdmin();
    }

    protected function registerPermissionGateMappings() {
        foreach ($this->permission_gate_mappings as $gate => $permission) {
            Gate::define($gate, function ($user) use ($permission) {
                if ($user->isSuperAdmin()) {
                    return true;
                }
                if (is_array($permission)) {
                    $hasPermission = false;
                    foreach ($permission as $pe) {
                        $hasPermission |= $user->hasPermission($pe);
                    }
                    return $hasPermission;
                }
                return $user->hasPermission($permission);
            });
        }
    }

    protected function registerPErmissionGateMappingsNoSuperAdmin() {
        foreach ($this->permission_gate_mappings_no_super_admin as $gate => $permission) {
            Gate::define($gate, function ($user) use ($permission) {
                if (is_array($permission)) {
                    $hasPermission = false;
                    foreach ($permission as $pe) {
                        $hasPermission |= $user->hasPermission($pe);
                    }
                    return $hasPermission;
                }
                return $user->hasPermission($permission);
            });
        }
    }
}

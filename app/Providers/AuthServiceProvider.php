<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User' => 'App\Policies\UserPolicy',
        'App\Role' => 'App\Policies\RolePolicy',
        'App\Person' => 'App\Policies\PersonPolicy',
        'App\Task' => 'App\Policies\TaskPolicy',
        'App\CalendarEvent' => 'App\Policies\CalendarEventPolicy',
        \App\CalendarResource::class => \App\Policies\Calendar\ResourcePolicy::class,
        \App\Donor::class => \App\Policies\Fundraising\DonorPolicy::class,
        \App\Donation::class => \App\Policies\Fundraising\DonationPolicy::class,
        \App\CouponType::class => \App\Policies\People\Bank\CouponTypePolicy::class,
        \App\WikiArticle::class => \App\Policies\Wiki\ArticlePolicy::class,
        \App\MoneyTransaction::class => \App\Policies\Accounting\MoneyTransactionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $simple_permission_gate_mappings = [
            'view-bank-index' => ['bank.withdrawals.do', 'bank.deposits.do', 'bank.configure'],
            'do-bank-withdrawals' => 'bank.withdrawals.do',
            'do-bank-deposits' => 'bank.deposits.do',
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
        foreach ($simple_permission_gate_mappings as $gate => $permission) {
            Gate::define($gate, function ($user) use($permission) {
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
}

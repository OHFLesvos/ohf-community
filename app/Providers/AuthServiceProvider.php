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
        \App\Models\User::class                           => \App\Policies\UserPolicy::class,
        \App\Models\Role::class                           => \App\Policies\RolePolicy::class,
        \App\Models\Tag::class                            => \App\Policies\TagPolicy::class,
        \App\Models\Comment::class                        => \App\Policies\CommentPolicy::class,
        \App\Models\Fundraising\Donor::class              => \App\Policies\Fundraising\DonorPolicy::class,
        \App\Models\Fundraising\Donation::class           => \App\Policies\Fundraising\DonationPolicy::class,
        \App\Models\Accounting\MoneyTransaction::class    => \App\Policies\Accounting\MoneyTransactionPolicy::class,
        \App\Models\Accounting\Wallet::class              => \App\Policies\Accounting\WalletPolicy::class,
        \App\Models\Accounting\Supplier::class            => \App\Policies\Accounting\SupplierPolicy::class,
        \App\Models\Collaboration\WikiArticle::class      => \App\Policies\Collaboration\ArticlePolicy::class,
        \App\Models\People\Person::class                  => \App\Policies\People\PersonPolicy::class,
        \App\Models\Bank\CouponType::class                => \App\Policies\Bank\CouponTypePolicy::class,
        \App\Models\CommunityVolunteers\CommunityVolunteer::class => \App\Policies\CommunityVolunteers\CommunityVolunteerPolicy::class,
        \App\Models\CommunityVolunteers\Responsibility::class => \App\Policies\CommunityVolunteers\ResponsibilityPolicy::class,
        \App\Models\Library\LibraryBook::class            => \App\Policies\Library\LibraryBookPolicy::class,
        \App\Models\Library\LibraryLending::class         => \App\Policies\Library\LibraryLendingPolicy::class,
        \App\Models\Visitors\Visitor::class               => \App\Policies\Visitors\VisitorPolicy::class,
    ];

    protected $permission_gate_mappings = [
        'view-reports'                => ['people.reports.view', 'bank.statistics.view', 'app.usermgmt.view'], // TODO

        'view-usermgmt-reports'       => 'app.usermgmt.view',

        'configure-common-settings'   => 'app.settings.commonbr.configure',

        'view-changelogs'             => 'app.changelogs.view',

        'create-badges'               => 'badges.create',

        'view-fundraising'            => ['fundraising.donors_donations.view', 'fundraising.reports.view'],
        'view-fundraising-entities'   => 'fundraising.donors_donations.view',
        'manage-fundraising-entities' => 'fundraising.donors_donations.manage',
        'view-fundraising-reports'    => 'fundraising.reports.view',
        'accept-fundraising-webhooks' => 'fundraising.donations.accept_webhooks',

        'view-accounting-summary'     => 'accounting.summary.view',
        'book-accounting-transactions-externally' => 'accounting.transactions.book_externally',
        'manage-suppliers'            => 'accounting.suppliers.manage',
        'configure-accounting'        => 'accounting.configure',

        'manage-people'               => 'people.manage',
        'view-people-reports'         => 'people.reports.view',

        'view-bank-index'             => ['bank.withdrawals.do', 'bank.configure'],
        'do-bank-withdrawals'         => 'bank.withdrawals.do',
        'view-bank-reports'           => 'bank.statistics.view',
        'configure-bank'              => 'bank.configure',

        'manage-community-volunteers' => 'cmtyvol.manage',

        'operate-library'             => 'library.operate',
        'configure-library'           => 'library.configure',

        'validate-shop-coupons'       => 'shop.coupons.validate',
        'configure-shop'              => 'shop.configure',

        'register-visitors'           => 'visitors.register',
        'export-visitors'           => 'visitors.export',
    ];

    protected $permission_gate_mappings_no_super_admin = [ ];

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

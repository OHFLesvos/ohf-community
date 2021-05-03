<?php

namespace App\Providers;

use App\Models\User;
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
        \App\Models\Accounting\Category::class            => \App\Policies\Accounting\CategoryPolicy::class,
        \App\Models\Accounting\Project::class             => \App\Policies\Accounting\ProjectPolicy::class,
        \App\Models\Accounting\Supplier::class            => \App\Policies\Accounting\SupplierPolicy::class,
        \App\Models\Collaboration\WikiArticle::class      => \App\Policies\Collaboration\ArticlePolicy::class,
        \App\Models\CommunityVolunteers\CommunityVolunteer::class => \App\Policies\CommunityVolunteers\CommunityVolunteerPolicy::class,
        \App\Models\CommunityVolunteers\Responsibility::class => \App\Policies\CommunityVolunteers\ResponsibilityPolicy::class,
        \App\Models\Visitors\Visitor::class               => \App\Policies\Visitors\VisitorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // TODO: Skipped for now as it ignores any invalid policies
        // $this->registerSuperAdminAccess();
        $this->registerPermissionGateMappings();
    }

    // protected function registerSuperAdminAccess()
    // {
    //     Gate::before(function (User $user) {
    //         if ($user->isSuperAdmin()) {
    //             return true;
    //         }
    //     });
    // }

    protected function registerPermissionGateMappings()
    {
        $mapping = config('permissions.gate_mapping');
        if (is_array($mapping)) {
            foreach ($mapping as $gate => $permission) {
                Gate::define($gate, function (User $user) use ($permission) {
                    if ($user->isSuperAdmin()) {
                        return true;
                    }
                    if (is_array($permission)) {
                        foreach ($permission as $pe) {
                            if ($user->hasPermission($pe)) {
                                return true;
                            }
                        }
                        return false;
                    }
                    return $user->hasPermission($permission);
                });
            }
        }
    }
}

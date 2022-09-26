<?php

namespace App\Providers;

use App\Events\UserSelfRegistered;
use App\Listeners\PromoteToSuperAdminIfUndefined;
use App\Listeners\SendUserRegisteredNotification;
use App\Models\Accounting\Transaction;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Observers\Accounting\TransactionObserver;
use App\Observers\CommunityVolunteers\CommunityVolunteerObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserSelfRegistered::class => [
            SendUserRegisteredNotification::class,
            PromoteToSuperAdminIfUndefined::class,
        ],
    ];

    protected $observers = [
        Transaction::class => [TransactionObserver::class],
        CommunityVolunteer::class => [CommunityVolunteerObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}

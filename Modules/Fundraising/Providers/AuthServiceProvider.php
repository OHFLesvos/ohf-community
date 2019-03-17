<?php

namespace Modules\Fundraising\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Fundraising\Entities\Donor::class => \Modules\Fundraising\Policies\DonorPolicy::class,
        \Modules\Fundraising\Entities\Donation::class => \Modules\Fundraising\Policies\DonationPolicy::class,
    ];

    protected $permissions = [
        'fundraising.donors.view' => [
            'label' => 'fundraising::permissions.view_fundraising_donors',
            'sensitive' => true,
        ],
        'fundraising.donors.manage' => [
            'label' => 'fundraising::permissions.manage_fundraising_donors',
            'sensitive' => true,
        ],
        'fundraising.donations.view' => [
            'label' => 'fundraising::permissions.view_fundraising_donations',
            'sensitive' => true,
        ],
        'fundraising.donations.register' => [
            'label' => 'fundraising::permissions.register_fundraising_donations',
            'sensitive' => true,
        ],
        'fundraising.donations.edit' => [
            'label' => 'fundraising::permissions.edit_fundraising_donations',
            'sensitive' => true,
        ],
        'fundraising.donations.accept_webhooks' => [
            'label' => 'fundraising::permissions.accept_fundraising_donations_webhooks',
            'sensitive' => false,
        ],        
    ];

    protected $permission_gate_mappings = [
        'accept-fundraising-webhooks' => 'fundraising.donations.accept_webhooks',
    ];

}

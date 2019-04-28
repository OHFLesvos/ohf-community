<?php

namespace Modules\Logistics\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Logistics\Entities\Product::class => \Modules\Logistics\Policies\ProductPolicy::class,
        \Modules\Logistics\Entities\Supplier::class => \Modules\Logistics\Policies\SupplierPolicy::class,
        \Modules\Logistics\Entities\Offer::class => \Modules\Logistics\Policies\OfferPolicy::class,
    ];

    protected $permissions = [
        'logistics.suppliers.view' => [
            'label' => 'logistics::permissions.view_suppliers',
            'sensitive' => false,
        ],
        'logistics.suppliers.manage' => [
            'label' => 'logistics::permissions.manage_suppliers',
            'sensitive' => false,
        ],
        'logistics.products.view' => [
            'label' => 'logistics::permissions.view_products',
            'sensitive' => false,
        ],
        'logistics.products.manage' => [
            'label' => 'logistics::permissions.manage_products',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
    ];
}

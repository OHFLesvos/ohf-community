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
    ];

    protected $permissions = [

    ];

    protected $permission_gate_mappings = [
    ];
}

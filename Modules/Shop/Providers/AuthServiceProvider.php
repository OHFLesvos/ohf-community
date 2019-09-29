<?php

namespace Modules\Shop\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    protected $permissions = [
        'shop.coupons.validate' => [
            'label' => 'shop::permissions.validate_shop_coupons',
            'sensitive' => true,
        ],
        'shop.configure' => [
            'label' => 'shop::permissions.configure_shop',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'validate-shop-coupons' => 'shop.coupons.validate',
        'configure-shop' => 'shop.configure',
    ];

}

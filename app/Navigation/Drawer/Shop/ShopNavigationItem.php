<?php

namespace App\Navigation\Drawer\Shop;

use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Gate;

class ShopNavigationItem extends BaseNavigationItem
{
    protected $route = 'shop.index';

    protected $caption = 'shop.shop';

    protected $icon = 'shopping-bag';

    protected $active = 'shop*';

    public function isAuthorized(): bool
    {
        return Gate::allows('validate-shop-coupons');
    }
}

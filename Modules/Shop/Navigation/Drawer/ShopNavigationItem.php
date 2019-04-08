<?php

namespace Modules\Shop\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class ShopNavigationItem extends BaseNavigationItem {

    protected $route = 'shop.index';

    protected $caption = 'shop::shop.shop';

    protected $icon = 'shopping-bag';

    protected $active = 'shop*';

    public function isAuthorized(): bool
    {
        return Gate::allows('validate-shop-coupons');
    }

}

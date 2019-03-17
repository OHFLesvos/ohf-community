<?php

namespace Modules\Fundraising\Providers;

use App\Providers\RegistersNavigationItems;
use App\Providers\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Fundraising\Navigation\Drawer\FundraisingNavigationItem::class => 5,
    ];

    protected $contextButtons = [
        'fundraising.donors.index'     => \Modules\Fundraising\Navigation\ContextButtons\DonorIndexContextButtons::class,
        'fundraising.donors.create'    => \Modules\Fundraising\Navigation\ContextButtons\DonorCreateContextButtons::class,
        'fundraising.donors.show'      => \Modules\Fundraising\Navigation\ContextButtons\DonorShowContextButtons::class,
        'fundraising.donors.edit'      => \Modules\Fundraising\Navigation\ContextButtons\DonorEditContextButtons::class,
        'fundraising.donations.index'  => \Modules\Fundraising\Navigation\ContextButtons\DonationIndexContextButtons::class,
        'fundraising.donations.import' => \Modules\Fundraising\Navigation\ContextButtons\DonationImportContextButtons::class,
        'fundraising.donations.create' => \Modules\Fundraising\Navigation\ContextButtons\DonationCreateContextButtons::class,
        'fundraising.donations.edit'   => \Modules\Fundraising\Navigation\ContextButtons\DonationEditContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextButtons();
    }

}

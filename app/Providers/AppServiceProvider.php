<?php

namespace App\Providers;

use App\Rules\CountryCode;
use App\Rules\CountryName;
use App\Rules\Isbn;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AppServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegistersDashboardWidgets, RegisterContextMenus, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class => 0,
        \App\Navigation\Drawer\PeopleNavigationItem::class => 1,
        \App\Navigation\Drawer\BankNavigationItem::class => 2,
        \App\Navigation\Drawer\HelpersNavigationItem::class => 3,
        \App\Navigation\Drawer\LogisticsNavigationItem::class => 4,
        \App\Navigation\Drawer\FundraisingNavigationItem::class => 5,
        \App\Navigation\Drawer\WikiArticlesItem::class => 6,
        \App\Navigation\Drawer\InventoryStorageNavigationItem::class => 7,
        \App\Navigation\Drawer\ShopNavigationItem::class => 8,
        \App\Navigation\Drawer\BarberNavigationItem::class => 9,
        \App\Navigation\Drawer\LibraryNavigationItem::class => 10,
        \App\Navigation\Drawer\CalendarNavigationItem::class => 11,
        \App\Navigation\Drawer\TasksNavigationItem::class => 12,
        \App\Navigation\Drawer\BadgesNavigationItem::class => 13,
        \App\Navigation\Drawer\ReportingNavigationItem::class => 14,
        \App\Navigation\Drawer\UsersNavigationItem::class => 15,
        \App\Navigation\Drawer\LogViewerNavigationItem::class => 16,
    ];

    protected $contextMenus = [
        \App\Navigation\ContextMenu\PeopleContextMenu::class,
        \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
        \App\Navigation\ContextMenu\HelpersContextMenu::class,
    ];

    protected $dashboardWidgets = [
        \App\Widgets\BankWidget::class => 0,
        \App\Widgets\PersonsWidget::class  => 1,
        \App\Widgets\ShopWidget::class => 2,
        \App\Widgets\BarberShopWidget::class => 3,
        \App\Widgets\LibraryWidget::class => 4,
        \App\Widgets\HelpersWidget::class => 5,
        \App\Widgets\WikiArticlesWidget::class => 6,
        \App\Widgets\InventoryWidget::class => 7,
        \App\Widgets\DonorsWidget::class => 8,
        \App\Widgets\ReportingWidget::class => 9,
        \App\Widgets\ToolsWidget::class => 10,
        \App\Widgets\UsersWidget::class => 11,
        \App\Widgets\ChangeLogWidget::class => 12,
    ];

    protected $contextButtons = [
        'changelog' => \App\Navigation\ContextButtons\ChangelogContextButtons::class,

        'userprofile.view2FA' => \App\Navigation\ContextButtons\UserProfile2FAContextButtons::class,

        'users.index' => \App\Navigation\ContextButtons\UserIndexContextButtons::class,
        'users.create' => \App\Navigation\ContextButtons\UserCreateContextButtons::class,
        'users.show' => \App\Navigation\ContextButtons\UserShowContextButtons::class,
        'users.edit' => \App\Navigation\ContextButtons\UserEditContextButtons::class,
        'users.permissions' => \App\Navigation\ContextButtons\UserPermissionsContextButtons::class,

        'roles.index' => \App\Navigation\ContextButtons\RoleIndexContextButtons::class,
        'roles.create' => \App\Navigation\ContextButtons\RoleCreateContextButtons::class,
        'roles.show' => \App\Navigation\ContextButtons\RoleShowContextButtons::class,
        'roles.edit' => \App\Navigation\ContextButtons\RoleEditContextButtons::class,
        'roles.permissions' => \App\Navigation\ContextButtons\RolePermissionsContextButtons::class,

        'people.index' => \App\Navigation\ContextButtons\PeopleIndexContextButtons::class,
        'people.create' => \App\Navigation\ContextButtons\PeopleCreateContextButtons::class,
        'people.show' => \App\Navigation\ContextButtons\PeopleShowContextButtons::class,
        'people.relations' => \App\Navigation\ContextButtons\PeopleRelationsContextButtons::class,
        'people.edit' => \App\Navigation\ContextButtons\PeopleEditContextButtons::class,
        'people.duplicates' => \App\Navigation\ContextButtons\PeopleDuplicatesContextButtons::class,
        'people.import' => \App\Navigation\ContextButtons\PeopleImportContextButtons::class,
        
        'people.helpers.index' => \App\Navigation\ContextButtons\HelperIndexContextButtons::class,
        'people.helpers.show' => \App\Navigation\ContextButtons\HelperShowContextButtons::class,
        'people.helpers.edit' => \App\Navigation\ContextButtons\HelpersEditContextButtons::class,
        'people.helpers.create' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.createFrom' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.import' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.export' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.report' => \App\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        
        'bank.withdrawal' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.withdrawalSearch' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.showCard' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.deposit' => \App\Navigation\ContextButtons\BankDepositContextButtons::class,
        'bank.prepareCodeCard' => \App\Navigation\ContextButtons\BankCodeCardContextButtons::class,
        'bank.settings.edit' => \App\Navigation\ContextButtons\BankSettingsContextButtons::class,
        'bank.withdrawalTransactions' => \App\Navigation\ContextButtons\BankWithdrawalTransactionsContextButtons::class,
        'bank.depositTransactions' => \App\Navigation\ContextButtons\BankDepositTransactionsContextButtons::class,
        'bank.maintenance' => \App\Navigation\ContextButtons\BankMaintenanceContextButtons::class,
        'bank.export' => \App\Navigation\ContextButtons\BankExportContextButtons::class,

        'coupons.index' => \App\Navigation\ContextButtons\CouponIndexContextButtons::class,
        'coupons.create' => \App\Navigation\ContextButtons\CouponCreateContextButtons::class,
        'coupons.show' => \App\Navigation\ContextButtons\CouponShowContextButtons::class,
        'coupons.edit' => \App\Navigation\ContextButtons\CouponEditContextButtons::class,
        
        'shop.index' => \App\Navigation\ContextButtons\ShopContextButtons::class,
        'shop.settings.edit' => \App\Navigation\ContextButtons\ShopSettingsContextButtons::class,

        'shop.barber.index' => \App\Navigation\ContextButtons\BarberContextButtons::class,
        'shop.barber.settings.edit' => \App\Navigation\ContextButtons\BarberSettingsContextButtons::class,
        
        'library.lending.index' => \App\Navigation\ContextButtons\LibraryLendingIndexContextButtons::class,
        'library.settings.edit' => \App\Navigation\ContextButtons\LibrarySettingsContextButtons::class,
        'library.lending.persons' => \App\Navigation\ContextButtons\LibraryReturnToIndexContextButtons::class,
        'library.lending.books' => \App\Navigation\ContextButtons\LibraryReturnToIndexContextButtons::class,
        'library.lending.person' => \App\Navigation\ContextButtons\LibraryLendingPersonContextButtons::class,
        'library.lending.personLog' => \App\Navigation\ContextButtons\LibraryLendingPersonLogContextButtons::class,
        'library.lending.book' => \App\Navigation\ContextButtons\LibraryLendingBookContextButtons::class,
        'library.lending.bookLog' => \App\Navigation\ContextButtons\LibraryLendingBookLogContextButtons::class,
        'library.books.index' => \App\Navigation\ContextButtons\LibraryBookIndexContextButtons::class,
        'library.books.create' => \App\Navigation\ContextButtons\LibraryBookCreateContextButtons::class,
        'library.books.edit' => \App\Navigation\ContextButtons\LibraryBookEditContextButtons::class,

        'logistics.articles.index' => \App\Navigation\ContextButtons\LogisticsArticleIndexContextButtons::class,
        'logistics.articles.edit' => \App\Navigation\ContextButtons\LogisticsArticleEditContextButtons::class,
        
        'fundraising.donors.index' => \App\Navigation\ContextButtons\FundraisingDonorIndexContextButtons::class,
        'fundraising.donors.create' => \App\Navigation\ContextButtons\FundraisingDonorCreateContextButtons::class,
        'fundraising.donors.show' => \App\Navigation\ContextButtons\FundraisingDonorShowContextButtons::class,
        'fundraising.donors.edit' => \App\Navigation\ContextButtons\FundraisingDonorEditContextButtons::class,
        'fundraising.donations.index' => \App\Navigation\ContextButtons\FundraisingDonationIndexContextButtons::class,
        'fundraising.donations.import' => \App\Navigation\ContextButtons\FundraisingDonationImportContextButtons::class,
        'fundraising.donations.create' => \App\Navigation\ContextButtons\FundraisingDonationCreateContextButtons::class,
        'fundraising.donations.edit' => \App\Navigation\ContextButtons\FundraisingDonationEditContextButtons::class,

        'wiki.articles.index' => \App\Navigation\ContextButtons\WikiArticleIndexContextButtons::class,
        'wiki.articles.tag' => \App\Navigation\ContextButtons\WikiArticleReturnToIndexContextButtons::class,
        'wiki.articles.latestChanges' => \App\Navigation\ContextButtons\WikiArticleReturnToIndexContextButtons::class,
        'wiki.articles.create' => \App\Navigation\ContextButtons\WikiArticleCreateContextButtons::class,
        'wiki.articles.show' => \App\Navigation\ContextButtons\WikiArticleShowContextButtons::class,
        'wiki.articles.edit' => \App\Navigation\ContextButtons\WikiArticleEditContextButtons::class,
        
        'inventory.storages.index' => \App\Navigation\ContextButtons\InventoryStorageIndexContextButtons::class,
        'inventory.storages.create' => \App\Navigation\ContextButtons\InventoryStorageCreateContextButtons::class,
        'inventory.storages.show' => \App\Navigation\ContextButtons\InventoryStorageShowContextButtons::class,
        'inventory.storages.edit' => \App\Navigation\ContextButtons\InventoryStorageEditContextButtons::class,
        'inventory.transactions.changes' => \App\Navigation\ContextButtons\InventoryTransactionChangesContextButtons::class,
        'inventory.transactions.ingress' => \App\Navigation\ContextButtons\InventoryReturnToStorageContextButtons::class,
        'inventory.transactions.egress' => \App\Navigation\ContextButtons\InventoryReturnToStorageContextButtons::class,

        'badges.selection' => \App\Navigation\ContextButtons\BadgeSelectionContextButtons::class,
        
        'reporting.monthly-summary' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.people' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.withdrawals' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.deposits' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.privacy' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.articles' => \App\Navigation\ContextButtons\ReportingArticlesContextButtons::class,
        'reporting.article' => \App\Navigation\ContextButtons\ReportingArticleContextButtons::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Database string length
        Schema::defaultStringLength(191);

        // Enforce SSL if running in production
		if (env('APP_ENV') === 'production' || env('APP_ENV') === 'prod') {
			\URL::forceScheme('https');
		}

		// Pagination method for collections
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))
                        ->withPath('');
                });
        }

        // Blade directive for showing a Font Awesome icon
        Blade::directive('icon', function ($name) {
            return '<i class="fa fa-' . $name . '"></i>';
        });

		// Exposes checks agains a specific gate
        Blade::if('allowed', function ($gate) {
            if (is_array($gate)) {
                foreach ($gate as $g) {
                    if (Gate::allows($g)) {
                        return true;
                    }
                }
                return false;
            }
            return Gate::allows($gate);
        });

        // Blade directive to create a link to a telephone number
        Blade::directive('tel', function ($expression) {
            return "<?php echo tel_link($expression); ?>";
        });

        // Blade directive to create a link to a WhatsApp number
        Blade::directive('whatsapp', function ($expression) {
            return "<?php echo whatsapp_link($expression); ?>";
        });

        // Blade directive to create a link to an email address
        Blade::directive('email', function ($expression) {
            return "<?php echo email_link($expression); ?>";
        });

        // Blade directive to create a link to call a skype name
        Blade::directive('skype', function ($expression) {
            return "<?php echo skype_link($expression); ?>";
        });

        Validator::extend('country_code', CountryCode::class);
        Validator::extend('country_name', CountryName::class);
        Validator::extend('isbn', Isbn::class);

        $this->registerNavigationItems();
        $this->registerDashboardWidgets();
        $this->registerContextMenus();
        $this->registerContextButtons();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}

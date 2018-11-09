<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'language'], function () {
    
    Route::group(['middleware' => ['auth']], function () {

        // Home (Dashboard)
        Route::get('/', 'HomeController@index')->name('home');

        // Changelog
        Route::group(['middleware' => ['can:view-changelogs']], function () {
            Route::get('/changelog', 'ChangelogController@index')->name('changelog');
        });

        // Log viewer
        Route::group(['middleware' => ['can:view-logs']], function () {
            Route::get('/logviewer', 'LogViewerController@index')->name('logviewer.index');
        });

        //
        // User and role management
        //
        Route::namespace('Admin')->prefix('admin')->group(function(){
            // Users
            Route::put('users/{user}/disable2FA', 'UserController@disable2FA')->name('users.disable2FA');
            Route::resource('users', 'UserController');

            // Roles
            Route::resource('roles', 'RoleController');

            // Reporting
            Route::group(['middleware' => ['can:view-usermgmt-reports']], function () {    
                Route::get('reporting/users/permissions', 'UserController@permissions')->name('users.permissions');
                Route::get('reporting/users/sensitiveData', 'UserController@sensitiveDataReport')->name('reporting.privacy');
                Route::get('reporting/roles/permissions', 'RoleController@permissions')->name('roles.permissions');
            });
        });

        //
        // User profile
        //
        Route::get('/userprofile', 'UserProfileController@index')->name('userprofile');
        Route::post('/userprofile', 'UserProfileController@update')->name('userprofile.update');
        Route::post('/userprofile/updatePassword', 'UserProfileController@updatePassword')->name('userprofile.updatePassword');
        Route::delete('/userprofile', 'UserProfileController@delete')->name('userprofile.delete');
        Route::get('/userprofile/2FA', 'UserProfileController@view2FA')->name('userprofile.view2FA');
        Route::post('/userprofile/2FA', 'UserProfileController@store2FA')->name('userprofile.store2FA');
        Route::delete('/userprofile/2FA', 'UserProfileController@disable2FA')->name('userprofile.disable2FA');

        //
        // Bank
        //
        Route::get('/bank', function(){
            return redirect()->route('bank.withdrawal');
        })->name('bank.index');

        // Withdrawals
        Route::group(['middleware' => ['can:do-bank-withdrawals']], function () {
            Route::get('/bank/withdrawal', 'People\Bank\WithdrawalController@index')->name('bank.withdrawal');
            Route::get('/bank/withdrawal/search', 'People\Bank\WithdrawalController@search')->name('bank.withdrawalSearch');
            Route::get('/bank/withdrawal/transactions', 'People\Bank\WithdrawalController@transactions')->name('bank.withdrawalTransactions');
            
            Route::get('/bank/withdrawal/cards/{card}', 'People\Bank\WithdrawalController@showCard')->name('bank.showCard');

            Route::get('/bank/codeCard', 'People\Bank\CodeCardController@create')->name('bank.prepareCodeCard');
            Route::post('/bank/codeCard', 'People\Bank\CodeCardController@download')->name('bank.createCodeCard');
        });

        // Deposits
        Route::group(['middleware' => ['can:do-bank-deposits']], function () {
            Route::get('/bank/deposit', 'People\Bank\DepositController@index')->name('bank.deposit');
            Route::post('/bank/deposit', 'People\Bank\DepositController@store')->name('bank.storeDeposit');
            Route::get('/bank/deposit/transactions', 'People\Bank\DepositController@transactions')->name('bank.depositTransactions');
        });

        // Settings
        Route::group(['middleware' => ['can:configure-bank']], function () {
            Route::get('/bank/settings', 'People\Bank\BankSettingsController@edit')->name('bank.settings.edit');
            Route::put('/bank/settings', 'People\Bank\BankSettingsController@update')->name('bank.settings.update');
            Route::resource('/bank/coupons', 'People\Bank\CouponTypesController');
        });

        // Maintenance
        Route::group(['middleware' => ['can:cleanup,App\Person']], function () {
            Route::get('/bank/maintenance', 'People\Bank\MaintenanceController@maintenance')->name('bank.maintenance');
            Route::post('/bank/maintenance', 'People\Bank\MaintenanceController@updateMaintenance')->name('bank.updateMaintenance');
        });

        // Export
        Route::group(['middleware' => ['can:export,App\Person']], function () {
            Route::get('/bank/export', 'People\Bank\ImportExportController@export')->name('bank.export');
            Route::post('/bank/doExport', 'People\Bank\ImportExportController@doExport')->name('bank.doExport');
        });

        // Import
        Route::group(['middleware' => ['can:create,App\Person']], function () {
            Route::get('/bank/import', 'People\Bank\ImportExportController@import')->name('bank.import');
            Route::post('/bank/doImport', 'People\Bank\ImportExportController@doImport')->name('bank.doImport');
        });

        //
        // People
        //
        Route::post('/people/filter', 'PeopleController@filter')->name('people.filter');
        Route::get('/people/export', 'PeopleController@export')->name('people.export');
        Route::get('/people/import', 'PeopleController@import')->name('people.import');
        Route::post('/people/doImport', 'PeopleController@doImport')->name('people.doImport');
        Route::get('/people/{person}/qrcode', 'PeopleController@qrCode')->name('people.qrCode');
        Route::get('/people/{person}/relations', 'PeopleController@relations')->name('people.relations');
        Route::get('/people/filterPersons', 'PeopleController@filterPersons')->name('people.filterPersons');
        Route::post('/people/{person}/relations', 'PeopleController@addRelation')->name('people.addRelation');
        Route::delete('/people/{person}/children/{child}', 'PeopleController@removeChild')->name('people.removeChild');
        Route::delete('/people/{person}/partner', 'PeopleController@removePartner')->name('people.removePartner');
        Route::delete('/people/{person}/mother', 'PeopleController@removeMother')->name('people.removeMother');
        Route::delete('/people/{person}/father', 'PeopleController@removeFather')->name('people.removeFather');
        Route::get('/people/duplicates', 'PeopleController@duplicates')->name('people.duplicates');
        Route::post('/people/duplicates', 'PeopleController@applyDuplicates')->name('people.applyDuplicates');
        Route::post('/people/bulkAction', 'PeopleController@bulkAction')->name('people.bulkAction');
        Route::resource('/people', 'PeopleController');

        // Shop
        Route::namespace('Shop')->prefix('shop')->name('shop.')->middleware(['can:validate-shop-coupons'])->group(function(){
            Route::get('/', 'ShopController@index')->name('index');
            Route::post('/', 'ShopController@redeem')->name('redeem');
            Route::get('/settings', 'ShopSettingsController@edit')->name('settings.edit')->middleware(['can:configure-shop']);
            Route::put('/settings', 'ShopSettingsController@update')->name('settings.update')->middleware(['can:configure-shop']);
        });
        Route::namespace('Shop')->prefix('barber')->name('shop.barber.')->middleware(['can:view-barber-list'])->group(function(){
            Route::get('/', 'BarberShopController@index')->name('index');
            Route::post('/checkin', 'BarberShopController@checkin')->name('checkin');
            Route::post('/addPerson', 'BarberShopController@addPerson')->name('addPerson');
            Route::delete('/removePerson', 'BarberShopController@removePerson')->name('removePerson');
            Route::get('/settings', 'BarberShopSettingsController@edit')->name('settings.edit')->middleware(['can:configure-barber-list']);
            Route::put('/settings', 'BarberShopSettingsController@update')->name('settings.update')->middleware(['can:configure-barber-list']);
        });

        // Helpers
        Route::namespace('People\Helpers')->name('people.')->group(function(){
            Route::get('helpers/export', 'HelperListController@export')->name('helpers.export')->middleware('can:export,App\Helper');
            Route::post('helpers/doExport', 'HelperListController@doExport')->name('helpers.doExport')->middleware('can:export,App\Helper');
            Route::get('helpers/import', 'HelperListController@import')->name('helpers.import')->middleware('can:import,App\Helper');
            Route::post('helpers/doImport', 'HelperListController@doImport')->name('helpers.doImport')->middleware('can:import,App\Helper');
            Route::get('helpers/createFrom', 'HelperListController@createFrom')->name('helpers.createFrom')->middleware('can:create,App\Helper');
            Route::post('helpers/createFrom', 'HelperListController@storeFrom')->name('helpers.storeFrom')->middleware('can:create,App\Helper');
            Route::get('helpers/{helper}/vcard', 'HelperListController@vcard')->name('helpers.vcard');
            Route::get('helpers/{helper}/badge', 'HelperListController@badge')->name('helpers.badge');
            Route::get('helpers/badges', 'HelperListController@badges')->name('helpers.badges')->middleware('can:list,App\Helper');
            Route::get('helpers/filterPersons', 'HelperListController@filterPersons')->name('helpers.filterPersons');            
            Route::resource('helpers', 'HelperListController');
        });

        //
        // Reporting
        //
        Route::group(['middleware' => ['can:view-reports']], function () {
            Route::view('/reporting', 'reporting.index')->name('reporting.index');
        });

        // Reporting: Monthly summary report
        Route::group(['middleware' => ['can:view-people-reports']], function () {
            Route::get('/reporting/monthly-summary', 'Reporting\\MonthlySummaryReportingController@index')->name('reporting.monthly-summary');
        });

        // Reporting: People
        Route::group(['middleware' => ['can:view-people-reports']], function () {
            Route::get('/reporting/people', 'Reporting\\PeopleReportingController@index')->name('reporting.people');
            Route::get('/reporting/people/chart/nationalities', 'Reporting\\PeopleReportingController@nationalities')->name('reporting.people.nationalities');
            Route::get('/reporting/people/chart/genderDistribution', 'Reporting\\PeopleReportingController@genderDistribution')->name('reporting.people.genderDistribution');
            Route::get('/reporting/people/chart/demographics', 'Reporting\\PeopleReportingController@demographics')->name('reporting.people.demographics');
            Route::get('/reporting/people/chart/numberTypes', 'Reporting\\PeopleReportingController@numberTypes')->name('reporting.people.numberTypes');
            Route::get('/reporting/people/chart/visitorsPerDay', 'Reporting\\PeopleReportingController@visitorsPerDay')->name('reporting.people.visitorsPerDay');
            Route::get('/reporting/people/chart/visitorsPerWeek', 'Reporting\\PeopleReportingController@visitorsPerWeek')->name('reporting.people.visitorsPerWeek');
            Route::get('/reporting/people/chart/visitorsPerMonth', 'Reporting\\PeopleReportingController@visitorsPerMonth')->name('reporting.people.visitorsPerMonth');
            Route::get('/reporting/people/chart/visitorsPerYear', 'Reporting\\PeopleReportingController@visitorsPerYear')->name('reporting.people.visitorsPerYear');
            Route::get('/reporting/people/chart/avgVisitorsPerDayOfWeek', 'Reporting\\PeopleReportingController@avgVisitorsPerDayOfWeek')->name('reporting.people.avgVisitorsPerDayOfWeek');
            Route::get('/reporting/people/chart/registrationsPerDay', 'Reporting\\PeopleReportingController@registrationsPerDay')->name('reporting.people.registrationsPerDay');
        });

        // Reporting: Bank
        Route::group(['middleware' => ['can:view-bank-reports']], function () {
            Route::get('/reporting/bank/withdrawals', 'Reporting\\BankReportingController@withdrawals')->name('reporting.bank.withdrawals');
            Route::get('/reporting/bank/withdrawals/chart/couponsHandedOutPerDay/{coupon}', 'Reporting\\BankReportingController@couponsHandedOutPerDay')->name('reporting.bank.couponsHandedOutPerDay');

            Route::get('/reporting/bank/deposits', 'Reporting\\BankReportingController@deposits')->name('reporting.bank.deposits');
            Route::get('/reporting/bank/deposits/chart/stats', 'Reporting\\BankReportingController@depositStats')->name('reporting.bank.depositStats');
            Route::get('/reporting/bank/deposits/chart/stats/{project}', 'Reporting\\BankReportingController@projectDepositStats')->name('reporting.bank.projectDepositStats');
        });

        // Reporting: Logistic articles
        Route::group(['middleware' => ['can:view-kitchen-reports']], function () {    
            Route::get('/reporting/kitchen', function() {
                return redirect()->route('reporting.articles', ['project' => Config::get('reporting.kitchen_project')]);
            })->name('reporting.kitchen');
        });

        Route::get('/reporting/project/{project}/articles', 'Reporting\\ArticleReportingController@articles')->name('reporting.articles');
        Route::get('/reporting/project/articles/{article}', 'Reporting\\ArticleReportingController@article')->name('reporting.article');
        Route::get('/reporting/articles/chart/{article}/transactionsPerDay', 'Reporting\\ArticleReportingController@transactionsPerDay')->name('reporting.articles.transactionsPerDay');
        Route::get('/reporting/articles/chart/{article}/transactionsPerWeek', 'Reporting\\ArticleReportingController@transactionsPerWeek')->name('reporting.articles.transactionsPerWeek');
        Route::get('/reporting/articles/chart/{article}/transactionsPerMonth', 'Reporting\\ArticleReportingController@transactionsPerMonth')->name('reporting.articles.transactionsPerMonth');
        Route::get('/reporting/articles/chart/{article}/avgTransactionsPerWeekDay', 'Reporting\\ArticleReportingController@avgTransactionsPerWeekDay')->name('reporting.articles.avgTransactionsPerWeekDay');

        // Wiki
        Route::namespace('Wiki')->prefix('wiki')->name('wiki.')->group(function(){
            Route::get('articles/_latest_changes', 'ArticleController@latestChanges')->name('articles.latestChanges');
            Route::resource('articles', 'ArticleController');
            Route::get('articles/tag/{tag}', 'ArticleController@tag')->name('articles.tag');
        });

        // Accounting
        Route::namespace('Accounting')->prefix('accounting')->name('accounting.')->group(function(){
            Route::get('transactions/export', 'MoneyTransactionsController@export')->name('transactions.export');
            Route::get('transactions/summary', 'MoneyTransactionsController@summary')->name('transactions.summary');
            Route::get('transactions/{transaction}/receipt', 'MoneyTransactionsController@editReceipt')->name('transactions.editReceipt');
            Route::post('transactions/{transaction}/receipt', 'MoneyTransactionsController@updateReceipt')->name('transactions.updateReceipt');
            Route::delete('transactions/{transaction}/receipt', 'MoneyTransactionsController@deleteReceipt')->name('transactions.deleteReceipt');
            Route::resource('transactions', 'MoneyTransactionsController');
        });

        // Inventory
        Route::namespace('Inventory')->prefix('inventory')->name('inventory.')->group(function(){
            Route::resource('storages', 'StorageController');

            Route::get('transactions/{storage}', 'ItemTransactionController@changes')->name('transactions.changes');

            Route::get('transactions/{storage}/ingress', 'ItemTransactionController@ingress')->name('transactions.ingress');
            Route::post('transactions/{storage}/ingress', 'ItemTransactionController@storeIngress')->name('transactions.storeIngress');

            Route::get('transactions/{storage}/egress', 'ItemTransactionController@egress')->name('transactions.egress');
            Route::post('transactions/{storage}/egress', 'ItemTransactionController@storeEgress')->name('transactions.storeEgress');

            Route::delete('transactions/{storage}', 'ItemTransactionController@destroy')->name('transactions.destroy');
        });
    });

    // Logistics
    Route::group(['middleware' => 'can:use-logistics'], function () {
        Route::get('/logistics', 'LogisticsController@index')->name('logistics.index');

        Route::get('/logistics/projects/{project}/articles', 'ArticleController@index')->name('logistics.articles.index');
        Route::post('/logistics/projects/{project}/articles', 'ArticleController@store')->name('logistics.articles.store');

        Route::get('/logistics/articles/{article}/edit', 'ArticleController@edit')->name('logistics.articles.edit');
        Route::put('/logistics/articles/{article}', 'ArticleController@update')->name('logistics.articles.update');
        Route::delete('/logistics/articles/{article}', 'ArticleController@destroyArticle')->name('logistics.articles.destroyArticle');
    });

    // Tasks
    Route::group(['middleware' => ['auth']], function () {
        // TODO Add authorization: Auth::user()->can('list', Task::class)
        Route::view('/tasks', 'tasks.tasklist')->name('tasks');
    });

    // Calendar
    Route::group(['middleware' => ['auth', 'can:view-calendar']], function () {
        Route::get('/calendar', 'CalendarController@index')->name('calendar');
    });

    // Donors and donations
    Route::namespace('Fundraising')->middleware(['auth'])->prefix('fundraising')->name('fundraising.')->group(function () {
        // Donors
        Route::name('donors.export')->get('donors/export', 'DonorController@export');
        Route::name('donors.vcard')->get('donors/{donor}/vcard', 'DonorController@vcard');
        Route::resource('donors', 'DonorController');

        // Donations
        Route::name('donations.index')->get('donations', 'DonationController@index');
        Route::prefix('donors/{donor}')->group(function () {
            Route::name('donations.export')->get('export', 'DonationController@export');
            Route::resource('donations', 'DonationController')->except('show', 'index');
        });
    });

    Auth::routes();
    Route::get('/userPrivacyPolicy', 'PrivacyPolicy@userPolicy')->name('userPrivacyPolicy');

});

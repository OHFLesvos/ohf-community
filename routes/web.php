<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware('language')->group(function () {

    Route::middleware('auth')->group(function () {

        // Home (Dashboard)
        Route::get('/', 'HomeController@index')->name('home');

        // Reporting
        Route::view('reporting', 'reporting.index')->name('reporting.index')->middleware('can:view-reports');
    });

    // Authentication
    Auth::routes();

    $socialite_drivers = config('auth.socialite.drivers');
    Route::get('login/{driver}/redirect', 'Auth\LoginController@redirectToProvider')
        ->name('login.provider')
        ->where('driver', implode('|', $socialite_drivers));
    Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback')
        ->name('login.callback')
        ->where('driver', implode('|', $socialite_drivers));

    // Privacy policy
    Route::get('userPrivacyPolicy', 'PrivacyPolicy@userPolicy')->name('userPrivacyPolicy');

    // Settings
    Route::get('settings', 'Settings\SettingsController@edit')
        ->name('settings.edit');
    Route::put('settings', 'Settings\SettingsController@update')
        ->name('settings.update');

});

//
// User management
//
Route::middleware(['auth', 'language'])
    ->namespace('UserManagement')
    ->group(function () {

        // User management
        Route::prefix('admin')->group(function () {
            // Users
            Route::put('users/{user}/disable2FA', 'UserController@disable2FA')
                ->name('users.disable2FA');
            Route::put('users/{user}/disableOAuth', 'UserController@disableOAuth')
                ->name('users.disableOAuth');
            Route::resource('users', 'UserController');

            // Roles
            Route::get('roles/{role}/members', 'RoleController@manageMembers')
                ->name('roles.manageMembers');
            Route::put('roles/{role}/members', 'RoleController@updateMembers')
                ->name('roles.updateMembers');
            Route::resource('roles', 'RoleController');

            // Reporting
            Route::group(['middleware' => ['can:view-usermgmt-reports']], function () {
                Route::get('reporting/users/permissions', 'UserController@permissions')
                    ->name('users.permissions');
                Route::get('reporting/users/sensitiveData', 'UserController@sensitiveDataReport')
                    ->name('reporting.privacy');
                Route::get('reporting/roles/permissions', 'RoleController@permissions')
                    ->name('roles.permissions');
            });
        });

        // User profile
        Route::get('/userprofile', 'UserProfileController@index')
            ->name('userprofile');
        Route::post('/userprofile', 'UserProfileController@update')
            ->name('userprofile.update');
        Route::post('/userprofile/updatePassword', 'UserProfileController@updatePassword')
            ->name('userprofile.updatePassword');
        Route::delete('/userprofile', 'UserProfileController@delete')
            ->name('userprofile.delete');
        Route::get('/userprofile/2FA', 'UserProfileController@view2FA')
            ->name('userprofile.view2FA');
        Route::post('/userprofile/2FA', 'UserProfileController@store2FA')
            ->name('userprofile.store2FA');
        Route::delete('/userprofile/2FA', 'UserProfileController@disable2FA')
            ->name('userprofile.disable2FA');
    });

//
// Changelog
//

Route::middleware(['language', 'auth', 'can:view-changelogs'])
    ->namespace('Changelog')
    ->group(function () {
        Route::get('changelog', 'ChangelogController@index')
            ->name('changelog');
    });

//
// Badges
//
Route::middleware(['language', 'auth'])
    ->namespace('Badges')
    ->name('badges.')
    ->prefix('badges')
    ->group(function () {
        Route::middleware(['can:create-badges'])
            ->group(function () {
                Route::get('/', 'BadgeMakerController@index')
                    ->name('index');
                Route::post('/selection', 'BadgeMakerController@selection')
                    ->name('selection');
                Route::post('/make', 'BadgeMakerController@make')
                    ->name('make');
            });
    });

//
// Fundraising
//

Route::middleware(['language', 'auth'])
    ->namespace('Fundraising')
    ->prefix('fundraising')
    ->name('fundraising.')
    ->group(function () {
        // Donors
        Route::name('donors.export')
            ->get('donors/export', 'DonorController@export');
        Route::name('donors.vcard')
            ->get('donors/{donor}/vcard', 'DonorController@vcard');
        Route::resource('donors', 'DonorController');

        // Donations
        Route::name('donations.index')
            ->get('donations', 'DonationController@index');
        Route::name('donations.import')
            ->get('donations/import', 'DonationController@import');
        Route::name('donations.export')
            ->get('donations/export', 'DonationController@export');
        Route::name('donations.doImport')
            ->post('donations/import', 'DonationController@doImport');
        Route::prefix('donors/{donor}')
            ->group(function () {
                Route::name('donations.exportDonor')
                    ->get('export', 'DonationController@exportDonor');
                Route::resource('donations', 'DonationController')
                    ->except('show', 'index');
            });
    });

//
// Accounting
//

Route::middleware(['language', 'auth'])
    ->namespace('Accounting')
    ->prefix('accounting')
    ->name('accounting.')
    ->group(function () {

        // Transactions
        Route::get('transactions/export', 'MoneyTransactionsController@export')
            ->name('transactions.export');
        Route::post('transactions/doExport', 'MoneyTransactionsController@doExport')
            ->name('transactions.doExport');
        Route::get('transactions/summary', 'MoneyTransactionsController@summary')
            ->name('transactions.summary');
        Route::get('transactions/{transaction}/snippet', 'MoneyTransactionsController@snippet')
            ->name('transactions.snippet');
        Route::put('transactions/{transaction}/undoBooking', 'MoneyTransactionsController@undoBooking')
            ->name('transactions.undoBooking');
        Route::resource('transactions', 'MoneyTransactionsController');

        // Webling
        Route::get('webling', 'WeblingApiController@index')
            ->name('webling.index');
        Route::get('webling/prepare', 'WeblingApiController@prepare')
            ->name('webling.prepare');
        Route::post('webling', 'WeblingApiController@store')
            ->name('webling.store');
        Route::get('webling/sync', 'WeblingApiController@sync')
            ->name('webling.sync');
    });

//
// Collaboration
//

Route::middleware(['language', 'auth'])
    ->namespace('Collaboration')
    ->group(function () {

        Route::view('calendar', 'collaboration.calendar')
            ->name('calendar')
            ->middleware('can:view-calendar');

        Route::view('tasks', 'collaboration.tasklist')
            ->name('tasks')
            ->middleware('can:list,App\Models\Collaboration\Task');
    });

Route::middleware(['language'])
    ->namespace('Collaboration')
    ->prefix('kb')
    ->name('kb.')
    ->group(function () {
        Route::group(['middleware' => ['auth']], function () {
            Route::get('', 'SearchController@index')
                ->name('index');
            Route::get('latest_changes', 'SearchController@latestChanges')
                ->name('latestChanges');

            Route::get('tags', 'TagController@tags')
                ->name('tags');
            Route::get('tags/{tag}/pdf', 'TagController@pdf')
                ->name('tags.pdf');

            Route::get('articles/{article}/pdf', 'ArticleController@pdf')
                ->name('articles.pdf');
            Route::resource('articles', 'ArticleController')
                ->except('show');
        });
        Route::get('tags/{tag}', 'TagController@tag')
            ->name('tag');
        Route::resource('articles', 'ArticleController')
            ->only('show');
    });

//
// People
//

Route::middleware(['auth', 'language'])
    ->namespace('People')
    ->group(function () {

        // People
        Route::get('/people/bulkSearch', 'PeopleController@bulkSearch')
            ->name('people.bulkSearch')
            ->middleware('can:list,App\Models\People\Person');
        Route::post('/people/bulkSearch', 'PeopleController@doBulkSearch')
            ->name('people.doBulkSearch')
            ->middleware('can:list,App\Models\People\Person');
        Route::get('/people/export', 'PeopleController@export')
            ->name('people.export')
            ->middleware('can:export,App\Models\People\Person');
        Route::get('/people/import', 'PeopleController@import')
            ->name('people.import')
            ->middleware('can:create,App\Models\People\Person');
        Route::post('/people/doImport', 'PeopleController@doImport')
            ->name('people.doImport')
            ->middleware('can:create,App\Models\People\Person');
        Route::get('/people/duplicates', 'PeopleController@duplicates')
            ->name('people.duplicates');
        Route::post('/people/duplicates', 'PeopleController@applyDuplicates')
            ->name('people.applyDuplicates');
        Route::resource('/people', 'PeopleController');

        // Reporting
        Route::namespace('Reporting')
            ->prefix('reporting')
            ->middleware(['can:view-people-reports'])
            ->group(function () {

                // Monthly summary report
                Route::get('monthly-summary', 'MonthlySummaryReportingController@index')
                    ->name('reporting.monthly-summary');

                // People report
                Route::get('people', 'PeopleReportingController@index')
                    ->name('reporting.people');
            });
    });

//
// Bank
//

Route::middleware(['auth', 'language'])
    ->namespace('Bank')
    ->group(function () {

        Route::prefix('bank')
            ->name('bank.')
            ->group(function () {

                // Withdrawals
                Route::middleware('can:do-bank-withdrawals')
                    ->group(function () {

                        Route::get('', function () {
                            return redirect()->route('bank.withdrawal.search');
                        })->name('index');

                        Route::view('withdrawal', 'bank.withdrawal.search')
                            ->name('withdrawal.search');

                        Route::view('withdrawal/transactions', 'bank.withdrawal.transactions')
                            ->name('withdrawal.transactions')
                            ->middleware('can:list,App\Models\People\Person');

                        Route::get('codeCard', 'CodeCardController@create')
                            ->name('prepareCodeCard');

                        Route::post('codeCard', 'CodeCardController@download')
                            ->name('createCodeCard');
                    });

                // People
                Route::resource('people', 'PeopleController')
                    ->except(['index', 'store', 'update']);

                // Settings
                Route::middleware('can:configure-bank')
                    ->namespace('Settings')
                    ->name('settings.')
                    ->group(function () {
                        Route::get('settings', 'BankSettingsController@edit')
                            ->name('edit');
                        Route::put('settings', 'BankSettingsController@update')
                            ->name('update');
                    });

                // Maintenance
                Route::middleware('can:cleanup,App\Models\People\Person')
                    ->group(function () {
                        Route::get('maintenance', 'MaintenanceController@maintenance')
                            ->name('maintenance');
                        Route::post('maintenance', 'MaintenanceController@updateMaintenance')
                            ->name('updateMaintenance');
                    });

                // Export
                Route::middleware('can:export,App\Models\People\Person')
                    ->group(function () {
                        Route::get('export', 'ImportExportController@export')
                            ->name('export');
                        Route::post('doExport', 'ImportExportController@doExport')
                            ->name('doExport');
                    });
            });

        // Coupons
        Route::middleware('can:configure-bank')
            ->prefix('bank')
            ->group(function () {
                Route::resource('coupons', 'CouponTypesController');
            });

        // Reporting
        Route::middleware('can:view-bank-reports')
            ->namespace('Reporting')
            ->name('reporting.bank.')
            ->prefix('reporting/bank')
            ->group(function () {
                Route::get('withdrawals', 'BankReportingController@withdrawals')
                    ->name('withdrawals');
                Route::get('withdrawals/chart/couponsHandedOutPerDay/{coupon}', 'BankReportingController@couponsHandedOutPerDay')
                    ->name('couponsHandedOutPerDay');

                Route::get('visitors', 'BankReportingController@visitors')
                    ->name('visitors');
                Route::get('visitors/chart/visitorsPerDay', 'BankReportingController@visitorsPerDay')
                    ->name('visitorsPerDay');
                Route::get('visitors/chart/visitorsPerWeek', 'BankReportingController@visitorsPerWeek')
                    ->name('visitorsPerWeek');
                Route::get('visitors/chart/visitorsPerMonth', 'BankReportingController@visitorsPerMonth')
                    ->name('visitorsPerMonth');
                Route::get('visitors/chart/visitorsPerYear', 'BankReportingController@visitorsPerYear')
                    ->name('visitorsPerYear');
                Route::get('visitors/chart/avgVisitorsPerDayOfWeek', 'BankReportingController@avgVisitorsPerDayOfWeek')
                    ->name('avgVisitorsPerDayOfWeek');
            });
    });

//
// Helpers
//

Route::middleware(['auth', 'language'])
    ->namespace('Helpers')
    ->group(function () {
        Route::name('people.')
            ->group(function () {
                // Report view
                Route::view('helpers/report', 'helpers.report')
                    ->name('helpers.report')
                    ->middleware('can:list,App\Models\Helpers\Helper');
                // Export view
                Route::get('helpers/export', 'HelperExportImportController@export')
                    ->name('helpers.export')
                    ->middleware('can:export,App\Models\Helpers\Helper');
                // Export download
                Route::post('helpers/doExport', 'HelperExportImportController@doExport')
                    ->name('helpers.doExport')
                    ->middleware('can:export,App\Models\Helpers\Helper');
                // Import view
                Route::get('helpers/import', 'HelperExportImportController@import')
                    ->name('helpers.import')
                    ->middleware('can:import,App\Models\Helpers\Helper');
                // Import upload
                Route::post('helpers/doImport', 'HelperExportImportController@doImport')
                    ->name('helpers.doImport')
                    ->middleware('can:import,App\Models\Helpers\Helper');
                // Download vCard
                Route::get('helpers/{helper}/vcard', 'HelperExportImportController@vcard')
                    ->name('helpers.vcard');
                // Create helper (decide what way)
                Route::get('helpers/createFrom', 'HelperListController@createFrom')
                    ->name('helpers.createFrom')
                    ->middleware('can:create,App\Models\Helpers\Helper');
                // Store helper (decide what way)
                Route::post('helpers/createFrom', 'HelperListController@storeFrom')
                    ->name('helpers.storeFrom')
                    ->middleware('can:create,App\Models\Helpers\Helper');
                // Responsibilities resource
                Route::name('helpers.')->group(function () {
                    Route::resource('helpers/responsibilities', 'ResponsibilitiesController')
                        ->except('show');
                });
                // Helpers resource
                Route::resource('helpers', 'HelperListController');
            });
    });

//
// Library
//

Route::middleware(['auth', 'language', 'can:operate-library'])
    ->namespace('Library')
    ->prefix('library')
    ->name('library.')
    ->group(function () {
        Route::get('lending', 'LendingController@index')->name('lending.index');

        Route::get('lending/persons', 'LendingController@persons')->name('lending.persons');
        Route::post('lending/persons/create', 'LendingController@storePerson')->name('lending.storePerson');
        Route::get('lending/person/{person}', 'LendingController@person')->name('lending.person');
        Route::post('lending/person/{person}/lendBook', 'LendingController@lendBookToPerson')->name('lending.lendBookToPerson');
        Route::post('lending/person/{person}/extendBook', 'LendingController@extendBookToPerson')->name('lending.extendBookToPerson');
        Route::post('lending/person/{person}/returnBook', 'LendingController@returnBookFromPerson')->name('lending.returnBookFromPerson');
        Route::get('lending/person/{person}/log', 'LendingController@personLog')->name('lending.personLog');

        Route::get('lending/books', 'LendingController@books')->name('lending.books');
        Route::get('lending/book/{book}', 'LendingController@book')->name('lending.book');
        Route::post('lending/book/{book}/lend', 'LendingController@lendBook')->name('lending.lendBook');
        Route::post('lending/book/{book}/extend', 'LendingController@extendBook')->name('lending.extendBook');
        Route::post('lending/book/{book}/return', 'LendingController@returnBook')->name('lending.returnBook');
        Route::get('lending/book/{book}/log', 'LendingController@bookLog')->name('lending.bookLog');

        Route::resource('books', 'BookController');
    });

//
// Shop
//

Route::middleware(['auth', 'language'])
    ->namespace('Shop')
    ->prefix('shop')
    ->name('shop.')
    ->group(function () {
        Route::middleware(['can:validate-shop-coupons'])->group(function () {
            Route::view('/', 'shop.index')->name('index');
            Route::view('manageCards', 'shop.manageCards')->name('manageCards');
        });
    });

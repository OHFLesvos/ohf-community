<?php

namespace App;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//
// User management
//
Route::middleware(['auth', 'language'])
    ->namespace('UserManagement\API')
    ->name('api.')
    ->group(function () {
        Route::apiResource('users', 'UserController');
        Route::prefix('users/{user}')
            ->name('users.')
            ->group(function () {
                Route::get('roles', 'UserController@roles')
                    ->name('roles.index');
                Route::prefix('relationships')
                    ->name('relationships.')
                    ->group(function () {
                        Route::prefix('roles')
                            ->name('roles.')
                            ->group(function () {
                                Route::get('', 'UserRoleRelationshipController@index')
                                ->name('index');
                                Route::post('', 'UserRoleRelationshipController@store')
                                    ->name('store');
                                Route::put('', 'UserRoleRelationshipController@update')
                                    ->name('update');
                                Route::delete('', 'UserRoleRelationshipController@destroy')
                                    ->name('destroy');
                            });
                    });
            });

        Route::apiResource('roles', 'RoleController');
        Route::prefix('roles/{role}')
            ->name('roles.')
            ->group(function () {
                Route::get('users', 'RoleController@users')
                    ->name('users.index');
                Route::get('administrators', 'RoleController@administrators')
                    ->name('administrators.index');
                Route::prefix('relationships')
                    ->name('relationships.')
                    ->group(function () {
                        Route::prefix('users')
                            ->name('users.')
                            ->group(function () {
                                Route::get('', 'RoleUserRelationshipController@index')
                                    ->name('index');
                                Route::post('', 'RoleUserRelationshipController@store')
                                    ->name('store');
                                Route::put('', 'RoleUserRelationshipController@update')
                                    ->name('update');
                                Route::delete('', 'RoleUserRelationshipController@destroy')
                                    ->name('destroy');
                            });
                        Route::prefix('administrators')
                            ->name('administrators.')
                            ->group(function () {
                                Route::get('', 'RoleAdministratorRelationshipController@index')
                                    ->name('index');
                                Route::post('', 'RoleAdministratorRelationshipController@store')
                                    ->name('store');
                                Route::put('', 'RoleAdministratorRelationshipController@update')
                                    ->name('update');
                                Route::delete('', 'RoleAdministratorRelationshipController@destroy')
                                    ->name('destroy');
                            });
                    });
            });
    });

//
// Fundraising
//

Route::middleware(['language', 'auth'])
    ->prefix('fundraising')
    ->name('api.fundraising.')
    ->namespace('Fundraising\API')
    ->group(function () {

        // Donor
        Route::get('donors/export', 'DonorController@export')
            ->name('donors.export');
        Route::get('donors/salutations', 'DonorController@salutations')
            ->name('donors.salutations');
        Route::apiResource('donors', 'DonorController');
        Route::get('donors/{donor}/vcard', 'DonorController@vcard')
            ->name('donors.vcard');

        // Donor's donations
        Route::apiResource('donors.donations', 'DonorDonationsController')
            ->only('index', 'store');
        Route::get('donors/{donor}/donations/export', 'DonorDonationsController@export')
            ->name('donors.donations.export');

        // Donor's comments
        Route::apiResource('donors.comments', 'DonorCommentsController')
            ->only('index', 'store');

        // Donor's tags
        Route::apiResource('donors.tags', 'DonorTagsController')
            ->only('index', 'store');
        Route::apiResource('tags', 'TagsController')
            ->only('index');

        // Donations
        Route::get('donations/channels', 'DonationController@channels')
            ->name('donations.channels');
        Route::get('donations/currencies', 'DonationController@currencies')
            ->name('donations.currencies');
        Route::get('donations/export', 'DonationController@export')
            ->name('donations.export');
        Route::post('donations/import', 'DonationController@import')
            ->name('donations.import');
        Route::apiResource('donations', 'DonationController')
            ->only('index', 'update', 'destroy');

        // Report
        Route::prefix('report')
            ->name('report.')
            ->group(function () {
                Route::get('donors/count', 'ReportController@count')
                    ->name('donors.count');
                Route::get('donors/languages', 'ReportController@languages')
                    ->name('donors.languages');
                Route::get('donors/countries', 'ReportController@countries')
                    ->name('donors.countries');
                Route::get('donors/registrations', 'ReportController@donorRegistrations')
                    ->name('donors.registrations');
                Route::get('donations/registrations', 'ReportController@donationRegistrations')
                    ->name('donations.registrations');
            });
    });

Route::middleware(['language', 'auth'])
    ->name('api.')
    ->namespace('API')
    ->group(function () {
        Route::apiResource('comments', 'CommentsController')
            ->except('index', 'store');
    });

// RaiseNow Webhook
Route::middleware(['auth.basic', 'can:accept-fundraising-webhooks'])
    ->prefix('fundraising/donations')
    ->name('fundraising.')
    ->namespace('Fundraising\API')
    ->group(function () {
        Route::name('donations.raiseNowWebHookListener')
            ->post('raiseNowWebHookListener', 'WebhookController@raiseNowWebHookListener');
    });

//
// Accounting
//

Route::middleware(['language', 'auth'])
    ->prefix('accounting')
    ->name('accounting.')
    ->namespace('Accounting\API')
    ->group(function () {
        Route::post('transactions/{transaction}/receipt', 'MoneyTransactionsController@updateReceipt')
            ->name('transactions.updateReceipt');
    });

//
// Collaboration
//

Route::middleware('auth')
    ->namespace('Collaboration\API')
    ->name('api.')
    ->group(function () {
        Route::apiResource('tasks', 'TasksController');
        Route::patch('tasks/{task}/done', 'TasksController@done')
            ->name('tasks.done');
    });

//
// People
//

Route::middleware(['auth', 'language'])
    ->namespace('People\API')
    ->name('api.people.')
    ->prefix('people')
    ->group(function () {

        // Get list of people
        Route::get('', 'PeopleController@index')
            ->name('index')
            ->middleware('can:viewAny,App\Models\People\Person');

        // Filter persons
        Route::get('filterPersons', 'PeopleController@filterPersons')
            ->name('filterPersons')
            ->middleware('can:viewAny,App\Models\People\Person');

        // Store new person
        Route::post('', 'PeopleController@store')
            ->name('store')
            ->middleware('can:create,App\Models\People\Person');

        // Show person
        Route::get('{person}', 'PeopleController@show')
            ->name('show')
            ->middleware('can:view,person');

        // Update person
        Route::put('{person}', 'PeopleController@update')
            ->name('update')
            ->middleware('can:update,person');

        // Set gender
        Route::patch('{person}/gender', 'PeopleController@updateGender')
            ->name('updateGender')
            ->middleware('can:update,person');

        // Set date of birth
        Route::patch('{person}/date_of_birth', 'PeopleController@updateDateOfBirth')
            ->name('updateDateOfBirth')
            ->middleware('can:update,person');

        // Set nationality
        Route::patch('{person}/nationality', 'PeopleController@updateNationality')
            ->name('updateNationality')
            ->middleware('can:update,person');

        // Update police number
        Route::patch('{person}/updatePoliceNo', 'PeopleController@updatePoliceNo')
            ->name('updatePoliceNo')
            ->middleware('can:update,person');

        // Update remarks
        Route::patch('{person}/remarks', 'PeopleController@updateRemarks')
            ->name('updateRemarks')
            ->middleware('can:update,person');

        // Register code card
        Route::patch('{person}/card', 'PeopleController@registerCard')
            ->name('registerCard')
            ->middleware('can:update,person');

        // Reporting
        Route::prefix('reporting')
            ->name('reporting.')
            ->middleware(['can:view-people-reports'])
            ->group(function () {
                Route::get('numbers', 'ReportingController@numbers')
                    ->name('numbers');
                Route::get('nationalities', 'ReportingController@nationalities')
                    ->name('nationalities');
                Route::get('genderDistribution', 'ReportingController@genderDistribution')
                    ->name('genderDistribution');
                Route::get('ageDistribution', 'ReportingController@ageDistribution')
                    ->name('ageDistribution');
                Route::get('registrationsPerDay', 'ReportingController@registrationsPerDay')
                    ->name('registrationsPerDay');
                Route::get('monthlySummary', 'MonthlySummaryReportController@summary')
                    ->name('monthlySummary');
            });
    });

//
// Bank
//

Route::middleware(['auth', 'language'])
    ->namespace('Bank')
    ->group(function () {

        // Withdrawals
        Route::middleware('can:do-bank-withdrawals')
            ->prefix('bank')
            ->name('api.bank.withdrawal.')
            ->namespace('API')
            ->group(function () {
                Route::get('withdrawal/dailyStats', 'WithdrawalController@dailyStats')
                    ->name('dailyStats');
                Route::get('withdrawal/transactions', 'WithdrawalController@transactions')
                    ->name('transactions')
                    ->middleware('can:viewAny,App\Models\People\Person');
                Route::get('withdrawal/search', 'WithdrawalController@search')
                    ->name('search');
                Route::get('withdrawal/persons/{person}', 'WithdrawalController@person')
                    ->name('person');
                Route::post('person/{person}/couponType/{couponType}/handout', 'WithdrawalController@handoutCoupon')
                    ->name('handoutCoupon');
                Route::delete('person/{person}/couponType/{couponType}/handout', 'WithdrawalController@undoHandoutCoupon')
                    ->name('undoHandoutCoupon');
            });

        // Reporting
        Route::middleware('can:view-bank-reports')
            ->prefix('bank')
            ->name('api.bank.reporting.')
            ->namespace('API')
            ->group(function () {
                // Withdrawals
                Route::get('withdrawals', 'WithdrawalReportingController@withdrawals')
                    ->name('withdrawals');
                Route::get('withdrawals/chart/couponsHandedOutPerDay/{coupon}', 'WithdrawalReportingController@couponsHandedOutPerDay')
                    ->name('couponsHandedOutPerDay');

                // Visitors
                Route::get('visitors', 'VisitorReportingController@visitors')
                    ->name('visitors');
                Route::get('visitors/chart/visitorsPerDay', 'VisitorReportingController@visitorsPerDay')
                    ->name('visitorsPerDay');
                Route::get('visitors/chart/visitorsPerWeek', 'VisitorReportingController@visitorsPerWeek')
                    ->name('visitorsPerWeek');
                Route::get('visitors/chart/visitorsPerMonth', 'VisitorReportingController@visitorsPerMonth')
                    ->name('visitorsPerMonth');
                Route::get('visitors/chart/visitorsPerYear', 'VisitorReportingController@visitorsPerYear')
                    ->name('visitorsPerYear');
                Route::get('visitors/chart/avgVisitorsPerDayOfWeek', 'VisitorReportingController@avgVisitorsPerDayOfWeek')
                    ->name('avgVisitorsPerDayOfWeek');
            });
    });

//
// Community volunteers
//

Route::middleware(['auth', 'language'])
    ->name('api.')
    ->namespace('CommunityVolunteers\API')
    ->group(function () {
        Route::name('cmtyvol.')
            ->prefix('cmtyvol')
            ->group(function () {

                // Age distribution
                Route::get('ageDistribution', 'ReportController@ageDistribution')
                    ->name('ageDistribution')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');
                // Nationality distribution
                Route::get('nationalityDistribution', 'ReportController@nationalityDistribution')
                    ->name('nationalityDistribution')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');
                // Gender distribution
                Route::get('genderDistribution', 'ReportController@genderDistribution')
                    ->name('genderDistribution')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');
            });

        Route::apiResource('cmtyvol', 'CommunityVolunteerController')
            ->only('index', 'show');
    });

//
// Shop
//

Route::middleware(['auth', 'language'])
    ->prefix('shop')
    ->name('api.shop.')
    ->namespace('Shop\API')
    ->group(function () {
        Route::middleware(['can:validate-shop-coupons'])
            ->prefix('cards')
            ->name('cards.')
            ->group(function () {
                Route::get('listRedeemedToday', 'CardsController@listRedeemedToday')
                    ->name('listRedeemedToday');
                Route::get('searchByCode', 'CardsController@searchByCode')
                    ->name('searchByCode');
                Route::patch('redeem/{handout}', 'CardsController@redeem')
                    ->name('redeem');
                Route::delete('cancel/{handout}', 'CardsController@cancel')
                    ->name('cancel');
                Route::get('listNonRedeemedByDay', 'CardsController@listNonRedeemedByDay')
                    ->name('listNonRedeemedByDay');
                Route::post('deleteNonRedeemedByDay', 'CardsController@deleteNonRedeemedByDay')
                    ->name('deleteNonRedeemedByDay');
            });
    });

//
// Library
//

Route::middleware(['auth', 'language', 'can:operate-library'])
    ->namespace('Library\API')
    ->prefix('library')
    ->name('api.library.')
    ->group(function () {
        Route::get('lending/stats', 'LendingController@stats')
            ->name('lending.stats');
        Route::get('lending/persons', 'LendingController@persons')
            ->name('lending.persons');
        Route::get('lending/books', 'LendingController@books')
            ->name('lending.books');

        Route::get('lending/person/{person}', 'LendingController@person')
            ->name('lending.person');
        Route::post('lending/person/{person}/lendBook', 'LendingController@lendBookToPerson')
            ->name('lending.lendBookToPerson');
        Route::post('lending/person/{person}/extendBook', 'LendingController@extendBookToPerson')
            ->name('lending.extendBookToPerson');
        Route::post('lending/person/{person}/returnBook', 'LendingController@returnBookFromPerson')
            ->name('lending.returnBookFromPerson');
        Route::get('lending/person/{person}/log', 'LendingController@personLog')
            ->name('lending.personLog');

        Route::get('lending/book/{book}', 'LendingController@book')
            ->name('lending.book');
        Route::post('lending/book/{book}/lend', 'LendingController@lendBook')
            ->name('lending.lendBook');
        Route::post('lending/book/{book}/extend', 'LendingController@extendBook')
            ->name('lending.extendBook');
        Route::post('lending/book/{book}/return', 'LendingController@returnBook')
            ->name('lending.returnBook');
        Route::get('lending/book/{book}/log', 'LendingController@bookLog')
            ->name('lending.bookLog');

        Route::get('books/filter', 'BookController@filter')
            ->name('books.filter');
        Route::get('books/findIsbn', 'BookController@findIsbn')
            ->name('books.findIsbn');
        Route::apiResource('books', 'BookController');
    });

//
// Collaboration
//

Route::middleware(['auth', 'language'])
    ->namespace('Collaboration\API')
    ->prefix('kb')
    ->name('api.kb.')
    ->group(function () {
        Route::resource('articles', 'ArticleController');
    });

//
// Common data
//

Route::get('countries', 'API\DataListController@countries')
    ->middleware(['language'])
    ->name('api.countries');

Route::get('languages', 'API\DataListController@languages')
    ->middleware(['language'])
    ->name('api.languages');

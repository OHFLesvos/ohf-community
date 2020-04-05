<?php

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
        Route::apiResource('donors', 'DonorController');
    });

// RaiseNow Webhook
Route::middleware(['auth.basic', 'can:accept-fundraising-webhooks'])
    ->prefix('fundraising/donations')
    ->name('fundraising.')
    ->namespace('Fundraising\API')
    ->group(function () {
        Route::name('donations.raiseNowWebHookListener')->post('raiseNowWebHookListener', 'DonationController@raiseNowWebHookListener');
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
    ->prefix('calendar')
    ->name('calendar.')
    ->group(function () {
        Route::apiResource('events', 'CalendarEventController');
        Route::patch('events/{event}/date', 'CalendarEventController@updateDate')
            ->name('events.updateDate');
        Route::apiResource('resources', 'CalendarResourceController');
    });

Route::middleware('auth')
    ->namespace('Collaboration\API')
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
            ->middleware('can:list,App\Models\People\Person');

        // Store new person
        Route::post('', 'PeopleController@store')
            ->name('store')
            ->middleware('can:create,App\Models\People\Person');

        // Update person
        Route::put('{person}', 'PeopleController@update')
            ->name('update')
            ->middleware('can:update,person');

        // Filter persons
        Route::get('filterPersons', 'PeopleController@filterPersons')
            ->name('filterPersons')
            ->middleware('can:list,App\Models\People\Person');

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
                Route::get('nationalities', 'ReportingController@nationalities')
                    ->name('nationalities');
                Route::get('genderDistribution', 'ReportingController@genderDistribution')
                    ->name('genderDistribution');
                Route::get('ageDistribution', 'ReportingController@ageDistribution')
                    ->name('ageDistribution');
                Route::get('registrationsPerDay', 'ReportingController@registrationsPerDay')
                    ->name('registrationsPerDay');
            });
    });

//
// Bank
//

Route::middleware(['auth', 'language'])
    ->namespace('Bank')
    ->group(function () {
        Route::middleware('can:do-bank-withdrawals')
            ->prefix('bank')
            ->name('api.bank.withdrawal.')
            ->namespace('API')
            ->group(function () {
                Route::get('withdrawal/dailyStats', 'WithdrawalController@dailyStats')
                    ->name('dailyStats');
                Route::get('withdrawal/transactions', 'WithdrawalController@transactions')
                    ->name('transactions')
                    ->middleware('can:list,App\Models\People\Person');
                Route::get('withdrawal/search', 'WithdrawalController@search')
                    ->name('search');
                Route::get('withdrawal/persons/{person}', 'WithdrawalController@person')
                    ->name('person');
                Route::post('person/{person}/couponType/{couponType}/handout', 'WithdrawalController@handoutCoupon')
                    ->name('handoutCoupon');
                Route::delete('person/{person}/couponType/{couponType}/handout', 'WithdrawalController@undoHandoutCoupon')
                    ->name('undoHandoutCoupon');
            });
    });

//
// Helpers
//

Route::middleware(['auth', 'language'])
    ->name('people.helpers.')
    ->prefix('helpers')
    ->namespace('Helpers\API')
    ->group(function () {
        // Age distribution
        Route::get('report/ages', 'HelperReportController@ages')
            ->name('report.ages')
            ->middleware('can:list,App\Models\Helpers\Helper');
        // Nationality distribution
        Route::get('report/nationalities', 'HelperReportController@nationalities')
            ->name('report.nationalities')
            ->middleware('can:list,App\Models\Helpers\Helper');
        // Gender distribution
        Route::get('report/genders', 'HelperReportController@genders')
            ->name('report.genders')
            ->middleware('can:list,App\Models\Helpers\Helper');
    });

//
// Shop
//

Route::middleware(['auth', 'language'])
    ->prefix('shop')
    ->name('shop.')
    ->namespace('Shop\API')
    ->group(function () {
        Route::middleware(['can:validate-shop-coupons'])
            ->prefix('cards')
            ->name('cards.')
            ->group(function () {
                Route::get('listRedeemedToday', 'CardsController@listRedeemedToday')->name('listRedeemedToday');
                Route::get('searchByCode', 'CardsController@searchByCode')->name('searchByCode');
                Route::patch('redeem/{handout}', 'CardsController@redeem')->name('redeem');
                Route::delete('cancel/{handout}', 'CardsController@cancel')->name('cancel');
                Route::get('listNonRedeemedByDay', 'CardsController@listNonRedeemedByDay')->name('listNonRedeemedByDay');
                Route::post('deleteNonRedeemedByDay', 'CardsController@deleteNonRedeemedByDay')->name('deleteNonRedeemedByDay');
            });
    });

//
// Library
//

Route::middleware(['auth', 'language', 'can:operate-library'])
    ->namespace('Library\API')
    ->prefix('library')
    ->name('library.')
    ->group(function () {
        Route::get('books/filter', 'BookController@filter')->name('books.filter');
        Route::get('books/findIsbn', 'BookController@findIsbn')->name('books.findIsbn');
    });
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
        // User management
        //
        Route::put('users/{user}/disable2FA', 'UserController@disable2FA')->name('users.disable2FA');
        Route::resource('users', 'UserController');
        Route::resource('roles', 'RoleController');

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

            Route::get('/bank/codeCard', 'People\Bank\CodeCardController@prepareCodeCard')->name('bank.prepareCodeCard');
            Route::post('/bank/codeCard', 'People\Bank\CodeCardController@createCodeCard')->name('bank.createCodeCard');
        });

        Route::get('/bank/deposit', 'People\Bank\DepositController@deposit')->name('bank.deposit');
        Route::post('/bank/deposit', 'People\Bank\DepositController@storeDeposit')->name('bank.storeDeposit');

        Route::get('/bank/settings', 'People\Bank\SettingsController@settings')->name('bank.settings');
        Route::post('/bank/settings', 'People\Bank\SettingsController@updateSettings')->name('bank.updateSettings');

        Route::get('/bank/maintenance', 'People\Bank\MaintenanceController@maintenance')->name('bank.maintenance');
        Route::post('/bank/maintenance', 'People\Bank\MaintenanceController@updateMaintenance')->name('bank.updateMaintenance');

        Route::get('/bank/export', 'People\Bank\ImportExportController@export')->name('bank.export');
        Route::get('/bank/import', 'People\Bank\ImportExportController@import')->name('bank.import');
        Route::post('/bank/doImport', 'People\Bank\ImportExportController@doImport')->name('bank.doImport');

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
        Route::resource('/people', 'PeopleController');

        //
        // Reporting
        //
        Route::group(['middleware' => ['can:view-reports']], function () {
            Route::view('/reporting', 'reporting.index')->name('reporting.index');
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
            Route::get('/reporting/bank/withdrawals/chart/numTransactions', 'Reporting\\BankReportingController@numTransactions')->name('reporting.bank.numTransactions');
            Route::get('/reporting/bank/withdrawals/chart/sumTransactions', 'Reporting\\BankReportingController@sumTransactions')->name('reporting.bank.sumTransactions');

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

        // Reporting: User and role management
        Route::group(['middleware' => ['can:view-usermgmt-reports']], function () {    
            Route::get('/reporting/users/permissions', 'UserController@permissions')->name('users.permissions');
            Route::get('/reporting/users/sensitiveData', 'UserController@sensitiveDataReport')->name('reporting.privacy');
            Route::get('/reporting/roles/permissions', 'RoleController@permissions')->name('roles.permissions');
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
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/donations/donors/export', 'Donations\DonorController@export')->name('donors.export');
        Route::resource('donations/donors', 'Donations\DonorController');
        Route::get('/donations/donors/{donor}/donation', 'Donations\DonationController@register')->name('donations.create');
        Route::post('/donations/donors/{donor}/donation', 'Donations\DonationController@store')->name('donations.store');
        Route::get('/donations/donors/{donor}/donation/{donation}/edit', 'Donations\DonationController@edit')->name('donations.edit');
        Route::put('/donations/donors/{donor}/donation/{donation}', 'Donations\DonationController@update')->name('donations.update');
        Route::delete('/donations/donors/{donor}/donation/{donation}', 'Donations\DonationController@destroy')->name('donations.destroy');
        Route::get('/donations/donors/{donor}/export', 'Donations\DonationController@export')->name('donations.export');
        Route::get('/donations', 'Donations\DonationController@index')->name('donations.index');
    });

    Auth::routes();
    Route::get('/userPrivacyPolicy', 'PrivacyPolicy@userPolicy')->name('userPrivacyPolicy');

});

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
    });

    Auth::routes();
    Route::get('/userPrivacyPolicy', 'PrivacyPolicy@userPolicy')->name('userPrivacyPolicy');

});

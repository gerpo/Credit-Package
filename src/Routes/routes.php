<?php

use Gerpo\DmsCredits\Controllers\CreditsTransferController;

Route::group(['middleware' => ['web', 'auth', 'can:have_credits']], function () {
    Route::get('credits', 'Gerpo\DmsCredits\Controllers\AccountController@index')->name('credits.index');

    Route::post('/credits/transfer', CreditsTransferController::class)->name('credits.transfer');

    Route::get(
        '/credits/code',
        'Gerpo\DmsCredits\Controllers\CodeController@index'
    )->name('credits.code.index')->middleware('can:create_codes');
    Route::get(
        '/credits/code/export',
        'Gerpo\DmsCredits\Controllers\CodeController@export'
    )->name('credits.code.export')->middleware('can:create_codes');
    Route::post(
        '/credits/code',
        'Gerpo\DmsCredits\Controllers\CodeController@create'
    )->name('credits.code.create')->middleware('can:create_codes');
    Route::post(
        '/credits/code/redeem',
        'Gerpo\DmsCredits\Controllers\CodeController@redeem'
    )->name('credits.code.redeem');

    Route::get(
        '/credits/code/statistics',
        'Gerpo\DmsCredits\Controllers\CreditStatisticsController@index'
    )->name('credits.statistics.index')->middleware('can:view_code_statistics');
});

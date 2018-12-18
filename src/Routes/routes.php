<?php


Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('credits', 'Gerpo\DmsCredits\Controllers\AccountController@index');

    Route::post('/credits/purchase', 'Gerpo\DmsCredits\Controllers\PurchaseController@store');

    Route::post('/credits/code',
        'Gerpo\DmsCredits\Controllers\CodeController@create')->name('credits.code.create')->middleware('check_ability:create_codes');
    Route::post('/credits/code/redeem',
        'Gerpo\DmsCredits\Controllers\CodeController@redeem')->name('credits.code.redeem');
});


<?php


Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('credits', 'Gerpo\DmsCredits\Controllers\AccountController@index')->name('credits.index');

    Route::post('/credits/purchase', 'Gerpo\DmsCredits\Controllers\PurchaseController@store');

    Route::get('/credits/code',
        'Gerpo\DmsCredits\Controllers\CodeController@index')->name('credits.code.index')->middleware('check_ability:create_codes');
    Route::get('/credits/code/export',
        'Gerpo\DmsCredits\Controllers\CodeController@export')->name('credits.code.export')->middleware('check_ability:create_codes');
    Route::post('/credits/code',
        'Gerpo\DmsCredits\Controllers\CodeController@create')->name('credits.code.create')->middleware('check_ability:create_codes');
    Route::post('/credits/code/redeem',
        'Gerpo\DmsCredits\Controllers\CodeController@redeem')->name('credits.code.redeem');
});


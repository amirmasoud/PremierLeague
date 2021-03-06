<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'guest:api'], function () {
    Route::get('standings', 'StandingController@all');

    Route::get('scores/next', 'ScoreController@nextRound');
    Route::get('scores/all', 'ScoreController@allRounds');

    Route::get('reset-data', 'SettingController@reset');
});

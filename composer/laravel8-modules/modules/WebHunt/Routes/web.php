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

Route::prefix('webhunt')->group(function () {
    Route::get('/', 'WebHuntController@index');
    Route::get('/meta/{midOrSlug}', 'WebHuntController@index');
    Route::get('/content/{cidOrSlug}', 'WebHuntController@view_content');
});

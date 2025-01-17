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

Route::prefix('project')->group(function () {
    Route::get('/', 'ProjectController@index');
    Route::get('/meta/{midOrSlug}', 'ProjectController@index');
    Route::get('/content/{cidOrSlug}', 'ProjectController@view_content');
});

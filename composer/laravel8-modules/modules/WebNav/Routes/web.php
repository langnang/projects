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

Route::prefix('webnav')->group(function () {
    Route::get('/', 'WebNavController@view_index');
    Route::post('/', 'WebNavController@crud_index');
});

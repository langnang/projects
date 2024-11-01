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

Route::prefix('video')->group(function () {
    Route::get('/', 'VideoController@view_index');
    Route::get('/metas', 'VideoController@view_meta_list');
    Route::get('/contents', 'VideoController@view_content_list');
    Route::get('/content/{cid}', 'VideoController@view_content_item');

});

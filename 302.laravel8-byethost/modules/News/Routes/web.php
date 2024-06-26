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

Route::prefix('news')->group(function () {
    Route::get('/', 'NewsController@view_index');
    Route::get('/metas', 'NewsController@view_meta_list');
    Route::get('/contents', 'NewsController@view_content_list');
    Route::get('/content/{cid}', 'NewsController@view_content_item');

});

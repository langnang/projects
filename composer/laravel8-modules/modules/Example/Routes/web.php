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

Route::prefix('Example')->group(function () {
    Route::get('/', 'ExampleController@view_index');
    // Route::get('/', function (Request $request) {
    //     $Example = app('files')->directories(module_path(Module::currentConfig('name'), config('modules.paths.generator.views.path') . '\\Example'));
    //     var_dump($Example);
    // });
    // Route::get('/{path}', 'ExampleController@view_Example')->where(['path' => '.*']);
});

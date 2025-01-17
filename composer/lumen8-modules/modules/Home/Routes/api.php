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

// Route::middleware('auth:api')->get('/home', function (Request $request) {
// return $request->user();
// });


$router->group(['prefix' => 'home'], function () use ($router) {
  // $router->get('/', "SpiderController@index");
  $router->post('/', function (Request $request) {
    return $request->user();
  });
});
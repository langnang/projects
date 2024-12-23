<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'App\Http\Controllers\Controller@view_index');

Route::prefix('home')->group(function () {
    Route::get('/', 'App\Http\Controllers\HomeController@view_index');
    Route::get('/meta/{id}', 'App\Http\Controllers\HomeController@view_meta');
    Route::get('/content/{id}', 'App\Http\Controllers\HomeController@view_content');
});

// Route::match(['get', 'post'], '/login', 'App\Http\Controllers\Controller@view_login');
// Route::match(['get', 'post'], '/register', 'App\Http\Controllers\Controller@view_register');

// Auth::routes();

// Route::get('/home123', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// require_once __DIR__ . '/web.modules.php';

// use Illuminate\Support\Facades\Blade;

// var_dump(Blade::render("123"));
Auth::routes();



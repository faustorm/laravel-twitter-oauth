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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/twitter/', 'TwitterController@index');
Route::get('/twitter_callback/', 'TwitterController@twitter_callback');
Route::get('/twitter_login/', 'TwitterController@twitter_login');
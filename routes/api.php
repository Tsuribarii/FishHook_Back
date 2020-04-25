<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/home', 'HomeController@index')->name('home');

//인증
Route::group([

    'middleware' => 'api',
    // 'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    // Route::post('me', 'AuthController@me');

});

//커뮤니티
Route::get('/list', 'BoardController@index')->name('list');

Route::get('/create', 'BoardController@create');

Route::post('/store', 'BoardController@store');

Route::get('/show/{id}', 'BoardController@show');

Route::get('/edit/{id}', 'BoardController@edit');

Route::post('/update/{id}', 'BoardController@update');

Route::get('/delete/{id}', 'BoardController@destroy');

//마이페이지
Route::get('/myabout/{id}', 'MypageController@show');

Route::get('/myedit/{id}', 'MypageController@edit');

Route::post('/myupdate/{id}', 'MypageController@update');

Route::get('/mycheck/{id}', 'MypageController@checkshow');

//예약
Route::get('/recreate/{id}', 'ShipController@create');

Route::post('/reservation/{id}', 'ShipController@rentalStore');

Route::get('/shipshow/{id}', 'ShipController@shipshow');
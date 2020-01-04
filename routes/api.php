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

header('Access-Control-Allow-Origin:*');
header('access-control-allow-methods: GET,POST,OPTIONS');
header('access-control-allow-headers: X-Requested-With,Content-Type,X-Token-Auth,Authorization');

Route::post('register', 'userController@register');

Route::post('login', 'userController@login');

Route::post('convert', 'userController@convert')->middleware(['decodejwt']);


Route::get('getjwt', 'userController@getjwt');
Route::get('decode', 'userController@decode');

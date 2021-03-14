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
Route::middleware(['api'])->namespace('Api')->group(function () {
    Route::post('login', 'ApiController@login');
    Route::get('test', 'ApiController@test');
    Route::post('create', 'ApiController@create');
    Route::post('refresh', 'ApiController@refresh');
    Route::get('logout', 'ApiController@logout');
    Route::get('user/info', 'ApiController@me');
    Route::get('system/menu', 'SystemController@menu');
    Route::get('system/test', 'SystemController@test');
    Route::get('user/userList', 'UserController@userList');
    Route::post('user/changeUserStatus', 'UserController@changeUserStatus');
    Route::post('user/addUser', 'UserController@addUser');
    Route::get('user/userInfo', 'UserController@userInfo');
    Route::post('user/editUser', 'UserController@editUser');
    Route::delete('user/deleteUser', 'UserController@deleteUser');

    Route::middleware('refresh.token')->group(function ($router) {
        $router->get('profile', 'UserController@profile');
    });
    //公众号接口
    Route::get('wehcat', 'WechatController@wehcat');
});


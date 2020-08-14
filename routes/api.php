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
Route::middleware('api')->namespace('Api')->group(function (){
    Route::post('mp/token', 'ApiController@login');
    Route::post('refresh', 'ApiController@refresh');

    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('logout', 'ApiController@logout');
        Route::get('user', 'ApiController@getAuthUser');
    });
    Route::middleware('refresh.token')->group(function($router) {
        $router->get('profile','UserController@profile');
    });

    //公众号接口
    Route::get('wehcat', 'WechatController@wehcat');
});


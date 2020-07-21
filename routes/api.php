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
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\v1'], function ($api) {
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('register', 'AuthController@register');
        $api->get('info', 'AuthController@me');
        $api->post('get_token','Authorizations@getAccessToken');
    });
});

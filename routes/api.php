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
    $api->group(['prefix' => 'user'], function ($api) {
        $api->post('register', 'UserController@register');
        $api->post('login', 'UserController@login');
        $api->get('unauthorized', 'UserController@unauthorized');
        $api->get('info', 'UserController@info');
        $api->get('logout', 'UserController@logout');
        $api->post('refreshtoken', 'UserController@refreshtoken');
    });
});

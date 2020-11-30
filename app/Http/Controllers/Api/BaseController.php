<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseController extends Controller
{
    protected $user;



    public function easyWechatGetSession(){
        $config = config('wechat.mini_program');
        //return $app->auth->session($code);
        return Factory::miniProgram($config);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseController extends Controller
{
    protected $user;
    public $page_size = 10;
    public $page_num = 1;


    public function easyWechatGetSession(){
        $config = config('wechat.mini_program');
        //return $app->auth->session($code);
        return Factory::miniProgram($config);
    }
}

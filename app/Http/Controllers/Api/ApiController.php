<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use App\Model\UserAuth;
use  App\User;

class ApiController extends BaseController
{

   /* public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login','refresh']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }*/
    public function login(Request $request)
    {
        return 1;
        if ($request->openid == 1) {
            $arr['mobile'] = 18180414607;
            $arr['password'] = 123456;
            $token = auth('api')->attempt($arr);
            $data['token'] = $token;
            $data['expires_in'] = auth('api')->factory()->getTTL() * 60;
            return response()->json([
                'success' => "",
                'data' => $data,
            ], 200);
        }

        $wechatApp = $this->easyWechatGetSession();
        $wehcatInfo = $wechatApp->auth->session($request->code);
        if (!$request->openid && empty($wehcatInfo['openid'])) {
            if (isset($wehcatInfo) && !empty($wehcatInfo['errmsg'])) {
                return response()->json([
                    'data' => "",
                    'message' => $wehcatInfo['errmsg'],
                ], 406);
            } else {
                return response()->json([
                    'data' => "",
                    'message' => '用户openid没有获取到',
                ], 401);
            }
        }
        $userOauth = UserAuth::where('oauth_id', $wehcatInfo['openid'])->first();
        if (!$userOauth) {
            $user = new User();
            $user->name = "小程序用户#" . date('yd') . mt_rand(1000, 9999);
            $oauth = new UserAuth();
        }
        $data['token'] = $token;
        return response()->json([
            'success' => "",
            'data' => $data,
        ], 200);
    }

    public function create(){

    }

}

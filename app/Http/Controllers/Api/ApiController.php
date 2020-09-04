<?php

namespace App\Http\Controllers\Api;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Transformers\UserTransformer;
use App\Model\UserAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login','test']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }
    public function login(Request $request)
    {
        $input = $request->only('username', 'password');
        $jwt_token = null;
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => '用户名或密码错误！',
            ], 401);
        }
       return  $this->respondWithToken($jwt_token);
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->touser();
        $data['username'] = $user->username;
        $data['name'] = $user->name;
        $data['avatar'] = $user->avatar;
        return response()->json([
            'data' => $data,
            'message' => 'OK',
        ], 200);
    }

    public function logout()
    {
        JWTAuth::parseToken()->invalidate();
        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * 刷新jwt
     * @return \Illuminate\Http\JsonResponse
     * Created by zhaoluyao@jangene.com
     * Date:2020/9/4 16:22
     */
    public function refresh(){
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function create(){

    }

    public function test(){
        return bcrypt("admin123456");
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'code'=>200,
            'message'=>"OK",
            'data'=>[
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ],
        ]);
    }

}

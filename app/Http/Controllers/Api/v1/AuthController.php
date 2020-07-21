<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\User;



class AuthController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);//不需要登录验证的方法
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required'],
            'password' => ['required', 'min:6', 'max:16'],
        ];

        $payload = $request->only('name', 'email', 'password');
        $validator = Validator::make($payload, $rules);

        // 验证格式
        if ($validator->fails()) {
            return $this->response->array(['error' => $validator->errors()]);
        }

        // 创建用户
        $result = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => bcrypt($payload['password']),
        ]);

        if ($result) {
            return $this->response->array(['code' => 200, 'msg' => '创建用户成功', 'data' => ""]);
        } else {
            return $this->response->array(['code' => 422, 'msg' => '创建用户失败', 'data' => ""]);
        }

    }

    /**
     * Get a JWT token via given credentials.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $http = new \GuzzleHttp\Client();
        // 发送相关字段到后端应用获取授权令牌
        $response = $http->post(route('passport.token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => app('request')->input('email'),  // 这里传递的是邮箱
                'password' => app('request')->input('password'), // 传递密码信息
                'scope' => '*'
            ],
        ]);
        return $this->response()->array(['code'=>200,'msg' => "登录成功", 'data' => $data]);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->response->array($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return $this->response->array(['message' => '退出成功']);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }


}

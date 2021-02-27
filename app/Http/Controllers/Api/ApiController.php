<?php

namespace App\Http\Controllers\Api;

use App\Models\AdminRoleUser;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Transformers\UserTransformer;
use App\Model\UserAuth;

class ApiController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login','test','create']]);
        //$user = JWTAuth::parseToken()->touser();
        // 另外关于上面的中间件，官方文档写的是『auth:api』
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
        $user = AdminUser::find($user->id);
        $role_user = AdminRoleUser::where('user_id',$user->id)->first();
        $data['username'] = $user->username;
        $data['name'] = $user->name;
        $data['avatar'] = $user->avatar;
        $data['role'] = $role_user->name;
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

    public function create(Request $request){
        $input = $request->only('username', 'password');
        $user = new AdminUser();
        $user->username = $input['username'];
        $user->password = bcrypt($input['password']);
        $user->save();
        $data['username'] = $user->username;
        $data['name'] = $user->name;
        return response()->json([
            'data' => $data,
            'message' => 'OK',
        ], 200);
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

<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as OClient;

class UserController extends BaseController
{
    public $successStatus = 200;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','unauthorized','refreshtoken']]);//不需要登录验证的方法
    }

    public function login() {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $oClient = OClient::where('password_client', 1)->first();
            $data = $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
            return $this->response->array(['code' => $this->successStatus, 'msg' => '登陆成功', 'data' => $data->original]);
        } else {
            return $this->response()->array(['code'=>401,'msg'=>'Unauthorised']);
        }
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $password = $request->password;
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $oClient = OClient::where('password_client', 1)->first();
        return $this->getTokenAndRefreshToken($oClient, $user->email, $password);
    }

    public function info(){
        $user = Auth::user();
        return response()->json($user, $this->successStatus);
    }

    /**
     * 刷新
     * Created by zhaoluyao
     * Date:2020/7/22 10:43
     */
    public function refreshtoken(Request $request){
        $refresh_token = $request->header('Refreshtoken');
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;

        try {
            $response = $http->request('POST', 'http://api.com/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'scope' => '*',
                ],
            ]);
            return json_decode((string) $response->getBody(), true);
        } catch (Exception $e) {
            return response()->json("unauthorized", 401);
        }
    }

    public function unauthorized() {
        return response()->json("unauthorized", 401);
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://api.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }
}

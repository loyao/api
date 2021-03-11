<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['test']]);
        //$user = JWTAuth::parseToken()->touser();
        // 另外关于上面的中间件，官方文档写的是『auth:api』
    }

    public function userList(Request $request)
    {
        $page_size = $request->get("pagesize",$this->page_size);
        $page_num = $request->get("pagenum",$this->page_num);

        DB::connection()->enableQueryLog();
        $user_list = DB::table('users')
            ->offset($page_num)->limit($page_size)->get()->toArray();

        $res = DB::getQueryLog();

        return response()->json([
            'data' => $res,
            'message' => '',
            'code' =>200,
        ]);
    }
    public function test(Request $request)
    {
        $match ="^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$";
    }

}

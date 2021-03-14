<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

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
        $page_size = $request->get("pagesize", $this->page_size);
        $page_num = $request->get("pagenum", $this->page_num);
        $query = $request->get("query", "");
        //DB::connection()->enableQueryLog();//启动日志
        $limit = ($page_num - 1) * $page_size;

        $user_list = User::where('name', 'like', "%" . $query . '%')
            ->offset($limit)->limit($page_size)
            ->get()->toArray();


        //$res = DB::getQueryLog();//打印日志
        $num = User::where([
                ['name', 'like', $query . '%']
            ])
            ->count();
        $data['user_list'] = $user_list;
        $data['total'] = $num;
        $data['page'] = $page_num;
        //$data['sql'] = $res;

        return response()->json([
            'data' => $data,
            'message' => 'ok',
            'code' => 200,
        ]);
    }


    public function userInfo(Request $request)
    {
        $id = $request->get('id');
        $user = User::find($id, ['id', 'name', 'mobile', 'email']);

        if (!$user) {
            return response()->json([
                'data' => "",
                'message' => "用户已不存在",
                'code' => 422,
            ]);
        }
        return response()->json([
            'data' => $user,
            'message' => "success",
            'code' => 200,
        ]);
    }

    /**
     * 修改用户状态
     */
    public function changeUserStatus(Request $request)
    {
        $data = $request->post();
        $user = User::find($data['uid']);
        if (!$user) {
            return response()->json([
                'data' => "",
                'message' => "用户不存在",
                'code' => 422,
            ]);
        }
        if ($user->status == $data['status']) {
            return response()->json([
                'data' => "",
                'message' => "用户状态未修改",
                'code' => 422,
            ]);
        }
        $user->status = $data['status'];
        $res = $user->save();
        if (!$res) {
            return response()->json([
                'data' => "",
                'message' => "用户状态未修改",
                'code' => 422,
            ]);
        }
        return response()->json([
            'data' => "",
            'message' => "修改成功",
            'code' => 200,
        ]);
    }


    public function addUser(Request $request)
    {
        $data = $request->post();
        $password = $request->post('password', 123456);
        $has_user = User::find($data['mobile']);
        if ($has_user) {
            return response()->json([
                'data' => "",
                'message' => "用户已经存在",
                'code' => 422,
            ]);
        }
        $user = new User();
        $user->name = $data['name'];
        $user->mobile = $data['mobile'];
        $user->email = $data['email'];
        $user->password = bcrypt($password);
        $res = $user->save();
        if (!$res) {
            return response()->json([
                'data' => "",
                'message' => "添加失败",
                'code' => 422,
            ]);
        }
        return response()->json([
            'data' => "",
            'message' => "添加成功",
            'code' => 200,
        ]);
    }

    public function editUser(Request $request)
    {
        $data = $request->post();
        $password = $request->post('password');
        $user = User::find($data['id']);
        if (!$user) {
            return response()->json([
                'data' => "",
                'message' => "用户不存在",
                'code' => 422,
            ]);
        }
        $user->name = $data['name'];
        $user->mobile = $data['mobile'];
        $user->email = $data['email'];
        if ($password) {
            $user->password = bcrypt($password);
        }

        $res = $user->save();
        if (!$res) {
            return response()->json([
                'data' => "",
                'message' => "更新失败",
                'code' => 422,
            ]);
        }
        return response()->json([
            'data' => "",
            'message' => "更新成功",
            'code' => 200,
        ]);
    }

    /**
     * 删除
     */
    public function deleteUser(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'data' => "",
                'message' => "用户不存在",
                'code' => 422,
            ]);
        }
        $res = $user->delete();
        if (!$res) {
            return response()->json([
                'data' => "",
                'message' => "删除失败",
                'code' => 422,
            ]);
        }
        return response()->json([
            'data' => "",
            'message' => "删除成功",
            'code' => 200,
        ]);
    }
}

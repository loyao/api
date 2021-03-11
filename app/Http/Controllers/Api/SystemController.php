<?php

namespace App\Http\Controllers\Api;

use App\Models\AdminMenu;

class SystemController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['test']]);
        //$user = JWTAuth::parseToken()->touser();
        // 另外关于上面的中间件，官方文档写的是『auth:api』
    }

    /**
     * 菜单列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function menu()
    {
        $menu = new AdminMenu();
        $list = $menu->GetAdminMenuTree();
        return response()->json([
            'data' => $list,
            'message' => '',
            'code' =>200,
        ]);
    }


}

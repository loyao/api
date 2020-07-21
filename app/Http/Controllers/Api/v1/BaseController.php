<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    use Helpers;
    protected $guard = 'api';//设置使用guard为api选项验证

    /**
     * Get the guard to be used during authentication. 自动解析
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard($this->guard);
    }

}

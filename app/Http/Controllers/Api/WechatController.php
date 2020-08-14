<?php
namespace App\Http\Controllers\Api;

use EasyWeChat\Factory;

class wechatController extends BaseController
{
    private $app_id = "wx6022cf3d8af551c0";
    private $AppSecret = "c14821d54041c8b4d9f5e56893a7709f";
    public function wechat(){
        $config = [
            'app_id' => 'wx3cf0f39249eb0exx',
            'secret' => 'f1c242f4f28f735d4687abb469072axx',
            'response_type' => 'array',
        ];
        $app = Factory::officialAccount($config);
        $response = $app->server->serve();
        return $response;
    }
}

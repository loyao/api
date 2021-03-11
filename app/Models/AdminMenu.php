<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{

    protected $table = 'admin_menu';


    public static function GetAdminMenuTree()
    {

        $list = self::query()
            ->select('id','title','icon','url')
            ->where(['parent_id' => 0])
            ->orderBy('order')
            ->get()
            ->toArray();

        $tree = [];
        foreach ($list as $key => &$value) {
            $child = self::query()
                ->select('id','title','icon','url')
                ->where(['parent_id' => $value['id']])
                ->orderBy('order')
                ->get()
                ->toArray();
            $value['child'] = $child;
        }
        return $list;
    }



}

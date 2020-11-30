<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class AdminRoleUser extends Model
{

    protected $table = 'admin_role_users';


    public function role(){
        return $this->hasOne('App\Models\AdminRole','id','role_id');
    }
}

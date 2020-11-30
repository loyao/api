<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{

    protected $table = 'admin_roles';

    public function user(){
        return $this->belongsTo('App\Models\AdminRoleUser','role_id','id');
    }
}

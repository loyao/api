<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'admin_roles';
    
}

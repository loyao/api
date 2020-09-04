<?php

namespace App\Models;


use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable implements JWTSubject
{
    use  Notifiable;
    use HasDateTimeFormatter;
    protected $table = 'admin_users';

    protected $fillable = [
        'username', 'password',
    ];

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

<?php

namespace App\Models;


use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable implements JWTSubject
{
    use  Notifiable;
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

    public function role(){
        return $this->belongsToMany (
            'App\Model\AdminRole',
            'admin_role_users',
            'user_id',
            'role_id'
        );
    }
}

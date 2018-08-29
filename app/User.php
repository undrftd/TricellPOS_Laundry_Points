<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;

    
    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRememberToken()
    {
    return null; 
    }

    public function setRememberToken($value)
    {

    }

    public function getRememberTokenName()
    {
        return null; 
    }

    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute)
        {
            parent::setAttribute($key, $value);
        }
    }

    public function balance()
    {
        return $this->hasOne('App\Balance', 'id');
    }

    public function sale()
    {
        return $this->hasMany('App\Sales','member_id', 'id');
    }

    public function reload()
    {
        return $this->hasMany('App\Reload_sale','member_id', 'id');
    }

    public function timesheet()
    {
        return $this->hasMany('App\timesheet','user_id', 'id');
    }
}

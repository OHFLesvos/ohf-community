<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_super_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isSuperAdmin() {
        return $this->is_super_admin;
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function hasPermission($permissionKey) {
        return $this->roles->contains(function($role) use($permissionKey) { return $role->permissions->contains(function($value) use($permissionKey) { return $value->key == $permissionKey; }); });
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

}

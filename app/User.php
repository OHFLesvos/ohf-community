<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Iatstuti\Database\Support\NullableFields;

class User extends Authenticatable
{
    use Notifiable;
    use NullableFields;
   
    protected $nullable = [
		'tfa_secret',
    ];

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
        return $this->roles->contains(function($role) use($permissionKey) { 
            return $role->permissions->contains(function($value) use($permissionKey) {
                return $value->key == $permissionKey; 
            }); 
        });
    }

    public function permissions() {
        $permissions = [];
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        $configuredPermissions = Config::get('auth.permissions');
        return collect($permissions)
            ->filter(function($permission) use($configuredPermissions) {
                return isset($configuredPermissions[$permission->key]);
            })
            ->unique();
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

}

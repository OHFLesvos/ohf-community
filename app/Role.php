<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('is_admin');
    }

    /**
     * The users that belong to the role.
     */
    public function permissions()
    {
        return $this->hasMany('App\RolePermission');
    }

}

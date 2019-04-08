<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['role'];

    protected $fillable = ['key'];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

}

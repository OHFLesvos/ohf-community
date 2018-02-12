<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarResource extends Model
{
    protected $fillable = [ 'name' ];

    public function events()
    {
        return $this->hasMany('App\CalendarEvent', 'resource_id');
    }
}

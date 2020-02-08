<?php

namespace Modules\Collaboration\Entities;

use Illuminate\Database\Eloquent\Model;

class CalendarResource extends Model
{
    protected $fillable = [ 'name' ];

    public function events()
    {
        return $this->hasMany(CalendarEvent::class, 'resource_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [ 'text', 'start_date', 'end_date' ];

    public function type()
    {
        return $this->belongsTo('App\CalendarEventType');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

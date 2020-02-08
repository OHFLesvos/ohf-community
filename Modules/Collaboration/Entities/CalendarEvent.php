<?php

namespace Modules\Collaboration\Entities;

use App\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [ 'text', 'start_date', 'end_date' ];

    public function resource()
    {
        return $this->belongsTo(CalendarResource::class, 'resource_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

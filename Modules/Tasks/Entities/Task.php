<?php

namespace Modules\Tasks\Entities;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [ 'description' ];

    public function scopeOpen($query)
    {
        return $query->where('done_date', null);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}

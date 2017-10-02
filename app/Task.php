<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function scopeOpen($query) {
        return $query->where('done_date', null);
    }
}

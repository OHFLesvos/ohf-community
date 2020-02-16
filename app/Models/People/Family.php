<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    function members()
    {
        return $this->hasMany(Person::class);
    }
}

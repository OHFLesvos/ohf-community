<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Model;

class RevokedCard extends Model
{
    function person()
    {
        return $this->belongsTo(Person::class);
    }
}

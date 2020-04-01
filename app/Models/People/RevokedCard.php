<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Model;

class RevokedCard extends Model
{
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}

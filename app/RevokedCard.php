<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RevokedCard extends Model
{
    function person() {
        return $this->belongsTo('App\Person');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorageTransaction extends Model
{
    public function container() {
        return $this->belongsTo('App\StorageContainer', 'container_id');
    }
}

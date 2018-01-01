<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    public static function boot()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });

        parent::boot();
    }

    /**
     * Get all of the owning transactionable models.
     */
    public function transactionable()
    {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}

<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    public static function boot()
    {
        static::creating(function ($model) {
            if ($model->user_id !== null) {
                $model->user_name = $model->user->name;
            }
        });
        parent::boot();
    }

    public function getUserNameAttribute()
    {
        if ($this->user != null) {
            return $this->user->name;
        }
        return $this->attributes['user_name'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setUser(User $user)
    {
        $this->user()->associate($user);
    }

    public function setCurrentUser()
    {
        $this->setUser(Auth::user());
    }

    /**
     * Get the owning commentable model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}

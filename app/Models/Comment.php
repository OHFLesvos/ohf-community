<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    public static function boot(): void
    {
        static::creating(function ($model) {
            if ($model->user_id !== null) {
                $model->user_name = $model->user->name;
            }
        });
        parent::boot();
    }

    public function getUserNameAttribute(): string
    {
        if ($this->user != null) {
            return $this->user->name;
        }

        return $this->attributes['user_name'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setUser(User $user): void
    {
        $this->user()->associate($user);
    }

    public function setCurrentUser(): void
    {
        $this->setUser(Auth::user());
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}

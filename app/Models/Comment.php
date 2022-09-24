<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * @return Attribute<?string,never>
     */
    protected function userName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user !== null ? $this->user->name : $this->attributes['user_name'],
        );
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

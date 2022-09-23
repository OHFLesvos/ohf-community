<?php

namespace App\Models\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CommentsRelation
{
    public static function boot(): void
    {
        static::deleting(function ($model) {
            $model->comments()->delete();
        });
        parent::boot();
    }

    /**
     * Get all of the items's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Adds the given comment
     */
    public function addComment(Comment $comment): void
    {
        $this->comments()->save($comment);
    }

    /**
     * Adds the given comments
     */
    public function addComments(iterable $comments): void
    {
        $this->comments()->saveMany($comments);
    }
}

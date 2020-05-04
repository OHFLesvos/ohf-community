<?php

namespace App\Models\Traits;

use App\Models\Comment;

trait CommentsRelation
{
    public static function boot()
    {
        static::deleting(function ($model) {
            $model->comments()->delete();
        });
        parent::boot();
    }

    /**
     * Get all of the items's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Adds the given comment
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments()->save($comment);
    }

    /**
     * Adds the given comments
     *
     * @param array|Collection $comments
     */
    public function addComments($comments)
    {
        $this->comments()->saveMany($comments);
    }
}

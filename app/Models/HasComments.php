<?php

namespace App\Models;

trait HasComments
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
}

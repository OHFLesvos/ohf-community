<?php

namespace App\Support\Traits;

use App\Tag;

/**
 * Adds methods to @Illuminate\Database\Eloquent\Model to support tagging.
 */
trait HasTags
{
    /**
     * Get all of the tags of the model.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the tags sorted by name.
     */
    public function getTagsSortedAttribute()
    {
        return $this->tags->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE);
    }    

    public function syncTags(array $tags)
    {
        $tag_ids = [];
        foreach($tags as $tag_str) {
            $tag = Tag::where('name', $tag_str)->first();
            if ($tag != null) {
                $tag_ids[] = $tag->id;
            } else {
                $tag = new Tag();
                $tag->name = $tag_str;
                $tag->save();
                $tag_ids[] = $tag->id;
            }
        }
        $this->tags()->sync($tag_ids);
    }
}
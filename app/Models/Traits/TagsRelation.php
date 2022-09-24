<?php

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * Adds methods to @Illuminate\Database\Eloquent\Model to support tagging.
 */
trait TagsRelation
{
    /**
     * Get all of the tags of the model.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the tags sorted by name.
     *
     * @return Attribute<Collection,never>
     */
    protected function tagsSorted(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->tags->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
        );
    }

    /**
     * Set tags from an array of values (tag names).
     * Overrides existing tag relations.
     */
    public function setTags(iterable $tags): void
    {
        $tag_ids = [];
        foreach ($tags as $tag_str) {
            $tag = Tag::where('name', $tag_str)->first();
            if ($tag !== null) {
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

    /**
     * Scope a query to only include records having the given tags.
     * If no tags are specified, all records will be returned.
     */
    public function scopeWithAllTags(Builder $query, iterable $tagSlugs = []): Builder
    {
        foreach ($tagSlugs as $tag) {
            $query->whereHas('tags', fn (Builder $qry) => $qry->where('slug', $tag));
        }

        return $query;
    }
}

<?php

namespace App\Models\Traits;

use App\Tag;
use Illuminate\Support\Collection;

/**
 * Adds methods to @Illuminate\Database\Eloquent\Model to support tagging.
 */
trait TagsRelation
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
        return $this->tags->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
    }

    /**
     * Sets thgs from the given JSON string which must have the form:
     * [{"value":"Tag 1"},{"value":"Tag 2"}]
     *
     * @param null|string $json
     * @return void
     */
    public function setTagsFromJson(?string $json = null)
    {
        $items = collect(json_decode($json))
            ->pluck('value')
            ->unique();
        $this->setTags($items);
    }

    /**
     * Set tags from an array of values (tag names).
     * Overrides existing tag relations.
     *
     * @param Collection<string> $tags
     * @return void
     */
    public function setTags(Collection $tags)
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
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param null|array<string> $tags list of tags (slug values)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTags($query, ?array $tags = [])
    {
        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                $query->whereHas('tags', fn ($query) => $query->where('slug', $tag));
            }
        }
        return $query;
    }

    /**
     * Scope a query to only include records having any of the given tags.
     * If no tags are specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param null|array<string> $tags list of tags (slug values)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithTags($query, ?array $tags = [])
    {
        if (count($tags) > 0) {
            $query->whereHas('tags', fn ($query) => $query->whereIn('slug', $tags));
        }
        return $query;
    }
}

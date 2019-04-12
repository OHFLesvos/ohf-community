<?php

namespace Modules\Wiki\Entities;

use App\Tag;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

use OwenIt\Auditing\Contracts\Auditable;

class WikiArticle extends Model implements Auditable
{
    use Sluggable;
    use \OwenIt\Auditing\Auditable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get all of the tags for the wiki article.
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
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

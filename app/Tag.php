<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
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
     * Get all of the wiki articles that are assigned this tag.
     */
    public function wikiArticles()
    {
        return $this->morphedByMany(\App\Models\Collaboration\WikiArticle::class, 'taggable');
    }

    /**
     * Get all of the donors that are assigned this tag.
     */
    public function donors()
    {
        return $this->morphedByMany(\App\Models\Fundraising\Donor::class, 'taggable');
    }
}

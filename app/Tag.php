<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

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
                'source' => 'name'
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
     * Get all of the wiki articles that are assigned this tag.
     */
    public function wikiArticles()
    {
        return $this->morphedByMany('Modules\Wiki\Entities\WikiArticle', 'taggable');
    }

    /**
     * Get all of the donors that are assigned this tag.
     */
    public function donors()
    {
        // TODO: Modularization
        return $this->morphedByMany('Modules\Fundraising\Entities\Donor', 'taggable');
    }
}

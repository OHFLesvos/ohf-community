<?php

namespace Modules\Wiki\Entities;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use AustinHeap\Database\Encryption\Traits\HasEncryptedAttributes;
use OwenIt\Auditing\Contracts\Auditable;

class WikiArticle extends Model implements Auditable
{
    use Sluggable;
    use HasEncryptedAttributes;
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

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encrypted = [
        'content',
    ];
}

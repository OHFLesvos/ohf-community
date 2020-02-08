<?php

namespace Modules\Collaboration\Entities;

use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    protected $table = 'kb_article_views';

    public $timestamps = false;

    protected $fillable = [
        'value'
    ];

    /**
     * Get all of the owning imageable models.
     */
    public function viewable()
    {
        return $this->morphTo();
    }
}

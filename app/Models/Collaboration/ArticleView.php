<?php

namespace App\Models\Collaboration;

use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    protected $table = 'kb_article_views';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'value',
    ];

    /**
     * Get all of the owning imageable models.
     */
    public function viewable()
    {
        return $this->morphTo();
    }
}

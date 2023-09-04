<?php

namespace App\Models\Visitors;

use Illuminate\Database\Eloquent\Model;

class VisitorParentChild extends Model
{
    protected $table = 'visitor_parent_child';

    protected $fillable = [
        'parent_id',
        'child_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Visitor::class, 'parent_id');
    }

    public function child()
    {
        return $this->belongsTo(Visitor::class, 'child_id');
    }
}

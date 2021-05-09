<?php

namespace App\Models\Accounting;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Project extends Model
{
    use HasFactory;
    use NullableFields;

    protected $table = 'accounting_projects';

    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];

    protected $nullable = [
        'description',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function parent()
    {
        return $this->belongsTo(Project::class);
    }

    public function children()
    {
        return $this->hasMany(Project::class, 'parent_id');
    }

    public function getIsRootAttribute()
    {
        return $this->parent_id == null;
    }

    public function scopeIsRoot(Builder $query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForParent(Builder $query, int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    public function scopeForFilter($query, ?string $filter = '')
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where('name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('description', 'LIKE', '%' . $filter . '%');
            });
        }
        return $query;
    }

    public function getPathElements(): Collection
    {
        $elements = collect([$this]);
        $elem = $this;
        while ($elem->parent != null) {
            $elements->prepend($elem->parent);
            $elem = $elem->parent;
        }
        return $elements;
    }
}

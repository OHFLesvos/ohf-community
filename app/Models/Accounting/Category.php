<?php

namespace App\Models\Accounting;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use NullableFields;

    protected $table = 'accounting_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $nullable = [
        'description',
    ];

    public function transactions()
    {
        return $this->hasMany(MoneyTransaction::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getIsRootAttribute()
    {
        return $this->parent_id == null;
    }

    public function scopeIsRoot(Builder $query)
    {
        return $query->whereNull('parent_id');
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
}

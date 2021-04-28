<?php

namespace App\Models\Accounting;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    use NullableFields;

    protected $table = 'accounting_projects';

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
        return $this->belongsTo(Project::class);
    }

    public function getIsRootAttribute()
    {
        return $this->parent_id == null;
    }

    public function scopeIsRoot(Builder $query)
    {
        return $query->whereNull('parent_id');
    }
}

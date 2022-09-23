<?php

namespace App\Models;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ImportFieldMapping extends Model
{
    use NullableFields;

    protected $nullable = [
        'to',
    ];

    protected $fillable = [
        'model',
        'from',
        'to',
        'append',
    ];

    public function scopeModel(Builder $query, string $model): Builder
    {
        return $query->where('model', $model);
    }
}

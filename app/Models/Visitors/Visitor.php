<?php

namespace App\Models\Visitors;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    use NullableFields;

    protected $fillable = [
        'name',
        'id_number',
        'gender',
        'date_of_birth',
        'nationality',
        'living_situation',
        'anonymized',
    ];

    protected $nullable = [
        'name',
        'id_number',
        'gender',
        'date_of_birth',
        'nationality',
        'living_situation',
    ];

    protected $dates = [
        'date_of_birth',
    ];

    protected $casts = [
        'anonymized' => 'boolean',
    ];

    public function checkins()
    {
        return $this->hasMany(VisitorCheckin::class);
    }

    public function scopeForFilter(Builder $query, ?string $filter = ''): Builder
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where('name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('id_number', $filter)
                    ->orWhere('date_of_birth', $filter);
            });
        }

        return $query;
    }
}

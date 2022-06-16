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
        'liability_form_signed',
    ];

    protected $nullable = [
        'name',
        'id_number',
        'gender',
        'date_of_birth',
        'nationality',
        'living_situation',
        'liability_form_signed',
    ];

    protected $dates = [
        'date_of_birth',
        'liability_form_signed',
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
        if (!empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where('name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('id_number', 'LIKE', '%' . $filter . '%')
                    ->orWhere('date_of_birth', $filter);
            });
        }

        return $query;
    }

    public function getGenderLabelAttribute()
    {
        if ($this->gender == 'male') {
            return __('male');
        }
        if ($this->gender == 'female') {
            return __('female');
        }
        if ($this->gender == 'other') {
            return __('other');
        }
        return $this->gender;
    }
}

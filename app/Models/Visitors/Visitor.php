<?php

namespace App\Models\Visitors;

use App\Models\Traits\InDateRangeScope;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Visitor extends Model
{
    use HasFactory;
    use InDateRangeScope;
    use NullableFields;

    protected $fillable = [
        'name',
        'id_number',
        'membership_number',
        'gender',
        'date_of_birth',
        'nationality',
        'living_situation',
        'anonymized',
        'liability_form_signed',
        'remarks',
        'parental_consent_given',
    ];

    protected $nullable = [
        'name',
        'id_number',
        'membership_number',
        'gender',
        'date_of_birth',
        'nationality',
        'living_situation',
        'liability_form_signed',
        'remarks',
    ];

    protected $casts = [
        'anonymized' => 'boolean',
        'parental_consent_given' => 'boolean',
        'date_of_birth' => 'datetime:Y-m-d',
        'liability_form_signed' => 'datetime:Y-m-d',
    ];

    protected $visible = [
        'id',
        'name',
        'id_number',
        'membership_number',
        'gender',
        'date_of_birth',
        'nationality',
        'living_situation',
        'anonymized',
        'liability_form_signed',
        'remarks',
        'parental_consent_given',
    ];

    public function checkins(): HasMany
    {
        return $this->hasMany(VisitorCheckin::class);
    }

    public function parents(): HasManyThrough
    {
        return $this->hasManyThrough(
            Visitor::class,
            VisitorParentChild::class,
            'child_id',
            'id',
            'id',
            'parent_id'
        );
    }

    public function children(): HasManyThrough
    {
        return $this->hasManyThrough(
            Visitor::class,
            VisitorParentChild::class,
            'parent_id',
            'id',
            'id',
            'child_id'
        );
    }
}

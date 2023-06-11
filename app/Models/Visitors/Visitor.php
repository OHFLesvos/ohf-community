<?php

namespace App\Models\Visitors;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'remarks',
        'parental_consent_given',
    ];

    protected $nullable = [
        'name',
        'id_number',
        'gender',
        'date_of_birth',
        'nationality',
        'living_situation',
        'liability_form_signed',
        'remarks',
    ];

    protected $dates = [
        'date_of_birth',
        'liability_form_signed',
    ];

    protected $casts = [
        'anonymized' => 'boolean',
        'parental_consent_given' => 'boolean',
    ];

    // protected $appends = [
    //     'parents',
    //     'children',
    // ];

    public function checkins(): HasMany
    {
        return $this->hasMany(VisitorCheckin::class);
    }

    // public function getParentsAttribute()
    // {
    //     return $this->parents()->get();
    // }

    public function parents()
    {
        return $this->hasManyThrough(
            Visitor::class,
            ParentChild::class, 
            'child_id',
            'id',
            'id',
            'parent_id'
        );
    }

    // public function getChildrenAttribute()
    // {
    //     return $this->parents()->get();
    // }

    public function children()
    {
        return $this->hasManyThrough(
            Visitor::class,
            ParentChild::class, 
            'parent_id',
            'id',
            'id',
            'child_id'
        );
    }
}
